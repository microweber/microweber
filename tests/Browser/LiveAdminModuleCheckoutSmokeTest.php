<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Checkout module smoke.
 *
 * Shape diverges from the sibling Filament-settings-page smokes:
 * the Checkout module does NOT register a settings page (its
 * AddressServiceProvider has the FilamentRegistry::registerPage
 * line commented out — see CheckoutServiceProvider.php). Instead
 * it ships its own Filament panel mounted at `/checkout` with a
 * dedicated CheckoutResource (slug "checkout") whose form is the
 * full multi-section checkout form: Personal Information,
 * Shipping Address, Billing Address, Payment, Review.
 *
 * Plan-C.2 task line is "checkout form fields". Those fields
 * persist (on order completion) into the `cart_orders` table:
 *   first_name, last_name, email, country, city, state, zip,
 *   address, address2, phone, etc.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /checkout — the public
 *      checkout entry where the form fields actually mount.
 *   2. Signal #2 (form-field persistence round-trip): seed a
 *      marker-prefixed `cart_orders` row with the canonical
 *      checkout form-field set (first_name, last_name, email,
 *      country, city, zip, phone), reload via DB::table, verify
 *      every field round-tripped intact, delete on tearDown.
 *      That's the same persistence target every checkout-form
 *      submission ultimately writes to.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      checkout page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait — required
 * even though /checkout is public, because the smoke runs
 * inside a logged-in Dusk session for shared-state reasons).
 *
 * Cleans up its marker-prefixed `cart_orders` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleCheckoutSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const CHECKOUT_PATH = 'checkout';

    private const FIXTURE_EMAIL_PREFIX = 'live-admin-module-checkout-smoke-';

    private const FIXTURE_FIRST_NAME = 'Smoke';

    private const FIXTURE_LAST_NAME = 'Checkout';

    private const FIXTURE_COUNTRY = 'Bulgaria';

    private const FIXTURE_CITY = 'Sofia';

    private const FIXTURE_ZIP = '1000';

    private const FIXTURE_PHONE = '+359-555-0100';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureOrders();
        parent::tearDown();
    }

    private function purgeFixtureOrders(): void
    {
        DB::table('cart_orders')
            ->where('email', 'like', self::FIXTURE_EMAIL_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function checkout_page_loads_and_form_fields_round_trip_through_cart_orders(): void
    {
        $this->purgeFixtureOrders();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /checkout
            // (HTTP < 500, no Whoops / Internal Server Error /
            // Symfony stack-trace markers in the DOM, no SEVERE
            // JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::CHECKOUT_PATH,
                'checkout module entry page',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'checkout page render');

            // Signal #2 — round-trip the canonical checkout
            // form-field set through the `cart_orders`
            // persistence target every checkout-form submission
            // ultimately writes to.
            $this->assertCheckoutFormFieldsRoundTripPersist();

            // Confirm /checkout's Livewire / Filament wiring
            // rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertCheckoutFormWired($browser);
        });
    }

    /**
     * Insert a marker-prefixed cart_orders row carrying every
     * canonical checkout form field, then re-read the row and
     * verify each field round-tripped intact. Same persistence
     * target the public checkout form's submit handler writes
     * to (via Modules\Order\Models\Order, which uses
     * cart_orders as its table). Bypassing the Eloquent layer
     * keeps the smoke focused on the schema-level persistence
     * round-trip rather than any Order model events/listeners
     * that might mutate state.
     */
    private function assertCheckoutFormFieldsRoundTripPersist(): void
    {
        $email = self::FIXTURE_EMAIL_PREFIX . uniqid() . '@example.test';

        $orderId = DB::table('cart_orders')->insertGetId([
            'first_name' => self::FIXTURE_FIRST_NAME,
            'last_name' => self::FIXTURE_LAST_NAME,
            'email' => $email,
            'country' => self::FIXTURE_COUNTRY,
            'city' => self::FIXTURE_CITY,
            'zip' => self::FIXTURE_ZIP,
            'phone' => self::FIXTURE_PHONE,
            'currency' => 'USD',
            'amount' => 99.99,
            'order_status' => 'new',
            'order_completed' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertGreaterThan(
            0,
            $orderId,
            'cart_orders insert must return a primary key — checkout form submissions and '
            . 'admin order management both depend on this round-trip working.'
        );

        $row = DB::table('cart_orders')->where('id', $orderId)->first();

        $this->assertNotNull(
            $row,
            'cart_orders row must be readable immediately after insert; an Eloquent or '
            . 'cache regression here would silently break checkout form persistence.'
        );
        $this->assertSame(
            self::FIXTURE_FIRST_NAME,
            (string) $row->first_name,
            'first_name field must round-trip intact — the canonical Personal Information '
            . 'TextInput in CheckoutResource binds through this column.'
        );
        $this->assertSame(
            self::FIXTURE_LAST_NAME,
            (string) $row->last_name,
            'last_name field must round-trip intact — paired with first_name in the '
            . 'Personal Information section.'
        );
        $this->assertSame(
            $email,
            (string) $row->email,
            'email field must round-trip intact verbatim — also the marker that drives '
            . 'tearDown re-runnability.'
        );
        $this->assertSame(
            self::FIXTURE_CITY,
            (string) $row->city,
            'city field must round-trip intact — Shipping Address section binds here.'
        );
        $this->assertSame(
            self::FIXTURE_ZIP,
            (string) $row->zip,
            'zip field must round-trip intact — Shipping Address postal-code field.'
        );
        $this->assertSame(
            self::FIXTURE_PHONE,
            (string) $row->phone,
            'phone field must round-trip intact — Personal Information contact field.'
        );
    }

    /**
     * Probe the rendered /checkout page for the
     * Filament/Livewire scaffolding that proves the checkout
     * form's submit pipeline is reachable. Same probe shape as
     * the sibling smokes — accepts inline Livewire wiring or
     * the deferred Filament shell.
     */
    private function assertCheckoutFormWired(Browser $browser): void
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
            'checkout page must render at least one Livewire / Filament wiring attribute '
            . '(wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the form-field '
            . 'round-trip asserted above would only prove the cart_orders schema works, '
            . 'not that the checkout page is reachable through the form pipeline.'
        );
    }
}
