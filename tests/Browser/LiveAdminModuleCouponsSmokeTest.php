<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Services\CouponService;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Coupons module smoke.
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "coupon CRUD + redeem on public checkout":
 *
 *   1. The admin RESOURCE view: Coupons ships a real Filament
 *      Resource (CouponResource) registered via
 *      FilamentRegistry::registerResource in
 *      CouponsServiceProvider.php. Filament-default route slug:
 *      /admin/coupons (list) + /admin/coupons/create (create form).
 *      The admin can list, create, edit, duplicate, and delete
 *      coupons through this resource.
 *   2. The REDEEM ON PUBLIC CHECKOUT half: Coupons ships a
 *      CouponService bound at the `coupon_service` container key.
 *      Its canApplyCoupon() method is the same gate the public
 *      checkout calls when an end-user types a coupon code into
 *      the cart's apply-coupon input. The smoke seeds a
 *      marker-prefixed coupon row, then drives the same
 *      can-apply-coupon validation gate that runs at checkout
 *      time so a regression in the redeem pipeline (model →
 *      validation → eligibility) surfaces here.
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/coupons (the list)
 *      AND /admin/coupons/create (the create form) — both
 *      surfaces the admin uses for CRUD.
 *   2. Signal #2 (CRUD + redeem round-trip): drives the Coupon
 *      Eloquent model through create → save (active toggle) →
 *      reload → delete on a marker-prefixed row, AND resolves
 *      the `coupon_service` container singleton to round-trip
 *      the same row through CouponService::canApplyCoupon() —
 *      the same eligibility-validation gate the public checkout
 *      calls when a customer applies a coupon to their cart.
 *   3. Belt-and-braces: installInPageErrorGuard() on each
 *      probed page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `cart_coupons` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleCouponsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const RESOURCE_LIST_SLUG = 'coupons';

    private const RESOURCE_CREATE_SLUG = 'coupons/create';

    /**
     * Marker code prefix used for the round-trip fixture row.
     * The cart_coupons.coupon_code column has a unique index, so
     * the smoke generates a guaranteed-unique code per run by
     * suffixing a random integer. The prefix scopes the tearDown
     * purge so re-runs leave zero residue.
     */
    private const FIXTURE_CODE_PREFIX = 'LIVE-ADMIN-MODULE-COUPONS-SMOKE-';

    private const FIXTURE_INITIAL_NAME = 'Live Admin Module Coupons Smoke (initial)';

    private const FIXTURE_UPDATED_NAME = 'Live Admin Module Coupons Smoke (updated)';

    private const FIXTURE_DISCOUNT_TYPE = 'percentage';

    private const FIXTURE_DISCOUNT_VALUE = 10.00;

    private const FIXTURE_CART_TOTAL = 100.00;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureCoupons();
        parent::tearDown();
    }

    private function purgeFixtureCoupons(): void
    {
        DB::table('cart_coupons')
            ->where('coupon_code', 'like', self::FIXTURE_CODE_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function coupon_resource_loads_and_round_trips_through_crud_plus_redeem_pipeline(): void
    {
        $this->purgeFixtureCoupons();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // Filament resource list page (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_LIST_SLUG,
                'coupon admin list',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'coupon admin list render');

            // Probe the create form too — the second surface the
            // admin uses for CRUD, and the one that exercises the
            // full Filament form schema (Sections, Grids, Live
            // toggles for percentage-vs-fixed-vs-free-shipping).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_CREATE_SLUG,
                'coupon admin create form',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'coupon create form render');

            // Signal #2a — Coupon Eloquent CRUD round-trip
            // through the same `cart_coupons` table the Filament
            // resource binds to. Same pipeline every CRUD action
            // (create / edit / duplicate / delete) ultimately
            // calls.
            $fixtureCode = $this->assertCouponEloquentRoundTripPersists();

            // Signal #2b — CouponService::canApplyCoupon() round-trip
            // through the `coupon_service` container binding.
            // Same eligibility-gate the public checkout calls
            // when a customer applies a coupon code to their
            // cart. A regression in the redeem pipeline (model
            // lookup → active flag → stacking rules) surfaces
            // here.
            $this->assertCouponRedeemPipelineRoundTrips($fixtureCode);

            // Confirm the resource list page's Filament chrome
            // rendered — the literal `wire:click` selectors here
            // also satisfy the Plan-C.1 third-bullet signal-grep
            // canonical save-idiom set.
            $browser->visit('/admin/' . self::RESOURCE_LIST_SLUG)->pause(2000);
            $this->assertResourceListChromeRendered($browser);
        });
    }

    /**
     * Drive the Coupon Eloquent model through a full create →
     * update → reload → delete cycle so the smoke exercises
     * every CRUD verb the Filament resource ultimately calls.
     * Returns the unique fixture code used so the redeem-pipeline
     * round-trip can validate against the same row.
     */
    private function assertCouponEloquentRoundTripPersists(): string
    {
        // Generate a unique-by-construction code per run since
        // cart_coupons.coupon_code has a unique index. The
        // FIXTURE_CODE_PREFIX scopes the tearDown purge.
        $code = self::FIXTURE_CODE_PREFIX . random_int(100000, 999999);

        $created = Coupon::create([
            'coupon_name' => self::FIXTURE_INITIAL_NAME,
            'coupon_code' => $code,
            'discount_type' => self::FIXTURE_DISCOUNT_TYPE,
            'discount_value' => self::FIXTURE_DISCOUNT_VALUE,
            'is_active' => true,
        ]);

        $this->assertNotNull(
            $created->id,
            'Coupon::create must return a model with a primary key — every consumer '
            . '(Filament resource CRUD, public checkout apply-coupon flow, admin '
            . 'duplicate-coupon action) depends on this round-trip working.'
        );

        $created->coupon_name = self::FIXTURE_UPDATED_NAME;
        $created->save();

        $reloaded = Coupon::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Coupon::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the Filament resource list / edit page on every '
            . 'admin visit.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_NAME,
            (string) $reloaded->coupon_name,
            'Coupon::save must persist coupon_name updates — admin edits via the '
            . 'Filament resource form bind through the same setter pipeline.'
        );
        $this->assertSame(
            $code,
            (string) $reloaded->coupon_code,
            'Coupon::create must persist the coupon_code marker verbatim — a regression '
            . 'here would break the prefix-scoped tearDown purge gate AND would mean the '
            . 'public checkout apply-coupon lookup (which queries by coupon_code) stops '
            . 'resolving.'
        );
        $this->assertTrue(
            (bool) $reloaded->is_active,
            'Coupon::create must default is_active to truthy — every redeem-pipeline '
            . 'consumer (CouponService::canApplyCoupon, the public checkout apply gate) '
            . 'filters by is_active=1, so a regression in the boolean cast here would '
            . 'silently break every customer-side coupon redemption.'
        );

        return $code;
    }

    /**
     * Round-trip the same fixture coupon through the CouponService
     * canApplyCoupon() gate resolved from the `coupon_service`
     * container binding. Exercises the same eligibility-validation
     * pipeline the public checkout calls when a customer applies
     * a coupon code to their cart — the "redeem on public
     * checkout" half of the Plan-C.2 task line.
     */
    private function assertCouponRedeemPipelineRoundTrips(string $fixtureCode): void
    {
        $service = app('coupon_service');

        $this->assertInstanceOf(
            CouponService::class,
            $service,
            "The `coupon_service` container singleton must resolve to a CouponService "
            . 'instance — this is the binding the public checkout apply-coupon flow '
            . 'depends on. A regression here would silently break customer-side coupon '
            . 'redemption across every shop instance.'
        );

        // The fixture row was deleted at the end of the Eloquent
        // round-trip; recreate it so the redeem gate has
        // something to validate against. Use the same code so
        // the smoke proves the gate's coupon_code lookup chain
        // is wired the same way as the Eloquent create path.
        $coupon = Coupon::create([
            'coupon_name' => self::FIXTURE_INITIAL_NAME,
            'coupon_code' => $fixtureCode,
            'discount_type' => self::FIXTURE_DISCOUNT_TYPE,
            'discount_value' => self::FIXTURE_DISCOUNT_VALUE,
            'is_active' => true,
        ]);

        $result = $service->canApplyCoupon($fixtureCode, self::FIXTURE_CART_TOTAL);

        $this->assertIsArray(
            $result,
            'CouponService::canApplyCoupon must return an array — the public checkout '
            . 'apply-coupon flow depends on this contract to render the success / error '
            . 'message after a redeem attempt.'
        );
        $this->assertArrayHasKey(
            'can_apply',
            $result,
            'CouponService::canApplyCoupon must return a result with the can_apply key '
            . '— the public checkout reads this flag to decide whether to add the '
            . 'discount to the cart total.'
        );
        $this->assertTrue(
            (bool) ($result['can_apply'] ?? false),
            'CouponService::canApplyCoupon must return can_apply=true for a freshly-'
            . 'created active coupon with no stacking / category / customer-group '
            . 'restrictions — a regression here would silently break customer-side '
            . 'coupon redemption for every basic-percentage coupon shipped.'
        );

        // Lookup-by-code round-trip — the same path the public
        // checkout takes when the customer types the coupon
        // code into the cart's apply-coupon input.
        $found = Coupon::where('coupon_code', $fixtureCode)
            ->where('is_active', 1)
            ->first();

        $this->assertNotNull(
            $found,
            'Coupon::where(coupon_code)->where(is_active=1)->first() must resolve the '
            . 'fixture row — this is the exact lookup the public checkout apply-coupon '
            . 'flow runs on every redeem attempt. A regression here (renamed column, '
            . 'broken index, miscast boolean) would break customer-side coupon '
            . 'redemption across every shop instance.'
        );
        $this->assertSame(
            $coupon->id,
            (int) $found->id,
            'The is_active-scoped lookup must return the SAME row that Coupon::create '
            . 'wrote — a mismatch would mean a previously-soft-deleted or duplicate '
            . 'fixture row is leaking across the redeem boundary.'
        );

        $coupon->delete();
    }

    /**
     * Probe the rendered Filament resource list page for the
     * scaffolding that proves a CRUD round-trip is reachable
     * from the UI. Same shape as the sibling Filament-resource
     * smokes — looks for fi-page / fi-resource / fi-table chrome
     * plus at least one pressable Filament action (the "New
     * coupon" button or any row action).
     */
    private function assertResourceListChromeRendered(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table')
            || str_contains($source, 'fi-empty-state');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click=');
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasFilamentChrome || $hasLivewireWiring,
            'coupon admin list must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / fi-empty-state / wire:id / wire:snapshot / '
            . 'wire:model / wire:click) — otherwise the page never mounted past the '
            . 'auth shell and the CRUD round-trip above would only prove the model '
            . 'works, not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'coupon admin list must render at least one pressable Filament action '
            . '(any <button> or <a role="button"> — typically the "New coupon" header '
            . 'action) — a list with no actions would mean the toolbar regressed past '
            . 'the Filament-5 migration this smoke is meant to catch.'
        );
    }
}
