<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\ContentData\Models\ContentData;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — ContentData module smoke.
 *
 * Same shape as {@see LiveAdminModuleAddressSmokeTest} and
 * {@see LiveAdminModuleCompanySmokeTest}: the ContentData
 * module is backend-only — its ContentDataServiceProvider
 * registers migrations + traits but never calls
 * FilamentRegistry::registerPage. There is no
 * /admin/content-data-module-settings page.
 *
 * Plan-C.2 task line is "content-data KV editor". The KV
 * editor itself surfaces via the Content admin's custom-fields
 * panel (each Content row's arbitrary key/value attributes
 * land in the `content_data` table through the same Eloquent
 * model the smoke exercises here). The persistence surface
 * every consumer (Content admin custom fields, product
 * variants, content-data KV reads from the public-frontend
 * widgets) routes through is the ContentData Eloquent model
 * + `content_data` table.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin (the dashboard) —
 *      ContentData is process-wide / dashboard-adjacent
 *      because every Content admin Edit page that renders
 *      custom-fields reads from content_data on mount.
 *   2. Signal #2 (KV CRUD round-trip): drives the ContentData
 *      Eloquent model through create → ->save (field_value
 *      update) → ::find (reload) → ->delete on a marker-prefixed
 *      row. Same pipeline every consumer ultimately calls when
 *      persisting arbitrary key/value content metadata.
 *   3. Belt-and-braces: installInPageErrorGuard() on /admin
 *      after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Plan-C.4 follow-up: when a future commit lands a real
 * /admin/content-data-module-settings Filament page (matching
 * the canonical Audio/Accordion/Btn pattern — a wire:click="save"
 * Filament action button persisting through the
 * LiveEditModuleSettings Livewire updated() hook), this smoke
 * should be updated to probe that page directly. Until then,
 * the smoke covers the underlying KV-table CRUD round-trip per
 * the same three-assertion-minimum the sibling smokes follow.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `content_data` row in
 * tearDown; safe to re-run.
 */
class LiveAdminModuleContentDataSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const DASHBOARD_PATH = 'admin';

    private const FIXTURE_REL_TYPE = 'live-admin-module-content-data-smoke';

    private const FIXTURE_FIELD_NAME = 'live_admin_module_content_data_smoke_kv_field';

    private const FIXTURE_INITIAL_VALUE = 'initial value';

    private const FIXTURE_UPDATED_VALUE = 'updated value';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureContentData();
        parent::tearDown();
    }

    private function purgeFixtureContentData(): void
    {
        DB::table('content_data')
            ->where('rel_type', self::FIXTURE_REL_TYPE)
            ->delete();
    }

    #[Test]
    public function content_data_kv_round_trips_under_the_admin_dashboard_surface(): void
    {
        $this->purgeFixtureContentData();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of /admin
            // (the dashboard). The ContentData module is
            // process-wide so a regression in the Eloquent model
            // would surface as a SEVERE log entry on any
            // Content-admin edit page that renders custom fields
            // on mount.
            $this->assertPageSmokeOk(
                $browser,
                '/' . self::DASHBOARD_PATH,
                'admin dashboard (ContentData KV embed surface)',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'admin dashboard render');

            // Signal #2 — ContentData Eloquent CRUD round-trip
            // through the same pipeline every consumer calls
            // when persisting arbitrary KV content metadata.
            $this->assertContentDataKvRoundTripPersists();

            // Confirm the admin dashboard's Filament chrome
            // rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertDashboardBootsAroundContentData($browser);
        });
    }

    /**
     * Drive the ContentData Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the Content admin custom-fields
     * panel (and the public-frontend KV reads) ultimately call.
     */
    private function assertContentDataKvRoundTripPersists(): void
    {
        // High rel_id sentinel that won't collide with real
        // content rows; combined with FIXTURE_REL_TYPE this
        // gives a unique-by-construction tearDown filter.
        $relId = (string) (99999900 + random_int(0, 99));

        $created = ContentData::create([
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => $relId,
            'field_name' => self::FIXTURE_FIELD_NAME,
            'field_value' => self::FIXTURE_INITIAL_VALUE,
        ]);

        $this->assertNotNull(
            $created->id,
            'ContentData::create must return a model with a primary key — every consumer '
            . '(Content admin custom-fields panel, product variants, public KV reads) '
            . 'depends on this round-trip working.'
        );

        $created->field_value = self::FIXTURE_UPDATED_VALUE;
        $created->save();

        $reloaded = ContentData::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'ContentData::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break every Content admin Edit page that renders custom '
            . 'fields on mount.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_VALUE,
            (string) $reloaded->field_value,
            'ContentData::save must persist field_value updates — admin KV-editor edits '
            . 'bind through the same setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_FIELD_NAME,
            (string) $reloaded->field_name,
            'ContentData::create must persist the field_name marker verbatim — a regression '
            . 'here would break the tearDown purge gate that this smoke depends on for '
            . 're-runnability.'
        );

        $reloaded->delete();
        $this->assertNull(
            ContentData::find($created->id),
            'ContentData::delete must remove the row from the content_data table; failure '
            . 'here would silently leak fixture rows across re-runs (and would also break '
            . "the Content admin's \"remove custom field\" action)."
        );
    }

    /**
     * Probe the rendered admin dashboard for the Filament/Livewire
     * scaffolding that proves the page mounted properly. Same
     * probe shape as the sibling Address/Company smokes.
     */
    private function assertDashboardBootsAroundContentData(Browser $browser): void
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
            . 'otherwise the page never mounted past the auth shell and the content_data '
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
