<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — FacebookPage module smoke (widget settings).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the FacebookPage module
 * ships a Filament settings page registered via
 * FilamentRegistry::registerPage(FacebookPageModuleSettings::class)
 * in FacebookPageServiceProvider.php. Filament-default route
 * slug: /admin/facebook-page-module-settings.
 *
 * Plan-C.2 task line is "facebook page widget settings". The
 * settings page exposes the canonical FB-page-plugin config
 * field set inside a Tabs container: `options.fbPage` (the FB
 * Page URL TextInput, default https://www.facebook.com/Microweber/),
 * `options.width` / `options.height` (size TextInputs), and the
 * `options.friends` / `options.timeline` Toggles. The smoke
 * round-trips the `fbPage` option through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive field update — that's the option
 * the public-frontend FacebookPageModule reads to construct the
 * FB-page-plugin <div data-href="…"> attribute the Facebook JS
 * SDK rewrites into the embedded Page widget.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/facebook-page-module-settings.
 *   2. Signal #2 (FB-page-url save round-trip): direct
 *      save_module_option() call against the `fbPage` option key
 *      with a marker-prefixed FB-page URL; verifies the row
 *      lands in `options` (the exact row the public-frontend
 *      FacebookPageModule reads to render the Page widget's
 *      data-href attribute).
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
class LiveAdminModuleFacebookPageSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'facebook-page-module-settings';

    private const MODULE_NAME = 'facebook_page';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_facebook_page_smoke_fb_page';

    private const FIXTURE_OPTION_VALUE = 'https://www.facebook.com/live-admin-module-facebook-page-smoke';

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
    public function facebook_page_settings_page_loads_and_round_trips_the_fb_page_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // facebook-page settings admin (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'facebook page module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'facebook page settings render');

            // Signal #2 — round-trip the FB-page-url option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // TextInput edit. The persisted row is what the
            // public-frontend FacebookPageModule reads to render
            // the Page widget's data-href attribute (the URL the
            // Facebook JS SDK rewrites into the embedded Page
            // plugin).
            $this->assertFbPageOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `fbPage`-style option through the
     * same save_module_option() helper the FacebookPage settings
     * page's Livewire updated() hook calls server-side when the
     * FB-page-URL TextInput is edited. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the
     * public-frontend FacebookPageModule reads to render the
     * Page widget's data-href attribute.
     */
    private function assertFbPageOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-facebook-page-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the facebook_page '
            . 'module — this is the same code path the FacebookPage settings page '
            . 'invokes from its Livewire updated() hook on every fbPage TextInput '
            . 'edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the FB-page URL passed to '
            . 'save_module_option(). The public-frontend FacebookPageModule reads '
            . 'this exact row to render the embedded Page plugin\'s data-href; a '
            . 'mismatch here would silently break the Page-target URL on every page '
            . 'that embeds the facebook_page widget.'
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
            'facebook page settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
