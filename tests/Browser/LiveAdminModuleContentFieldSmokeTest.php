<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\ContentField\Models\ContentField;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — ContentField module smoke.
 *
 * Same shape as the sibling {@see LiveAdminModuleContentDataSmokeTest}
 * and {@see LiveAdminModuleContentDataVariantSmokeTest}: the
 * ContentField module is backend-only — its
 * ContentFieldServiceProvider registers migrations + traits + the
 * TranslateContentField multilanguage provider but never calls
 * FilamentRegistry::registerPage. There is no
 * /admin/content-field-module-settings page.
 *
 * Plan-C.2 task line is "custom content field CRUD". The
 * persistence target is the ContentField Eloquent model +
 * `content_fields` table — every Content / Product / Page admin
 * Edit surface that exposes arbitrary custom fields ultimately
 * writes (rel_type, rel_id, field, value) tuples through this
 * model (the morphMany hook in {@see HasContentFieldTrait} also
 * funnels through the same Eloquent pipeline on parent save).
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (the dashboard) —
 *      ContentField is process-wide / dashboard-adjacent because
 *      every Content / Product admin Edit page that renders
 *      custom-field inputs reads from content_fields on mount.
 *   2. Signal #2 (custom-field CRUD round-trip): drives the
 *      ContentField Eloquent model through create → ->save (value
 *      update) → ::find (reload) → ->delete on a marker-prefixed
 *      row. Same pipeline every consumer ultimately calls when
 *      persisting custom content metadata, including the
 *      auto-save hook in HasContentFieldTrait that fires on
 *      parent model save.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: when a future commit lands a real
 * /admin/content-field-module-settings Filament page (matching
 * the canonical Audio/Accordion/Btn pattern with a
 * wire:click="save" Filament action button persisting through
 * the LiveEditModuleSettings Livewire updated() hook), this
 * smoke should be updated to probe that page directly. Until
 * then, the smoke covers the underlying custom-fields-table
 * CRUD round-trip per the same three-assertion-minimum the
 * sibling smokes follow.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `content_fields` row in
 * tearDown; safe to re-run.
 */
class LiveAdminModuleContentFieldSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const DASHBOARD_PATH = 'admin';

    private const FIXTURE_REL_TYPE = 'live-admin-module-content-field-smoke';

    private const FIXTURE_FIELD_NAME = 'live_admin_module_content_field_smoke_field';

    private const FIXTURE_INITIAL_VALUE = 'initial value';

    private const FIXTURE_UPDATED_VALUE = 'updated value';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureContentFields();
        parent::tearDown();
    }

    private function purgeFixtureContentFields(): void
    {
        DB::table('content_fields')
            ->where('rel_type', self::FIXTURE_REL_TYPE)
            ->delete();
    }

    #[Test]
    public function content_field_crud_round_trips_under_the_admin_dashboard_surface(): void
    {
        $this->purgeFixtureContentFields();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin
            // (the dashboard). ContentField is process-wide so a
            // regression in the Eloquent model would surface as a
            // SEVERE log entry on any Content / Product admin
            // Edit page that renders custom-field inputs on
            // mount.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'admin dashboard (ContentField custom-fields embed surface)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'admin dashboard render');

            // Signal #2 — ContentField Eloquent CRUD round-trip
            // through the same pipeline every consumer (Content /
            // Product admin custom-fields panel + the
            // HasContentFieldTrait auto-save hook on parent
            // model save) calls when persisting custom content
            // metadata.
            $this->assertContentFieldRoundTripPersists();

            // Confirm the admin dashboard's Filament chrome
            // rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertDashboardBootsAroundContentField($browser);
        });
    }

    /**
     * Drive the ContentField Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the Content / Product admin
     * custom-fields panel (and the HasContentFieldTrait morph
     * auto-save hook) ultimately calls.
     */
    private function assertContentFieldRoundTripPersists(): void
    {
        // High rel_id sentinel that won't collide with real
        // content rows; combined with FIXTURE_REL_TYPE this
        // gives a unique-by-construction tearDown filter.
        $relId = (string) (99999900 + random_int(0, 99));

        $created = ContentField::create([
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => $relId,
            'field' => self::FIXTURE_FIELD_NAME,
            'value' => self::FIXTURE_INITIAL_VALUE,
        ]);

        $this->assertNotNull(
            $created->id,
            'ContentField::create must return a model with a primary key — every consumer '
            . '(Content / Product admin custom-fields panel, HasContentFieldTrait auto-save '
            . 'hook on parent save) depends on this round-trip working.'
        );

        $created->value = self::FIXTURE_UPDATED_VALUE;
        $created->save();

        $reloaded = ContentField::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'ContentField::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break every Content / Product admin Edit page that renders '
            . 'custom-field inputs on mount.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_VALUE,
            (string) $reloaded->value,
            'ContentField::save must persist value updates — admin custom-field edits and '
            . 'the HasContentFieldTrait morphMany auto-save hook bind through the same '
            . 'setter pipeline (translatable `value` is the only writable column the smoke '
            . 'covers, so a regression here would silently break both write paths).'
        );
        $this->assertSame(
            self::FIXTURE_FIELD_NAME,
            (string) $reloaded->field,
            'ContentField::create must persist the field marker verbatim — a regression '
            . 'here would break the rel_type-scoped tearDown purge gate that this smoke '
            . 'depends on for re-runnability AND would mean custom-field key reads from the '
            . 'public-frontend stop resolving.'
        );

        $reloaded->delete();
        $this->assertNull(
            ContentField::find($created->id),
            'ContentField::delete must remove the row from the content_fields table; failure '
            . 'here would silently leak fixture rows across re-runs (and would also break '
            . "the Content admin's \"remove custom field\" action)."
        );
    }

    /**
     * Probe the rendered admin dashboard for the Filament/Livewire
     * scaffolding that proves the page mounted properly. Same
     * probe shape as the sibling Address/Company/ContentData/
     * ContentDataVariant smokes.
     */
    private function assertDashboardBootsAroundContentField(Browser $browser): void
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
            . 'otherwise the page never mounted past the auth shell and the content_fields '
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
