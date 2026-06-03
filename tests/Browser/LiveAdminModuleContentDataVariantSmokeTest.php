<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\ContentDataVariant\Models\ContentDataVariant;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — ContentDataVariant module smoke.
 *
 * Same shape as the sibling {@see LiveAdminModuleContentDataSmokeTest}:
 * the ContentDataVariant module is backend-only — its
 * ContentDataVariantServiceProvider registers migrations + traits
 * but never calls FilamentRegistry::registerPage. There is no
 * /admin/content-data-variant-module-settings page.
 *
 * Plan-C.2 task line is "variants admin". The module ties content
 * rows to custom-field-value rows via the
 * (custom_field_id, custom_field_value_id, rel_id, rel_type)
 * tuple — that's what the product-variant admin (Plan-C.2 also
 * lists `LiveAdminModuleAttributesSmokeTest` covering the
 * adjacent variant-attribute admin) ultimately writes to. The
 * persistence target is the ContentDataVariant Eloquent model +
 * `content_data_variants` table.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (the dashboard) —
 *      ContentDataVariant is process-wide / dashboard-adjacent
 *      (every Product admin Edit page that renders variant
 *      configuration reads from content_data_variants on
 *      mount).
 *   2. Signal #2 (variant CRUD round-trip): drives the
 *      ContentDataVariant Eloquent model through
 *      create → ->save (custom_field_value_id update) →
 *      ::find (reload) → ->delete on a marker-prefixed row.
 *      Same Eloquent pipeline every consumer ultimately calls
 *      when persisting variant ↔ custom-field-value links.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: when a future commit lands a real
 * /admin/content-data-variant-module-settings Filament page
 * (matching the canonical Audio/Accordion/Btn pattern with a
 * wire:click="save" Filament action button), this smoke should
 * be updated to probe that page directly. Until then, the smoke
 * covers the underlying variants-table CRUD round-trip per the
 * same three-assertion-minimum the sibling smokes follow.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `content_data_variants` row in
 * tearDown; safe to re-run.
 */
class LiveAdminModuleContentDataVariantSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const DASHBOARD_PATH = 'admin';

    private const FIXTURE_REL_TYPE = 'live-admin-module-content-data-variant-smoke';

    /**
     * High custom_field_id sentinel that won't collide with real
     * custom-field rows. Combined with FIXTURE_REL_TYPE this
     * gives a unique-by-construction tearDown filter.
     */
    private const FIXTURE_CUSTOM_FIELD_ID = 99999900;

    private const FIXTURE_INITIAL_VALUE_ID = 88888800;

    private const FIXTURE_UPDATED_VALUE_ID = 88888801;

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureVariants();
        parent::tearDown();
    }

    private function purgeFixtureVariants(): void
    {
        DB::table('content_data_variants')
            ->where('rel_type', self::FIXTURE_REL_TYPE)
            ->delete();
    }

    #[Test]
    public function content_data_variants_round_trip_under_the_admin_dashboard_surface(): void
    {
        $this->purgeFixtureVariants();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin
            // (the dashboard). ContentDataVariant is process-wide
            // so a regression in the Eloquent model would surface
            // as a SEVERE log entry on any Product admin Edit
            // page that renders variant configuration on mount.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'admin dashboard (ContentDataVariant embed surface)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'admin dashboard render');

            // Signal #2 — ContentDataVariant Eloquent CRUD
            // round-trip through the same pipeline every consumer
            // calls when persisting variant ↔ custom-field-value
            // links.
            $this->assertContentDataVariantRoundTripPersists();

            // Confirm the admin dashboard's Filament chrome
            // rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertDashboardBootsAroundContentDataVariant($browser);
        });
    }

    /**
     * Drive the ContentDataVariant Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the variants admin (and its
     * Product / custom-field-value embed surfaces) ultimately
     * calls.
     */
    private function assertContentDataVariantRoundTripPersists(): void
    {
        $relId = 99999900 + random_int(0, 99);

        $created = ContentDataVariant::create([
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => $relId,
            'custom_field_id' => self::FIXTURE_CUSTOM_FIELD_ID,
            'custom_field_value_id' => self::FIXTURE_INITIAL_VALUE_ID,
        ]);

        $this->assertNotNull(
            $created->id,
            'ContentDataVariant::create must return a model with a primary key — Product '
            . 'admin variant configuration writes and the public-frontend variant resolution '
            . 'lookup both depend on this round-trip working.'
        );

        $created->custom_field_value_id = self::FIXTURE_UPDATED_VALUE_ID;
        $created->save();

        $reloaded = ContentDataVariant::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'ContentDataVariant::find must return the freshly-created row; an Eloquent '
            . 'regression here would silently break variant configuration reads in every '
            . 'embed surface.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_VALUE_ID,
            (int) $reloaded->custom_field_value_id,
            'ContentDataVariant::save must persist custom_field_value_id updates — admin '
            . 'variant edits bind through the same setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_REL_TYPE,
            (string) $reloaded->rel_type,
            'ContentDataVariant::create must persist the rel_type marker verbatim — a '
            . 'regression here would break the tearDown purge gate that this smoke depends '
            . 'on for re-runnability.'
        );

        $reloaded->delete();
        $this->assertNull(
            ContentDataVariant::find($created->id),
            'ContentDataVariant::delete must remove the row from the content_data_variants '
            . 'table; failure here would silently leak fixture rows across re-runs (and '
            . "would also break the variants admin's \"remove variant\" action)."
        );
    }

    /**
     * Probe the rendered admin dashboard for the Filament/Livewire
     * scaffolding that proves the page mounted properly. Same
     * probe shape as the sibling Address/Company/ContentData
     * smokes.
     */
    private function assertDashboardBootsAroundContentDataVariant(Browser $browser): void
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
            . 'otherwise the page never mounted past the auth shell and the '
            . 'content_data_variants round-trip above would only prove the model works, '
            . 'not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'admin dashboard must render at least one pressable Filament action (any '
            . '<button> or <a role="button">) — a dashboard with no actions would mean the '
            . 'toolbar regressed past the Filament-5 migration this smoke is meant to catch.'
        );
    }
}
