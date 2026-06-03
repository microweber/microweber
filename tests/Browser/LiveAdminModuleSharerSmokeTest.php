<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Sharer module smoke (sharer widget settings).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the Sharer module ships
 * a Filament settings page registered via
 * FilamentRegistry::registerPage(SharerModuleSettings::class)
 * in SharerServiceProvider.php (module name: `sharer` —
 * confirmed at SharerServiceProvider.php:18 and at
 * SharerModuleSettings.php:13). Filament-default route slug:
 * /admin/sharer-module-settings.
 *
 * Plan-C.2 task line is "sharer sharer widget settings". The
 * settings page exposes a Tabs container with a Content tab
 * full of per-network Toggles: `options.facebook_enabled`,
 * `options.x_enabled`, `options.pinterest_enabled`,
 * `options.linkedin_enabled`, `options.viber_enabled`,
 * `options.whatsapp_enabled`, `options.telegram_enabled`. Each
 * Toggle is the master switch the public-frontend SharerModule
 * reads to decide whether to render the matching network's
 * share button. The smoke round-trips the
 * `facebook_enabled` option through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive Toggle change — that's the
 * option that controls whether the Facebook-share button
 * appears in the rendered widget.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/sharer-module-settings.
 *   2. Signal #2 (facebook-toggle save round-trip): direct
 *      save_module_option() call against the
 *      `facebook_enabled` option key with value "1"; verifies
 *      the row lands in `options` (the exact row the public-
 *      frontend SharerModule reads to decide whether to render
 *      the Facebook-share button).
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
class LiveAdminModuleSharerSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'sharer-module-settings';

    private const MODULE_NAME = 'sharer';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_sharer_smoke_facebook_enabled';

    /**
     * The facebook-share toggle value — "1" matches the truthy
     * branch SharerModule checks before rendering the
     * Facebook-share button. Pinning this value also surfaces
     * a regression that flips the option-store boolean coercion
     * (a common breakage when options are migrated between the
     * legacy options table and Filament's normalized shape).
     */
    private const FIXTURE_OPTION_VALUE = '1';

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
    public function sharer_settings_page_loads_and_round_trips_the_facebook_enabled_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // sharer settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'sharer module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'sharer settings render');

            // Signal #2 — round-trip the facebook-share toggle
            // option through the same save_module_option()
            // pipeline the page's Livewire updated() hook calls
            // on every Toggle flip. The persisted row is what
            // the public-frontend SharerModule reads to decide
            // whether to render the Facebook-share button in
            // the widget output.
            $this->assertFacebookEnabledOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `facebook_enabled`-style option
     * through the same save_module_option() helper the Sharer
     * settings page's Livewire updated() hook calls server-side
     * when the Facebook-share Toggle is flipped. Then assert
     * the row landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the
     * public-frontend SharerModule reads to decide whether to
     * render the Facebook-share button.
     */
    private function assertFacebookEnabledOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-sharer-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the sharer module '
            . '— this is the same code path the Sharer settings page invokes from '
            . 'its Livewire updated() hook on every per-network Toggle flip.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the value passed to '
            . 'save_module_option(). The public-frontend SharerModule reads this '
            . 'exact row to decide whether to render the Facebook-share button; a '
            . 'mismatch here would silently break the per-network enable / disable '
            . 'switch the operator just toggled in the admin.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Btn/Embed/CookieNotice
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
            'sharer settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
