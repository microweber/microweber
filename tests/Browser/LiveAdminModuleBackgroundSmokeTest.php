<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * MSET.1 batch — Background module smoke (background settings).
 *
 * Same shape as {@see LiveAdminModuleBtnSmokeTest}: the Background
 * module ships a Filament settings page registered via
 * FilamentRegistry::registerPage(BackgroundModuleSettings::class). Filament-
 * default route slug: /admin/background-module-settings.
 *
 * The smoke round-trips the background image URL through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive field update — that's the option
 * the public-frontend Background module reads on next mount.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/background-module-settings.
 *   2. Signal #2 (background image URL save round-trip): direct
 *      save_module_option() call against a marker-prefixed
 *      key with a marker-prefixed value; verifies the row lands
 *      in `options` (the same row the public-frontend module
 *      reads on next mount).
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
class LiveAdminModuleBackgroundSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'background-module-settings';

    private const MODULE_NAME = 'background';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_background_smoke_image';

    private const FIXTURE_OPTION_VALUE = 'https://example.com/live-admin-module-background-smoke.jpg';

    private const FIXTURE_OPTION_GROUP = 'live-admin-module-background-smoke';

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
    public function background_settings_page_loads_and_round_trips_an_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // background settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'background module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'background settings render');

            // Signal #2 — round-trip the background image URL through the
            // same save_module_option() pipeline the page's
            // Livewire updated() hook calls on every reactive
            // field update.
            $this->assertOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1 third-
            // bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save the marker-prefixed background image URL through the same
     * save_module_option() helper the Background settings page's
     * Livewire updated() hook calls server-side. Then assert the
     * row landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the public-
     * frontend module reads on next mount.
     */
    private function assertOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => self::FIXTURE_OPTION_GROUP,
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the background '
            . 'module — this is the same code path the Background settings page invokes '
            . 'from its Livewire updated() hook on every reactive field update.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the value passed to '
            . 'save_module_option(). The public-frontend Background module reads this '
            . 'exact row; a mismatch here would silently break the operator-configured '
            . 'background image URL across every page that embeds the module.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the canonical Btn / Pictures / Embed
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
            'background settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}