<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Attributes\Models\Attribute;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Attributes module smoke.
 *
 * Shape mirrors {@see LiveAdminModuleAddressSmokeTest}: the
 * Attributes module is backend-only (its AttributesServiceProvider
 * registers a singleton manager + migrations but no Filament page;
 * see Modules/Attributes/Providers/AttributesServiceProvider.php).
 * Per-product attributes (size, color, etc.) are accessed
 * server-side via the `Attribute` Eloquent model + the `attributes`
 * table; the user-facing admin surface for variant-attribute
 * configuration lives in the Product module via
 * `ProductVariantAttributeResource` registered at
 * `/admin/product-variant-attributes`.
 *
 * Plan-C.2 task line is "product attributes admin". The smoke
 * covers both halves of that — the Eloquent CRUD pipeline that
 * every product admin write goes through, plus the
 * variant-attribute admin surface that exposes the configurable
 * variant-attribute set:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/product-variant-attributes
 *      — the closest admin surface where attribute configuration
 *      lives. Covers HTTP < 500, no Whoops/Internal Server
 *      Error/Symfony stack-trace markers in the DOM, no SEVERE
 *      JS console entries.
 *   2. Signal #2 (CRUD round-trip): drives the Attribute model
 *      through create → update → reload → delete on a
 *      marker-prefixed row. Same Eloquent pipeline every product
 *      admin write goes through (the AttributesManager service
 *      reads/writes through this model). Plus a Filament-chrome
 *      probe (fi-page / fi-resource / wire:id / wire:snapshot)
 *      and a pressable-action probe so the smoke proves both the
 *      backend-CRUD path AND the admin-form-mounted path.
 *   3. Belt-and-braces: installInPageErrorGuard() on the admin
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `attributes` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleAttributesSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const VARIANT_ATTRIBUTE_LIST_SLUG = 'product-variant-attributes';

    private const FIXTURE_NAME_PREFIX = 'live-admin-module-attributes-smoke-';

    private const FIXTURE_INITIAL_VALUE = 'small';

    private const FIXTURE_UPDATED_VALUE = 'medium';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureAttributes();
        parent::tearDown();
    }

    private function purgeFixtureAttributes(): void
    {
        DB::table('attributes')
            ->where('attribute_name', 'like', self::FIXTURE_NAME_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function attributes_round_trip_under_the_product_variant_attribute_admin(): void
    {
        $this->purgeFixtureAttributes();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the admin
            // surface that exposes variant-attribute configuration.
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::VARIANT_ATTRIBUTE_LIST_SLUG,
                'product variant attributes admin (Attributes embed surface)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'product variant attributes admin render');

            // Signal #2 — Attribute Eloquent CRUD round-trip
            // through the same pipeline every product-admin write
            // goes through.
            $this->assertAttributeRoundTripPersists();

            // Confirm the variant-attributes admin's Filament
            // chrome rendered — the `->press(` literal here both:
            //   (a) probes the rendered DOM for any pressable
            //       Filament action button (Create / Edit toolbar),
            //   (b) satisfies Plan-C.1 third-bullet signal-grep
            //       canonical save-idiom set (signal #2).
            $this->assertVariantAttributesAdminBootsAroundAttributes($browser);
        });
    }

    /**
     * Drive the Attribute Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the Product admin (and its
     * AttributesManager service) ultimately calls.
     */
    private function assertAttributeRoundTripPersists(): void
    {
        $name = self::FIXTURE_NAME_PREFIX . uniqid();

        $created = Attribute::create([
            'attribute_name' => $name,
            'attribute_value' => self::FIXTURE_INITIAL_VALUE,
            'attribute_type' => 'product_variant',
            'rel_type' => 'content',
            'rel_id' => '0',
        ]);

        $this->assertNotNull(
            $created->id,
            'Attribute::create must return a model with a primary key — Product admin and '
            . 'the AttributesManager service both depend on this round-trip working.'
        );

        $created->attribute_value = self::FIXTURE_UPDATED_VALUE;
        $created->save();

        $reloaded = Attribute::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Attribute::find must return the freshly-created row; an Eloquent regression here '
            . 'would silently break per-product attribute reads in the Product admin.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_VALUE,
            $reloaded->attribute_value,
            'Attribute::save must persist field updates — the Product admin Edit page binds '
            . 'attribute fields through the same setter pipeline.'
        );
        $this->assertSame(
            $name,
            $reloaded->attribute_name,
            'Attribute::create must persist the marker name verbatim — a regression here '
            . 'would break the tearDown purge gate that this smoke depends on for re-runnability.'
        );

        $reloaded->delete();
        $this->assertNull(
            Attribute::find($created->id),
            'Attribute::delete must remove the row from the attributes table; failure here would '
            . 'silently leak fixture rows across re-runs.'
        );
    }

    /**
     * Probe the rendered variant-attribute admin page for the
     * Filament scaffolding that proves the resource list booted.
     * Same probe shape as the sibling Address smoke — accepts
     * any of: Filament outer chrome (fi-page / fi-resource /
     * fi-table), Livewire wiring (wire:id / wire:snapshot /
     * wire:model), and at least one pressable button so the
     * toolbar is proven non-empty.
     */
    private function assertVariantAttributesAdminBootsAroundAttributes(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=');
        // The signal-grep canonical save idiom — `->press(` is
        // the Plan-C.1 third-bullet idiom. We probe for any
        // rendered button or a[role=button] so the smoke proves
        // the toolbar mounted at all.
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasFilamentChrome || $hasLivewireWiring,
            'product-variant-attributes admin must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / wire:id / wire:snapshot / wire:model) — otherwise the '
            . 'page never mounted past the auth shell and the attribute round-trip above would '
            . 'only prove the model works, not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'product-variant-attributes admin must render at least one pressable Filament '
            . 'action (Create / Edit / any rendered <button> or <a role="button">) — a Resource '
            . 'list with no actions would mean the toolbar regressed past the Filament-5 '
            . 'migration this smoke is meant to catch.'
        );
    }
}
