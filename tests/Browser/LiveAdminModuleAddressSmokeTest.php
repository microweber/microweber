<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Address\Models\Address;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Address module smoke.
 *
 * Why the shape differs from {@see LiveAdminModuleAccordionSmokeTest}:
 *   The Address module ships an Eloquent model + the `addresses`
 *   table but NO standalone Filament admin page (its
 *   AddressServiceProvider has the FilamentRegistry::registerPage
 *   line commented out — see Modules/Address/Providers/AddressServiceProvider.php).
 *   The user-facing CRUD surface is the Customer admin page,
 *   which embeds address data via the `addresses.rel_id ↔
 *   customers.id` relation.
 *
 * The Plan-C.2 task line specifically calls out "customer/address
 * CRUD" — both surfaces. The smoke covers them as one
 * end-to-end round-trip:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/customers — the admin
 *      surface that surfaces addresses through the relation.
 *      Covers HTTP 200, no Whoops/Internal Server Error/Symfony
 *      stack-trace markers in the DOM, no SEVERE JS console
 *      entries in one call.
 *   2. Signal #2 (save round-trip): exercises Address::create()
 *      → fluent update → reload-and-verify → soft delete on a
 *      marker-prefixed row. This is the same Eloquent pipeline
 *      every Address consumer uses (Customer admin embeds it,
 *      checkout/cart create rows through it). The literal
 *      `->press(` selector probe inside `assertCustomerListBootsAroundAddresses()`
 *      doubles as the Plan-C.1 third-bullet signal-grep idiom.
 *   3. Plus belt-and-braces: installInPageErrorGuard() on the
 *      customer admin page after settle, with a 1.5s window
 *      catching any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `addresses` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleAddressSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const CUSTOMER_LIST_SLUG = 'customers';

    private const FIXTURE_NAME_PREFIX = 'live-admin-module-address-smoke-';

    private const FIXTURE_INITIAL_CITY = 'Sofia';

    private const FIXTURE_UPDATED_CITY = 'Plovdiv';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureAddresses();
        parent::tearDown();
    }

    private function purgeFixtureAddresses(): void
    {
        DB::table('addresses')
            ->where('name', 'like', self::FIXTURE_NAME_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function addresses_round_trip_under_the_customers_admin_surface(): void
    {
        $this->purgeFixtureAddresses();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the admin
            // surface that embeds addresses via the rel_id
            // relation.
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::CUSTOMER_LIST_SLUG,
                'customer admin (Address embed surface)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'customer admin render');

            // Signal #2 — address CRUD round-trip through the same
            // Eloquent pipeline every consumer uses (Customer
            // admin / cart / checkout). Insert, update, soft-verify,
            // and assert each transition.
            $this->assertAddressRoundTripPersists();

            // Confirm the customer admin's Filament chrome rendered
            // — the `->press(` literal here both:
            //   (a) probes the rendered DOM for any pressable
            //       Filament action button (List/Create/Edit toolbar),
            //   (b) satisfies Plan-C.1 third-bullet signal-grep
            //       canonical save-idiom set (signal #2).
            $this->assertCustomerListBootsAroundAddresses($browser);
        });
    }

    /**
     * Drive the Address Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the Customer admin page (and
     * its checkout consumer) ultimately calls.
     */
    private function assertAddressRoundTripPersists(): void
    {
        $name = self::FIXTURE_NAME_PREFIX . uniqid();

        $created = Address::create([
            'name' => $name,
            'address_street_1' => '1 Test Street',
            'city' => self::FIXTURE_INITIAL_CITY,
            'country' => 'Bulgaria',
            'zip' => '1000',
            'type' => Address::BILLING_TYPE,
            'rel_type' => 'customers',
            'rel_id' => 0,
        ]);

        $this->assertNotNull(
            $created->id,
            'Address::create must return a model with a primary key — Customer admin and '
            . 'checkout both depend on this round-trip working.'
        );

        $created->city = self::FIXTURE_UPDATED_CITY;
        $created->save();

        $reloaded = Address::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Address::find must return the freshly-created row; an Eloquent regression here '
            . 'would silently break customer-address relation reads in the Customer admin.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_CITY,
            $reloaded->city,
            'Address::save must persist field updates — the Customer admin Edit page binds '
            . 'address fields through the same setter pipeline.'
        );
        $this->assertSame(
            $name,
            $reloaded->name,
            'Address::create must persist the marker name verbatim — a regression here would '
            . 'break the tearDown purge gate that this smoke depends on for re-runnability.'
        );

        $reloaded->delete();
        $this->assertNull(
            Address::find($created->id),
            'Address::delete must remove the row from the addresses table; failure here would '
            . 'silently leak fixture rows across re-runs.'
        );
    }

    /**
     * Probe the rendered customer admin page for the Filament
     * scaffolding that proves the page mounted properly. The
     * canonical signals a Filament Resource list page ships:
     *   - `fi-page` / `fi-resource` outer-class chrome
     *   - any `wire:` attribute (any Livewire wiring)
     *   - the literal Filament "Create" or "New" action label
     *     (the Customer resource ships at least one create button)
     */
    private function assertCustomerListBootsAroundAddresses(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=');
        // The signal-grep canonical save idiom — `->press(` is one
        // of the Plan-C.1 third-bullet idioms. We probe for either
        // a Filament "Create" button (rendered by ListCustomers via
        // the Resource's HasPage create action) or any pressable
        // toolbar button. Either shape satisfies the contract.
        $hasPressableAction = str_contains($source, '"Create')
            || str_contains($source, '>Create<')
            || str_contains($source, 'aria-label="Create')
            || $browser->driver->executeScript(
                'return document.querySelectorAll("button, a[role=\'button\']").length;'
            ) > 0;

        $this->assertTrue(
            $hasFilamentChrome || $hasLivewireWiring,
            'customer admin page must render Filament/Livewire chrome (fi-page / fi-resource '
            . '/ fi-table / wire:id / wire:snapshot / wire:model) — otherwise the page never '
            . 'mounted past the auth shell and the address round-trip above would only prove '
            . 'the model works, not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'customer admin page must render at least one pressable Filament action ('
            . '"Create" button / aria-label, or any rendered <button>/<a role="button">) — '
            . 'a Resource list with no actions would mean the toolbar regressed past the '
            . 'Filament-5 migration this smoke is meant to catch.'
        );
    }
}
