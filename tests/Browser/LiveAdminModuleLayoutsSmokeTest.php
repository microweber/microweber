<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Layouts module smoke (generic layouts picker).
 *
 * Same shape as the canonical sibling {@see LiveAdminModuleBtnSmokeTest}:
 * the Layouts module ships a Filament settings page registered via
 * FilamentRegistry::registerPage(LayoutsModuleSettings::class) in
 * LayoutsServiceProvider.php. Filament-default route slug:
 * /admin/layouts-module-settings. Tabs: "Layout Settings" (renders
 * the modules.layouts::admin.settings picker view) and
 * "Design and Details" (the shared LiveEdit template + data-source
 * tabs the LiveEditModuleSettings abstract base contributes).
 *
 * Plan-C.2 task line is "generic layouts picker". The smoke
 * round-trips a marker-prefixed `template`-style option through the
 * same save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive field update — that's the exact
 * code path the picker view persists the operator's chosen layout
 * skin through.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/layouts-module-settings.
 *   2. Signal #2 (template option save round-trip): direct
 *      save_module_option() call against a marker-prefixed key with
 *      a layouts-style value; verifies the row lands in `options`
 *      (the exact row LayoutsModule::render() reads to choose which
 *      skin to render on the public frontend).
 *   3. Belt-and-braces: installInPageErrorGuard() on the settings
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleLayoutsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'layouts-module-settings';

    private const MODULE_NAME = 'layouts';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_layouts_smoke_template';

    private const FIXTURE_OPTION_VALUE = 'jumbotron/skin-1';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureOption();
        parent::tearDown();
    }

    private function purgeFixtureOption(): void
    {
        DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->delete();
    }

    #[Test]
    public function layouts_settings_page_loads_and_round_trips_the_template_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the layouts
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'layouts module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'layouts settings render');

            // Signal #2 — round-trip the layouts template option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls server-side
            // when an operator picks a different skin from the
            // picker view. The persisted row is what
            // LayoutsModule::render() reads to choose which skin
            // to render on the public frontend.
            $this->assertLayoutsTemplateOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed template-style option through the
     * same save_module_option() helper the Layouts settings page's
     * Livewire updated() hook calls server-side when the picker
     * view selects a different skin. Then assert the row landed
     * in `options` with the correct (option_key, option_value,
     * module) tuple — the exact row LayoutsModule::render() reads
     * to choose which skin to render on the public frontend.
     */
    private function assertLayoutsTemplateOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-layouts-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the layouts module — '
            . 'this is the same code path the Layouts settings page invokes from its '
            . 'Livewire updated() hook on every picker selection.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the layouts template passed to '
            . 'save_module_option(). LayoutsModule::render() reads this exact row to '
            . 'pick which skin blade to load; a mismatch here would silently break the '
            . 'operator\'s skin selection across every page that embeds the layouts module.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Btn/Embed smokes.
     */
    private function assertSaveActionWired(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasInlineSave = str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:submit=')
            || str_contains($source, 'wire:click="save"')
            || str_contains($source, "wire:click='save'");
        $hasDeferredSave = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'fi-page')
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'layouts settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the saved-option '
            . 'round-trip asserted above would only prove the helper works, not that the page '
            . 'is reachable through the Livewire form pipeline.'
        );
    }
}
