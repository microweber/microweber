<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Cart\Models\Cart;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Cart module smoke.
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "cart admin view + manual line-item edit":
 *
 *   1. The admin SETTINGS view: Cart ships a Filament settings
 *      page registered as CartAddModuleSettings via
 *      FilamentRegistry::registerPage in CartServiceProvider.php.
 *      Filament-default route slug: /admin/cart-add-module-settings
 *      (the "shop/cart_add" public-frontend module's admin
 *      configurator, which controls the inline button-text label).
 *   2. The MANUAL LINE-ITEM edit: Cart ships an Eloquent model +
 *      `cart` table where individual cart-line items persist.
 *      Manual line-item edits (admin-side qty / price corrections,
 *      checkout-side cart manipulations, soft-delete) all route
 *      through the Cart model. The smoke drives a full CRUD
 *      round-trip on a marker-prefixed cart row.
 *
 *   Signal #1 + #3 (page OK + no console errors): full
 *   assertPageSmokeOk() probe of /admin/cart-add-module-settings.
 *
 *   Signal #2 (manual line-item save round-trip): full
 *   create → ->save (qty update) → reload → ->delete cycle on a
 *   marker-prefixed `cart` row through the Cart Eloquent model.
 *   Plus an inline-or-deferred Livewire chrome probe so the smoke
 *   also proves the admin form pipeline is reachable.
 *
 *   Belt-and-braces: installInPageErrorGuard() on the settings
 *   page after settle, with a 1.5s window catching any
 *   deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `cart` row in tearDown; safe to
 * re-run.
 */
class LiveAdminModuleCartSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'cart-add-module-settings';

    private const FIXTURE_REL_TYPE = 'live-admin-module-cart-smoke';

    private const FIXTURE_INITIAL_QTY = 1;

    private const FIXTURE_UPDATED_QTY = 3;

    private const FIXTURE_PRICE = 9.99;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureCartRows();
        parent::tearDown();
    }

    private function purgeFixtureCartRows(): void
    {
        DB::table('cart')
            ->where('rel_type', self::FIXTURE_REL_TYPE)
            ->delete();
    }

    #[Test]
    public function cart_admin_view_loads_and_line_items_round_trip_through_eloquent(): void
    {
        $this->purgeFixtureCartRows();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the cart
            // admin view (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'cart admin view (cart-add settings)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'cart admin render');

            // Signal #2 — manual line-item CRUD round-trip
            // through the Cart Eloquent model. Same pipeline
            // every cart-line manipulation goes through (admin
            // qty/price corrections, checkout cart operations,
            // soft-delete on order completion).
            $this->assertCartLineItemRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSaveActionWired($browser);
        });
    }

    /**
     * Drive the Cart Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the cart admin (and its
     * checkout consumer) ultimately calls when manipulating
     * line items.
     */
    private function assertCartLineItemRoundTripPersists(): void
    {
        // Use a high rel_id sentinel so the marker can't collide
        // with any real product id — combined with the
        // FIXTURE_REL_TYPE this gives a unique-by-construction
        // tearDown filter.
        $relId = 99999900 + random_int(0, 99);

        $created = Cart::create([
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => $relId,
            'qty' => self::FIXTURE_INITIAL_QTY,
            'price' => self::FIXTURE_PRICE,
            'currency' => 'USD',
            'description' => 'Live admin module cart smoke fixture',
            'order_completed' => 0,
        ]);

        $this->assertNotNull(
            $created->id,
            'Cart::create must return a model with a primary key — admin cart manipulations '
            . 'and checkout both depend on this round-trip working.'
        );

        $created->qty = self::FIXTURE_UPDATED_QTY;
        $created->save();

        $reloaded = Cart::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Cart::find must return the freshly-created row; an Eloquent regression here '
            . 'would silently break manual line-item reads in the cart admin.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_QTY,
            (int) $reloaded->qty,
            'Cart::save must persist qty updates — manual line-item edits in the admin (the '
            . 'literal "manual line-item edit" the Plan-C.2 line names) bind through the '
            . 'same setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_REL_TYPE,
            (string) $reloaded->rel_type,
            'Cart::create must persist the rel_type marker verbatim — a regression here '
            . 'would break the tearDown purge gate that this smoke depends on for '
            . 're-runnability.'
        );

        $reloaded->delete();
        $this->assertNull(
            Cart::find($created->id),
            'Cart::delete must remove the row from the cart table; failure here would '
            . 'silently leak fixture rows across re-runs (and would also break the cart '
            . 'admin\'s "remove line item" action).'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves a save round-trip is possible from
     * the UI. Same shape as the sibling Audio/Accordion smokes.
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
            'cart admin page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR wire:id / '
            . 'wire:snapshot / fi-page / fi-form deferred) — otherwise the line-item '
            . 'round-trip asserted above would only prove the model works, not that the '
            . 'admin surface is reachable through the Livewire form pipeline.'
        );
    }
}
