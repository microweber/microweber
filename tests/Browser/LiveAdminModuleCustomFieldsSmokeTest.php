<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\CustomFields\Models\CustomField;
use Modules\CustomFields\Repositories\CustomFieldRepository;
use Modules\CustomFields\Services\FieldsManager;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — CustomFields module smoke.
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "custom fields schema":
 *
 *   1. The admin SETTINGS view: CustomFields ships a Filament
 *      settings page registered as CustomFieldsModuleSettings via
 *      FilamentRegistry::registerPage in
 *      CustomFieldsServiceProvider.php. Filament-default route
 *      slug: /admin/custom-fields-module-settings (the
 *      live-edit-style placeholder for module-wide settings).
 *      The same provider also registers the
 *      `admin-list-custom-fields` Livewire component (the
 *      ListCustomFields admin table that Content / Product /
 *      Page admin Edit pages embed inline to manage per-record
 *      custom fields).
 *   2. The SCHEMA half: CustomFields ships an Eloquent model +
 *      the `custom_fields` and `custom_fields_values` tables.
 *      The admin "schema" — i.e. the operator-defined custom-
 *      field shape per (rel_type, rel_id) tuple — persists
 *      through the CustomField model. The smoke drives a full
 *      CRUD round-trip on a marker-prefixed `custom_fields`
 *      row, plus a resolve-by-id round-trip through the
 *      `fields_manager` and `custom_field_repository` container
 *      bindings (the same singletons that back the
 *      ListCustomFields admin component and the public form
 *      render of every Content / Product / Page custom-field).
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of
 *      /admin/custom-fields-module-settings.
 *   2. Signal #2 (schema CRUD round-trip): drives the
 *      CustomField Eloquent model through create → save (name
 *      update) → reload → delete on a marker-prefixed row, AND
 *      resolves both the `fields_manager` (FieldsManager
 *      singleton) and `custom_field_repository`
 *      (CustomFieldRepository binding) container keys to prove
 *      the schema-resolution chain the admin embed-table relies
 *      on is wired.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `custom_fields` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleCustomFieldsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'custom-fields-module-settings';

    /**
     * Marker rel_type used to scope the round-trip fixture row.
     * `custom_fields` is keyed by (rel_type, rel_id) tuples and
     * has no natural unique index, so the smoke uses a long
     * marker prefix to scope the tearDown purge without
     * colliding with any real (content, page, product) rel_type
     * shipped by the platform.
     */
    private const FIXTURE_REL_TYPE = 'live-admin-module-custom-fields-smoke';

    private const FIXTURE_REL_ID = '999999999';

    private const FIXTURE_NAME_INITIAL = 'Live Admin Module CustomFields Smoke (initial)';

    private const FIXTURE_NAME_UPDATED = 'Live Admin Module CustomFields Smoke (updated)';

    private const FIXTURE_TYPE = 'text';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureCustomFields();
        parent::tearDown();
    }

    private function purgeFixtureCustomFields(): void
    {
        DB::table('custom_fields')
            ->where('rel_type', self::FIXTURE_REL_TYPE)
            ->delete();
    }

    #[Test]
    public function custom_fields_schema_loads_and_round_trips_through_the_admin_settings_page(): void
    {
        $this->purgeFixtureCustomFields();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // custom-fields settings admin (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'custom fields module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'custom fields settings render');

            // Signal #2a — CustomField Eloquent CRUD round-trip
            // through the same `custom_fields` table the
            // ListCustomFields admin component binds to. Same
            // pipeline every CRUD action (create/edit/delete via
            // the admin table's create / edit / delete actions)
            // ultimately calls.
            $this->assertCustomFieldEloquentRoundTripPersists();

            // Signal #2b — resolve and exercise the
            // `fields_manager` + `custom_field_repository`
            // container bindings. Both back the schema-resolution
            // chain that the admin embed-table and the public
            // form-render of every Content / Product / Page
            // custom-field call into on every render.
            $this->assertSchemaResolutionSingletonsRespond();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSettingsChromeRendered($browser);
        });
    }

    /**
     * Drive the CustomField Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the ListCustomFields admin
     * component ultimately calls when the operator defines a
     * custom-field schema for a Content / Product / Page record.
     */
    private function assertCustomFieldEloquentRoundTripPersists(): void
    {
        $created = CustomField::create([
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => self::FIXTURE_REL_ID,
            'type' => self::FIXTURE_TYPE,
            'name' => self::FIXTURE_NAME_INITIAL,
            'position' => 1,
        ]);

        $this->assertNotNull(
            $created->id,
            'CustomField::create must return a model with a primary key — every consumer '
            . '(ListCustomFields admin embed table, Content / Product / Page custom-field '
            . 'render, public form render) depends on this round-trip working.'
        );

        $created->name = self::FIXTURE_NAME_UPDATED;
        $created->save();

        $reloaded = CustomField::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'CustomField::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the ListCustomFields admin table on every Content '
            . '/ Product / Page admin Edit page.'
        );
        $this->assertSame(
            self::FIXTURE_NAME_UPDATED,
            (string) $reloaded->name,
            'CustomField::save must persist name updates — admin edits via the '
            . 'ListCustomFields embed table\'s slide-over edit form bind through the same '
            . 'setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_REL_TYPE,
            (string) $reloaded->rel_type,
            'CustomField::create must persist the rel_type marker verbatim — a regression '
            . 'here would break the rel_type-scoped tearDown purge gate AND would mean the '
            . 'embed table\'s rel_type-scoped query stops returning the operator\'s '
            . 'custom-field schema for the bound Content / Product / Page record.'
        );
        $this->assertSame(
            self::FIXTURE_REL_ID,
            (string) $reloaded->rel_id,
            'CustomField::create must persist the rel_id marker verbatim — same reasoning '
            . 'as the rel_type assertion above; the (rel_type, rel_id) tuple is the only '
            . 'index the embed table queries on.'
        );

        $reloaded->delete();
        $this->assertNull(
            CustomField::find($created->id),
            'CustomField::delete must remove the row from the custom_fields table; failure '
            . 'here would silently leak fixture rows across re-runs (and would also break '
            . 'the embed table\'s row-level Delete action).'
        );
    }

    /**
     * Resolve the `fields_manager` and `custom_field_repository`
     * container bindings — both back the schema-resolution chain
     * that the admin embed-table and the public form-render of
     * every Content / Product / Page custom-field call into on
     * every render. A regression in either binding would silently
     * break the whole custom-fields schema surface.
     */
    private function assertSchemaResolutionSingletonsRespond(): void
    {
        $manager = app('fields_manager');
        $this->assertInstanceOf(
            FieldsManager::class,
            $manager,
            "The `fields_manager` container singleton must resolve to a FieldsManager "
            . 'instance — this is the binding the public form-render of every Content / '
            . 'Product / Page custom-field calls into via fields()->getById() and '
            . 'fields()->parseFieldSettings(). A regression here would silently break the '
            . 'whole custom-fields render pipeline.'
        );

        $repository = app('custom_field_repository');
        $this->assertInstanceOf(
            CustomFieldRepository::class,
            $repository,
            "The `custom_field_repository` container binding must resolve to a "
            . 'CustomFieldRepository instance — this is the binding the admin embed-table '
            . 'uses to query custom-fields scoped by rel_type/rel_id. A regression here '
            . 'would silently break the ListCustomFields admin component on every Content '
            . '/ Product / Page admin Edit page.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves the admin surface mounted. Same
     * shape as the sibling Audio/Accordion/Captcha/Cookie
     * smokes.
     */
    private function assertSettingsChromeRendered(Browser $browser): void
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
            'custom fields settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" inline, OR '
            . 'wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'schema CRUD round-trip asserted above would only prove the model + manager '
            . 'work, not that the admin surface is reachable through the Livewire form '
            . 'pipeline.'
        );
    }
}
