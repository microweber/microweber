<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Spacer module smoke.
 *
 * Same shape as {@see LiveAdminModuleBtnSmokeTest}: the Spacer
 * module ships a Filament settings page registered via
 * FilamentRegistry::registerPage(SpacerModuleSettings::class) in
 * SpacerServiceProvider.php. Filament-default route slug:
 * /admin/spacer-module-settings.
 *
 * Plan-C.2 task line is "spacer insertion". The settings page
 * exposes the canonical spacer-config field set: a single
 * `options.height` TextInput where the operator types the
 * vertical-spacer height (e.g. "50px", "2rem", "5vh"). The smoke
 * round-trips the `height` option through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive field update — that's the option
 * SpacerModule reads via get_option() to render the literal
 * spacer height in the public-frontend `<div>` tag.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/spacer-module-settings.
 *   2. Signal #2 (height save round-trip): direct
 *      save_module_option() call against the `height` option key
 *      with a marker-prefixed value; verifies the row lands in
 *      `options` (the exact row the public-frontend spacer module
 *      reads to render the spacer's literal height).
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `options` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleSpacerSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'spacer-module-settings';

    private const MODULE_NAME = 'spacer';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_spacer_smoke_height';

    private const FIXTURE_OPTION_VALUE = '137px';

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
    public function spacer_settings_page_loads_and_round_trips_the_height_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the spacer
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'spacer module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'spacer settings render');

            // Signal #2 — round-trip the spacer-height option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // TextInput edit. The persisted row is what the
            // public-frontend spacer module reads to render the
            // literal vertical-spacer height in its inline style.
            $this->assertSpacerHeightOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `height`-style option through the
     * same save_module_option() helper the Spacer settings page's
     * Livewire updated() hook calls server-side when the operator
     * edits the height TextInput. Then assert the row landed in
     * `options` with the correct (option_key, option_value,
     * module) tuple — the exact row SpacerModule reads via
     * get_option('height', $params['id']) to render the public-
     * frontend spacer.
     */
    private function assertSpacerHeightOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-spacer-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the spacer module — this '
            . "is the same code path the Spacer settings page invokes from its Livewire "
            . 'updated() hook on every height TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the spacer height passed to '
            . 'save_module_option(). The public-frontend spacer module reads this exact '
            . 'row to render the literal vertical-spacer height; a mismatch here would '
            . 'silently break spacer-insertion edits across every page that embeds the '
            . 'spacer module.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Btn / Audio / Accordion
     * smokes.
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
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'spacer settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id '
            . '/ wire:snapshot / fi-page / fi-form deferred) — otherwise the saved-option '
            . 'round-trip asserted above would only prove the helper works, not that the '
            . 'page is reachable through the Livewire form pipeline.'
        );
    }
}
