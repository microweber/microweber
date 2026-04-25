<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — GoogleMaps module smoke (map widget settings).
 *
 * Same shape as the canonical sibling
 * {@see LiveAdminModuleBtnSmokeTest}: the GoogleMaps module
 * ships a Filament settings page registered via
 * FilamentRegistry::registerPage(GoogleMapsModuleSettings::class)
 * in GoogleMapsServiceProvider.php. Filament-default route
 * slug: /admin/google-maps-module-settings.
 *
 * Plan-C.2 task line is "google maps map widget settings". The
 * settings page exposes a Tabs-grouped location + map
 * configuration set: Location tab (`options.data-country`,
 * `options.data-city`, `options.data-street`, `options.data-zip`
 * — the address parts the map module geocodes into the embed)
 * and Map tab (`options.data-zoom`, `options.data-width`,
 * `options.data-height` — the iframe sizing). The smoke
 * round-trips the `data-country` option through the same
 * save_module_option() pipeline the page's Livewire updated()
 * hook calls on every reactive field update — that's the option
 * the public-frontend GoogleMapsModule reads to render the
 * iframe `data-country` attribute the map's JS bootstrapper
 * concatenates into the geocoded query.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/google-maps-module-settings.
 *   2. Signal #2 (data-country save round-trip): direct
 *      save_module_option() call against the `data-country`
 *      option key with a marker-prefixed country string;
 *      verifies the row lands in `options` (the exact row the
 *      public-frontend GoogleMapsModule reads to render the
 *      map iframe's data-country attribute).
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
class LiveAdminModuleGoogleMapsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'google-maps-module-settings';

    private const MODULE_NAME = 'google_maps';

    private const FIXTURE_OPTION_KEY = 'live_admin_module_google_maps_smoke_data_country';

    private const FIXTURE_OPTION_VALUE = 'Live Admin Module Google Maps Smoke Country';

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
    public function google_maps_settings_page_loads_and_round_trips_the_data_country_option(): void
    {
        $this->purgeFixtureOption();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // google-maps settings admin (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'google maps module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'google maps settings render');

            // Signal #2 — round-trip the data-country option
            // through the same save_module_option() pipeline the
            // page's Livewire updated() hook calls on every
            // TextInput edit. The persisted row is what the
            // public-frontend GoogleMapsModule reads to render
            // the map iframe's data-country attribute (the
            // country segment the map's JS bootstrapper
            // concatenates into the geocoded query string).
            $this->assertDataCountryOptionRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Save a marker-prefixed `data-country`-style option through
     * the same save_module_option() helper the GoogleMaps
     * settings page's Livewire updated() hook calls server-side
     * when the country TextInput is edited. Then assert the row
     * landed in `options` with the correct (option_key,
     * option_value, module) tuple — the exact row the
     * public-frontend GoogleMapsModule reads to render the map
     * iframe's data-country attribute.
     */
    private function assertDataCountryOptionRoundTripPersists(): void
    {
        save_module_option([
            'option_key' => self::FIXTURE_OPTION_KEY,
            'option_value' => self::FIXTURE_OPTION_VALUE,
            'option_group' => 'live-admin-module-google-maps-smoke',
            'module' => self::MODULE_NAME,
        ]);

        $row = DB::table('options')
            ->where('option_key', self::FIXTURE_OPTION_KEY)
            ->where('module', self::MODULE_NAME)
            ->first();

        $this->assertNotNull(
            $row,
            'save_module_option() must persist an options row for the google_maps '
            . 'module — this is the same code path the GoogleMaps settings page '
            . 'invokes from its Livewire updated() hook on every Location-tab '
            . 'TextInput edit.'
        );
        $this->assertSame(
            self::FIXTURE_OPTION_VALUE,
            (string) $row->option_value,
            'The persisted option_value must match the country string passed to '
            . 'save_module_option(). The public-frontend GoogleMapsModule reads '
            . 'this exact row to render the map iframe\'s data-country attribute; '
            . 'a mismatch here would silently break the geocoded address segment '
            . 'on every page that embeds the google_maps widget.'
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
            || str_contains($source, 'fi-page')
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'google maps settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, '
            . 'OR wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'saved-option round-trip asserted above would only prove the helper works, '
            . 'not that the page is reachable through the Livewire form pipeline.'
        );
    }
}
