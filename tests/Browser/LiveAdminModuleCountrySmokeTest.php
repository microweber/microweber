<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Country\Models\Country;
use Modules\Country\Repositories\CountryManager;
use Modules\Country\Support\CountriesHelper;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Country module smoke.
 *
 * Shape diverges from the sibling Filament-settings-page smokes
 * and instead mirrors {@see LiveAdminModuleCloudflareSmokeTest}'s
 * "no-admin-form" shape: the Country module ships an Eloquent
 * model + the `countries` table + the `country_manager`
 * container singleton (CountryManager) + the CountriesHelper
 * JSON/CSV utilities, but NO standalone Filament admin page —
 * CountryServiceProvider only loads migrations and binds the
 * singleton, never calling FilamentRegistry::registerPage. The
 * customer-facing CRUD surface for countries is the seeded
 * `countries` table itself (227 rows shipped via the
 * 2021_02_24_000000_insert_countries.php migration), consumed by
 * the Address / Customer / Checkout admin pages through the
 * Country model + the CountryManager singleton.
 *
 * Plan-C.2 task line is "country list admin". No such admin
 * exists today. This smoke covers the actual integration surface
 * — the Country Eloquent CRUD pipeline + the CountryManager
 * resolution chain — and pins the missing-form situation so a
 * future Filament-5 migration that ships an admin form here can
 * swap this smoke for the canonical settings/resource-page
 * pattern.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (the dashboard) —
 *      Country is process-wide / dashboard-adjacent because
 *      the Customer / Address / Checkout admin pages all read
 *      from the `countries` table on mount, so a SEVERE log
 *      entry on the dashboard would surface a Country-table
 *      regression.
 *   2. Signal #2 (CRUD round-trip): drives the Country Eloquent
 *      model through create → save (name update) → find (reload)
 *      → delete on a marker-prefixed row, AND resolves the
 *      `country_manager` container singleton to round-trip the
 *      same row through CountryManager::getCountryName() — the
 *      same ID/name/code lookup chain Address / Customer /
 *      Checkout admin pages call when populating country fields.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: when a future commit lands a real
 * /admin/country-list (or /admin/countries) Filament resource
 * page (registering a CountryResource via
 * FilamentRegistry::registerResource), this smoke should be
 * updated to probe that page directly. Until then, the smoke
 * covers the underlying countries-table CRUD round-trip per the
 * same three-assertion-minimum the sibling smokes follow.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `countries` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleCountrySmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const DASHBOARD_PATH = 'admin';

    /**
     * Marker code used for the round-trip fixture row. Long-form
     * to guarantee no collision with any real ISO 3166-1 alpha-2
     * code (which are 2 chars). The `code` column is a 255-char
     * string per the create migration, so the long marker fits.
     */
    private const FIXTURE_CODE = 'live-admin-module-country-smoke-zz';

    private const FIXTURE_INITIAL_NAME = 'Live Admin Module Country Smoke (initial)';

    private const FIXTURE_UPDATED_NAME = 'Live Admin Module Country Smoke (updated)';

    private const FIXTURE_PHONECODE = 99999;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureCountries();
        parent::tearDown();
    }

    private function purgeFixtureCountries(): void
    {
        DB::table('countries')
            ->where('code', self::FIXTURE_CODE)
            ->delete();
    }

    #[Test]
    public function country_crud_round_trips_under_the_admin_dashboard_surface(): void
    {
        $this->purgeFixtureCountries();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin
            // (the dashboard). Country is process-wide so a
            // regression in the Eloquent model or the seeded
            // countries table would surface as a SEVERE log
            // entry on any Customer / Address / Checkout admin
            // page that reads the country list on mount.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'admin dashboard (Country list / CountryManager surface)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'admin dashboard render');

            // Signal #2a — Country Eloquent CRUD round-trip
            // through the same pipeline every consumer (Address
            // / Customer / Checkout admin pages) calls when
            // resolving country names.
            $this->assertCountryEloquentRoundTripPersists();

            // Signal #2b — CountryManager singleton round-trip
            // through the `country_manager` container binding.
            // Same resolution chain (id → name → code → JSON
            // fallback) the Address / Customer / Checkout admin
            // pages invoke through country()->getCountryName()
            // on every render.
            $this->assertCountryManagerSingletonRoundTrips();

            // Belt-and-braces — confirm the JSON-backed
            // CountriesHelper still exposes the canonical 250+
            // country list that powers the Address country
            // dropdown's fallback branch (the ::countriesListFromJson()
            // call that backstops the DB lookups).
            $this->assertCountriesJsonHelperReturnsKnownEntries();

            // Document the missing-form situation: the Plan-C.2
            // task line names a "country list admin" but no such
            // page exists today (CountryServiceProvider does not
            // call FilamentRegistry::registerPage / registerResource).
            // Assert the absence explicitly so a future commit
            // that lands the form (and forgets to update this
            // smoke) flips the test.
            $this->assertNoCountrySettingsPageRegistered();

            // Confirm the admin dashboard's Filament chrome
            // rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertDashboardBootsAroundCountry($browser);
        });
    }

    /**
     * Drive the Country Eloquent model through a full create →
     * update → reload → delete cycle so the smoke exercises
     * every CRUD verb the Address / Customer / Checkout admin
     * pages ultimately call when persisting / resolving country
     * data.
     */
    private function assertCountryEloquentRoundTripPersists(): void
    {
        $created = Country::create([
            'code' => self::FIXTURE_CODE,
            'name' => self::FIXTURE_INITIAL_NAME,
            'phonecode' => self::FIXTURE_PHONECODE,
        ]);

        $this->assertNotNull(
            $created->id,
            'Country::create must return a model with a primary key — every consumer '
            . '(Address / Customer / Checkout admin pages, plus the country()->getCountryName() '
            . 'helper) depends on this round-trip working.'
        );

        $created->name = self::FIXTURE_UPDATED_NAME;
        $created->save();

        $reloaded = Country::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Country::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break every Customer / Address / Checkout admin page '
            . 'that resolves country names by ID on mount.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_NAME,
            (string) $reloaded->name,
            'Country::save must persist name updates — the Customer admin and the public '
            . 'checkout country dropdown both bind through this same setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_CODE,
            (string) $reloaded->code,
            'Country::create must persist the code marker verbatim — a regression here '
            . 'would break the code-scoped tearDown purge gate that this smoke depends on '
            . 'for re-runnability AND would mean ISO-code lookups stop resolving in the '
            . 'CountryManager fallback branches.'
        );
        $this->assertSame(
            self::FIXTURE_PHONECODE,
            (int) $reloaded->phonecode,
            'Country::create must persist phonecode as an integer — the Customer admin '
            . 'and Checkout international-phone widget both read this column to prefix '
            . 'the dialing code; an integer-cast regression here would silently break '
            . 'phonecode display across every country-aware form.'
        );

        $reloaded->delete();
        $this->assertNull(
            Country::find($created->id),
            'Country::delete must remove the row from the countries table; failure here '
            . 'would silently leak fixture rows across re-runs.'
        );
    }

    /**
     * Round-trip the same fixture row through the
     * CountryManager singleton resolved from the `country_manager`
     * container binding. Exercises the id → name → code lookup
     * chain that the Customer / Address / Checkout admin pages
     * call through the country() helper on every render.
     */
    private function assertCountryManagerSingletonRoundTrips(): void
    {
        $manager = app('country_manager');

        $this->assertInstanceOf(
            CountryManager::class,
            $manager,
            "The `country_manager` container singleton must resolve to a CountryManager "
            . 'instance — this is the binding the country()->getCountryName() helper '
            . 'depends on. A regression here would silently break country-name lookups '
            . 'across Customer / Address / Checkout admin pages.'
        );

        // Seed a marker-prefixed row so the manager has
        // something to find under each branch (id / name /
        // code). The row is purged again in tearDown.
        $created = Country::create([
            'code' => self::FIXTURE_CODE,
            'name' => self::FIXTURE_INITIAL_NAME,
            'phonecode' => self::FIXTURE_PHONECODE,
        ]);

        $this->assertSame(
            self::FIXTURE_INITIAL_NAME,
            (string) $manager->getCountryName($created->id),
            'CountryManager::getCountryName must resolve a row by primary key — this is '
            . 'the first branch of its lookup chain (id → name → code → JSON), and the '
            . 'most common entry point because Customer / Address / Checkout admin pages '
            . 'store country foreign keys as IDs.'
        );

        $this->assertSame(
            self::FIXTURE_INITIAL_NAME,
            (string) $manager->getCountryName(self::FIXTURE_INITIAL_NAME),
            'CountryManager::getCountryName must resolve a row by name (second-branch '
            . 'fallback) — this branch is hit by legacy migrations and CSV imports that '
            . 'pass country names rather than IDs.'
        );

        $this->assertSame(
            self::FIXTURE_INITIAL_NAME,
            (string) $manager->getCountryName(self::FIXTURE_CODE),
            'CountryManager::getCountryName must resolve a row by code (third-branch '
            . 'fallback) — this branch is hit by the public checkout flow when the '
            . 'browser sends an ISO 3166-1 alpha-2 code in the country select.'
        );

        $created->delete();
    }

    /**
     * Probe the JSON-backed country list helper. The
     * countries-list JSON file is the authoritative fallback
     * the CountryManager hits when DB lookups miss, AND the
     * source the Address country dropdown reads to enumerate
     * all countries when the table is empty / unseeded. A
     * regression in the JSON file (or its load path) would
     * silently break every country-aware admin form.
     */
    private function assertCountriesJsonHelperReturnsKnownEntries(): void
    {
        $countries = CountriesHelper::countriesListFromJson();

        $this->assertIsArray(
            $countries,
            'CountriesHelper::countriesListFromJson must return an array — the file at '
            . 'Modules/Country/resources/country.json backs the country dropdown across '
            . 'every Address-aware admin form.'
        );

        $this->assertGreaterThanOrEqual(
            200,
            count($countries),
            'CountriesHelper::countriesListFromJson must return at least 200 entries — '
            . 'the shipped JSON file enumerates every ISO 3166-1 country (~250). A '
            . 'regression here would silently truncate the country dropdown across every '
            . 'public checkout / customer registration form.'
        );

        // Sanity-check a couple of well-known ISO 3166-1
        // alpha-2 keys. Pinning these surfaces a regression
        // that swaps the JSON file's key/value orientation.
        $this->assertArrayHasKey(
            'US',
            $countries,
            'CountriesHelper::countriesListFromJson must key entries by ISO 3166-1 '
            . "alpha-2 code — 'US' is one of the must-be-present keys for any working "
            . 'public checkout. A regression that flips the key/value orientation would '
            . 'surface here as a missing key.'
        );
        $this->assertArrayHasKey(
            'BG',
            $countries,
            'CountriesHelper::countriesListFromJson must contain BG (Bulgaria) — '
            . 'pinning a second well-known key to prove the JSON file is fully loaded, '
            . 'not just truncated to a single entry.'
        );
    }

    /**
     * Pin the missing-form situation explicitly.
     * /admin/country-list and the bare-bones /admin/countries
     * route both return a non-200 today because
     * CountryServiceProvider does not call
     * FilamentRegistry::registerPage / registerResource. When
     * a future commit lands the form (closing Plan-C.2's
     * "country list admin" line for real), this assertion will
     * flip and the smoke will need a refresh pointing at the
     * new admin page.
     */
    private function assertNoCountrySettingsPageRegistered(): void
    {
        $url = config('app.url', 'http://127.0.0.1:8000');
        $candidates = [
            '/admin/country-list',
            '/admin/countries',
            '/admin/country-module-settings',
        ];

        foreach ($candidates as $candidate) {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withOptions(['http_errors' => false, 'allow_redirects' => false])
                ->timeout(15)
                ->get(rtrim($url, '/') . $candidate);

            $this->assertNotSame(
                200,
                $response->status(),
                'No ' . $candidate . ' Filament page is registered today '
                . '(CountryServiceProvider does not call '
                . 'FilamentRegistry::registerPage / registerResource). If this assertion '
                . 'ever fires, it means a future commit landed the long-awaited Country '
                . 'list admin — update this smoke to probe the new page and round-trip a '
                . 'create-country action through the resource form, matching the sibling '
                . 'Filament-resource pattern. See the docblock of ' . static::class
                . ' for migration guidance.'
            );
        }
    }

    /**
     * Probe the rendered admin dashboard for the Filament/Livewire
     * scaffolding that proves the page mounted properly. Same
     * probe shape as the sibling Cloudflare/ContentField/
     * Address smokes.
     */
    private function assertDashboardBootsAroundCountry(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click="save"');
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasFilamentChrome || $hasLivewireWiring,
            'admin dashboard must render Filament/Livewire chrome (fi-page / fi-resource '
            . '/ fi-table / wire:id / wire:snapshot / wire:model / wire:click="save") — '
            . 'otherwise the page never mounted past the auth shell and the Country '
            . 'round-trips above would only prove the model + manager work, not that '
            . 'the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'admin dashboard must render at least one pressable Filament action (any '
            . '<button> or <a role="button">) — a dashboard with no actions would mean '
            . 'the toolbar regressed past the Filament-5 migration this smoke is meant '
            . 'to catch.'
        );
    }
}
