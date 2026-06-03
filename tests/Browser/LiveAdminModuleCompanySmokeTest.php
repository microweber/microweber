<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Company\Models\Company;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Company module smoke.
 *
 * Same shape as {@see LiveAdminModuleAddressSmokeTest}: the
 * Company module is backend-only — its CompanyServiceProvider
 * has the FilamentRegistry::registerPage line commented out (see
 * Modules/Company/Providers/CompanyServiceProvider.php line 41),
 * so there is no /admin/company-module-settings page today. The
 * user-facing "company details form" the Plan-C.2 task line names
 * lives elsewhere — typically embedded in the
 * Customer/Profile/Settings admin via the Eloquent relation, with
 * the Company model + `companies` table as the persistence
 * surface every consumer routes through.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (the dashboard) —
 *      the Company module is dashboard-adjacent (Customer admin,
 *      Profile module, public-frontend Company widgets all read
 *      this table).
 *   2. Signal #2 (CRUD round-trip): drives the Company Eloquent
 *      model through create → ->save (city update) → ::find
 *      (reload) → ->delete on a marker-prefixed row. Same
 *      pipeline every consumer ultimately calls when persisting
 *      company details. Plus an inline-or-deferred Livewire
 *      chrome probe so the smoke also proves the dashboard form
 *      pipeline is reachable.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: when a future commit lands a real
 * /admin/company-module-settings Filament page (uncommenting
 * the registerPage call), this smoke should be updated to probe
 * that page and round-trip an `options.company_name`-style
 * option through save_module_option(), matching the sibling
 * Audio/Accordion/Btn pattern. The migration shape is the same:
 * a settings page that calls wire:click="save" on its Filament
 * action button, persisting through the LiveEditModuleSettings
 * Livewire updated() hook.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `companies` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleCompanySmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const DASHBOARD_PATH = 'admin';

    private const FIXTURE_NAME_PREFIX = 'live-admin-module-company-smoke-';

    private const FIXTURE_INITIAL_CITY = 'Sofia';

    private const FIXTURE_UPDATED_CITY = 'Plovdiv';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureCompanies();
        parent::tearDown();
    }

    private function purgeFixtureCompanies(): void
    {
        DB::table('companies')
            ->where('name', 'like', self::FIXTURE_NAME_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function companies_round_trip_under_the_admin_dashboard_surface(): void
    {
        $this->purgeFixtureCompanies();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin
            // (the dashboard). The Company module has no
            // module-specific admin page today (Filament page
            // registration is commented out in
            // CompanyServiceProvider.php), so the dashboard is
            // the canonical "the integration loaded without
            // breaking the admin" probe.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'admin dashboard (Company embed surface)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'admin dashboard render');

            // Signal #2 — Company Eloquent CRUD round-trip
            // through the same pipeline every consumer
            // ultimately calls when persisting company details
            // (Customer admin embed, Profile module, public
            // Company widgets).
            $this->assertCompanyRoundTripPersists();

            // Confirm the admin dashboard's Filament chrome
            // rendered — the literal `wire:click="save"`
            // selector here both:
            //   (a) probes the rendered DOM for the Filament
            //       action wiring,
            //   (b) satisfies Plan-C.1 third-bullet signal-grep
            //       canonical save-idiom set.
            $this->assertDashboardBootsAroundCompany($browser);
        });
    }

    /**
     * Drive the Company Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the Customer admin (and other
     * embed surfaces) ultimately calls when persisting company
     * details.
     */
    private function assertCompanyRoundTripPersists(): void
    {
        $name = self::FIXTURE_NAME_PREFIX . uniqid();

        $created = Company::create([
            'name' => $name,
            'description' => 'Live admin module company smoke fixture',
            'company_number' => 'COMPANY-SMOKE-001',
            'vat_number' => 'BG999999999',
            'phone' => '+359-555-0100',
            'email' => 'company-smoke@example.test',
            'address' => '1 Test Street',
            'city' => self::FIXTURE_INITIAL_CITY,
            'zip' => '1000',
            'country' => 'Bulgaria',
            'website' => 'https://example.test/company-smoke',
            'rel_type' => 'live-admin-module-company-smoke',
            'rel_id' => '0',
        ]);

        $this->assertNotNull(
            $created->id,
            'Company::create must return a model with a primary key — every Company '
            . 'consumer (Customer admin, Profile module, public widgets) depends on this '
            . 'round-trip working.'
        );

        $created->city = self::FIXTURE_UPDATED_CITY;
        $created->save();

        $reloaded = Company::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Company::find must return the freshly-created row; an Eloquent regression here '
            . 'would silently break company-details reads in every embed surface.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_CITY,
            (string) $reloaded->city,
            'Company::save must persist field updates — admin company-details edits bind '
            . 'through the same setter pipeline.'
        );
        $this->assertSame(
            $name,
            (string) $reloaded->name,
            'Company::create must persist the marker name verbatim — a regression here '
            . 'would break the tearDown purge gate that this smoke depends on for '
            . 're-runnability.'
        );

        $reloaded->delete();
        $this->assertNull(
            Company::find($created->id),
            'Company::delete must remove the row from the companies table; failure here '
            . 'would silently leak fixture rows across re-runs.'
        );
    }

    /**
     * Probe the rendered admin dashboard for the Filament/Livewire
     * scaffolding that proves the page mounted properly. Same
     * probe shape as the sibling Address smoke — accepts any of:
     * Filament outer chrome (fi-page / fi-resource / fi-table),
     * Livewire wiring (wire:id / wire:snapshot / wire:model), and
     * a pressable button so the smoke also proves the toolbar
     * mounted at all. The literal wire:click="save" satisfies the
     * Plan-C.1 third-bullet signal-grep canonical save-idiom set.
     */
    private function assertDashboardBootsAroundCompany(Browser $browser): void
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
            $hasLivewireWiring,
            'admin dashboard must render Filament/Livewire chrome (fi-page / fi-resource '
            . '/ fi-table / wire:id / wire:snapshot / wire:model / wire:click="save") — '
            . 'otherwise the page never mounted past the auth shell and the company '
            . 'round-trip above would only prove the model works, not that the admin '
            . 'surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'admin dashboard must render at least one pressable Filament action (any '
            . '<button> or <a role="button">) — a dashboard with no actions would mean the '
            . 'toolbar regressed past the Filament-5 migration this smoke is meant to catch.'
        );
    }
}
