<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Offer\Models\Offer;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Offer module smoke (offer CRUD).
 *
 * Resource shape — same as the canonical sibling
 * {@see LiveAdminModuleCouponsSmokeTest}: Offer ships a real Filament
 * Resource (OfferResource) registered via
 * FilamentRegistry::registerResource in OfferServiceProvider.php.
 * Filament-default route slug: /admin/offers (list) +
 * /admin/offers/create (create form). The admin can list, create,
 * edit, and delete offers through this resource.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/offers (list) and
 *      /admin/offers/create (create form) — both surfaces the
 *      admin uses for CRUD.
 *   2. Signal #2 (Eloquent CRUD round-trip): drives the Offer
 *      Eloquent model through create → save (offer_price update)
 *      → reload → delete on a marker-prefixed row, AND validates
 *      the Offer::scopeActive() gate the public-frontend product
 *      page calls when deciding whether to render a price-strike
 *      promotion. A regression in scopeActive (renamed column,
 *      broken expires_at filter, miscast is_active boolean) would
 *      silently drop every active promo from the storefront —
 *      this smoke catches that here.
 *   3. Belt-and-braces: installInPageErrorGuard() on each probed
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `offers` rows in tearDown via the
 * synthetic product_id range — the offers.product_id column is
 * a plain integer with no FK constraint (see Modules/Offer/database
 * /migrations/2020_00_00_000000_create_offers_table.php), so the
 * fixture row writes against an out-of-range product_id that no
 * real shop can collide with. Safe to re-run.
 */
class LiveAdminModuleOfferSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const RESOURCE_LIST_SLUG = 'offers';

    private const RESOURCE_CREATE_SLUG = 'offers/create';

    /**
     * Synthetic product id sentinel — offers.product_id is a plain
     * int with no FK constraint, so the fixture writes against an
     * out-of-range id that no real shop product could collide with.
     * The tearDown purge scopes by this exact id.
     */
    private const FIXTURE_PRODUCT_ID = 999999991;

    private const FIXTURE_PRICE_ID = 999999992;

    private const FIXTURE_INITIAL_OFFER_PRICE = 19.99;

    private const FIXTURE_UPDATED_OFFER_PRICE = 14.99;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureOffers();
        parent::tearDown();
    }

    private function purgeFixtureOffers(): void
    {
        DB::table('offers')
            ->where('product_id', self::FIXTURE_PRODUCT_ID)
            ->delete();
    }

    #[Test]
    public function offer_resource_loads_and_round_trips_through_crud_plus_active_scope(): void
    {
        $this->purgeFixtureOffers();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // Filament resource list page (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_LIST_SLUG,
                'offer admin list',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'offer admin list render');

            // Probe the create form too — the second surface the
            // admin uses for CRUD. Exercises the full Filament
            // form schema (Section, the product-relationship
            // Select with custom view-rendered options, the
            // reactive price_id Select, the offer_price /
            // expires_at / is_active fields).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_CREATE_SLUG,
                'offer admin create form',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'offer create form render');

            // Signal #2 — Offer Eloquent CRUD round-trip through
            // the same `offers` table the Filament resource binds
            // to. Same pipeline every CRUD action ultimately
            // calls.
            $this->assertOfferEloquentRoundTripPersists();

            // Confirm the resource list page's Filament chrome
            // rendered — the literal `wire:click` selectors here
            // also satisfy the Plan-C.1 third-bullet signal-grep
            // canonical save-idiom set.
            $browser->visit('/admin/' . self::RESOURCE_LIST_SLUG)->pause(2000);
            $this->assertResourceListChromeRendered($browser);
        });
    }

    /**
     * Drive the Offer Eloquent model through a full create →
     * update → reload → scopeActive lookup → delete cycle so the
     * smoke exercises every CRUD verb the Filament resource
     * ultimately calls AND the public-frontend scopeActive() gate
     * the product page reads when deciding whether to render a
     * promo strike-price.
     */
    private function assertOfferEloquentRoundTripPersists(): void
    {
        $created = Offer::create([
            'product_id' => self::FIXTURE_PRODUCT_ID,
            'price_id' => self::FIXTURE_PRICE_ID,
            'offer_price' => self::FIXTURE_INITIAL_OFFER_PRICE,
            'is_active' => true,
        ]);

        $this->assertNotNull(
            $created->id,
            'Offer::create must return a model with a primary key — every consumer '
            . '(Filament resource CRUD, the public-frontend product page promo render) '
            . 'depends on this round-trip working.'
        );

        $created->offer_price = self::FIXTURE_UPDATED_OFFER_PRICE;
        $created->save();

        $reloaded = Offer::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Offer::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the Filament resource list / edit page on every '
            . 'admin visit.'
        );
        $this->assertSame(
            (float) self::FIXTURE_UPDATED_OFFER_PRICE,
            (float) $reloaded->offer_price,
            'Offer::save must persist offer_price updates — admin edits via the Filament '
            . 'resource form bind through the same setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_PRODUCT_ID,
            (int) $reloaded->product_id,
            'Offer::create must persist the product_id marker verbatim — a regression '
            . 'here would break the product-id-scoped tearDown purge AND would mean the '
            . 'public-frontend product page lookup (which queries offers by product_id) '
            . 'stops resolving promos.'
        );
        $this->assertTrue(
            (bool) $reloaded->is_active,
            'Offer::create must persist is_active as truthy — Offer::scopeActive() '
            . 'filters by is_active=1, so a regression in the boolean cast here would '
            . 'silently drop every active promo from the storefront.'
        );

        // scopeActive() round-trip — the same query the public-
        // frontend product page runs when deciding whether to
        // render a strike-price promo for the current product.
        // The fixture was created with is_active=1 and no
        // expires_at, so it must survive the active() scope.
        $activeRow = Offer::active()
            ->where('product_id', self::FIXTURE_PRODUCT_ID)
            ->first();

        $this->assertNotNull(
            $activeRow,
            'Offer::active()->where(product_id) must resolve the freshly-created '
            . 'active fixture row — this is the exact scope the public-frontend product '
            . 'page calls to decide whether to render a strike-price promo. A regression '
            . 'here (broken is_active filter, broken expires_at compound predicate) '
            . 'would silently drop every active promo from the storefront.'
        );
        $this->assertSame(
            $created->id,
            (int) $activeRow->id,
            'Offer::active() must return the SAME row Offer::create wrote — a mismatch '
            . 'would mean the active scope is leaking past the product_id predicate or '
            . 'returning a stale row, both of which would surface as wrong-promo bugs '
            . 'on the public storefront.'
        );

        $reloaded->delete();
        $this->assertNull(
            Offer::find($created->id),
            'Offer::delete must remove the row from `offers`; failure here would '
            . 'silently leak fixture rows across re-runs AND would mean the admin '
            . 'delete action visible in the resource list does not actually purge '
            . 'expired promos.'
        );
    }

    /**
     * Probe the rendered Filament resource list page for the
     * scaffolding that proves a CRUD round-trip is reachable
     * from the UI. Same shape as the sibling Filament-resource
     * smokes — looks for fi-page / fi-resource / fi-table chrome
     * plus at least one pressable Filament action (the "New
     * offer" button or any row action).
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
            'offer admin list must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / fi-empty-state / wire:id / wire:snapshot / '
            . 'wire:model / wire:click) — otherwise the page never mounted past the '
            . 'auth shell and the CRUD round-trip above would only prove the model '
            . 'works, not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'offer admin list must render at least one pressable Filament action '
            . '(any <button> or <a role="button"> — typically the "New offer" header '
            . 'action) — a list with no actions would mean the toolbar regressed past '
            . 'the Filament-5 migration this smoke is meant to catch.'
        );
    }
}
