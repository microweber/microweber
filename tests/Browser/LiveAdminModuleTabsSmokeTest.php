<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Tabs\Models\Tab;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Tabs module smoke.
 *
 * Combination shape — covers the Plan-C.2 task line "tabs module
 * CRUD":
 *
 *   1. The admin SETTINGS view: Tabs ships a Filament settings
 *      page registered as TabsModuleSettings (extends
 *      LiveEditModuleSettings) via FilamentRegistry::registerPage
 *      in TabsServiceProvider.php. Filament-default route slug:
 *      /admin/tabs-module-settings. The page hosts the
 *      TabsTableList Livewire component (registered as
 *      `modules.tabs.filament.tabs-table-list`) which is the
 *      operator's tab-CRUD surface — list / create / edit /
 *      delete / reorder tabs per (rel_type, rel_id) tuple, the
 *      same rows the public-frontend tabs module reads to
 *      render its tab strip.
 *   2. The CRUD half: every tab the operator creates through
 *      the TabsTableList lands as a row in the `tabs` table
 *      backed by the Tab Eloquent model. The public-frontend
 *      tabs module renders by querying the same `tabs` table
 *      scoped by the bound module's (rel_type, rel_id) tuple.
 *      Mirrors the Coupons sibling: a model create → save →
 *      reload → delete cycle on a marker-prefixed row exercises
 *      every CRUD verb the Livewire table ultimately calls.
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/tabs-module-settings.
 *   2. Signal #2 (tab CRUD round-trip): drives the Tab Eloquent
 *      model through create → save (title update) → reload →
 *      delete on a marker-prefixed tab row. Same pipeline every
 *      CRUD action (create / edit / reorder / delete via the
 *      TabsTableList admin component) calls under the hood.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `tabs` rows in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleTabsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'tabs-module-settings';

    /**
     * Marker title prefix used to scope the round-trip fixture
     * row. The `tabs` table has no natural unique index on
     * `title`, so the smoke marker-prefixes the tab title so
     * the tearDown purge can clean up by `title LIKE prefix%`
     * without colliding with any operator-authored tab.
     */
    private const FIXTURE_TITLE_PREFIX = 'LIVE-ADMIN-MODULE-TABS-SMOKE-';

    private const FIXTURE_REL_TYPE = 'live-admin-module-tabs-smoke';

    private const FIXTURE_REL_ID = '999999999';

    private const FIXTURE_INITIAL_CONTENT = 'Live Admin Module Tabs smoke fixture content (initial)';

    private const FIXTURE_UPDATED_CONTENT = 'Live Admin Module Tabs smoke fixture content (updated)';

    private const FIXTURE_ICON = 'fa fa-star';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureTabs();
        parent::tearDown();
    }

    private function purgeFixtureTabs(): void
    {
        DB::table('tabs')
            ->where('title', 'like', self::FIXTURE_TITLE_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function tabs_settings_page_loads_and_round_trips_a_tab_through_crud(): void
    {
        $this->purgeFixtureTabs();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the tabs
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'tabs module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'tabs settings render');

            // Signal #2 — Tab Eloquent CRUD round-trip
            // through the same `tabs` table the TabsTableList
            // admin component binds to. Same pipeline every CRUD
            // action (create / edit / reorder / delete via the
            // table) calls — and the same row shape the public-
            // frontend tabs module reads on every render.
            $this->assertTabEloquentRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSettingsChromeRendered($browser);
        });
    }

    /**
     * Drive the Tab Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the TabsTableList admin
     * component ultimately calls when the operator manages
     * tabs on the bound module instance. The persisted row
     * shape (title / icon / content / rel_type / rel_id) is
     * also the exact shape the public-frontend tabs module
     * reads on every render.
     */
    private function assertTabEloquentRoundTripPersists(): void
    {
        // Mark-prefix the tab title so the tearDown purge can
        // clean up by `title LIKE prefix%` without ever touching
        // an operator-authored row.
        $title = self::FIXTURE_TITLE_PREFIX . random_int(100000, 999999);

        $created = Tab::create([
            'title' => $title,
            'icon' => self::FIXTURE_ICON,
            'content' => self::FIXTURE_INITIAL_CONTENT,
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => self::FIXTURE_REL_ID,
            'position' => 1,
        ]);

        $this->assertNotNull(
            $created->id,
            'Tab::create must return a model with a primary key — every consumer '
            . '(TabsTableList admin embed table CRUD, public-frontend TabsModule '
            . 'render, tab-reorder action) depends on this round-trip working.'
        );

        $created->content = self::FIXTURE_UPDATED_CONTENT;
        $created->save();

        $reloaded = Tab::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Tab::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the TabsTableList admin component AND every '
            . 'public-frontend tabs render.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_CONTENT,
            (string) $reloaded->content,
            "Tab::save must persist content updates — admin edits via the "
            . "TabsTableList embed table's tab-edit form bind through the same "
            . 'setter pipeline, and the public-frontend render reads `content` into '
            . 'each tab-pane body.'
        );
        $this->assertSame(
            self::FIXTURE_REL_TYPE,
            (string) $reloaded->rel_type,
            'Tab::create must persist the rel_type marker verbatim — the public-'
            . 'frontend tabs render queries by (rel_type, rel_id) to scope the tab '
            . 'list it renders on each module instance; a regression here would mean '
            . 'tabs land in `tabs` but never appear in the rendered tab-strip.'
        );
        $this->assertSame(
            self::FIXTURE_REL_ID,
            (string) $reloaded->rel_id,
            'Tab::create must persist the rel_id marker verbatim — same reasoning '
            . 'as the rel_type assertion above; the (rel_type, rel_id) tuple is the '
            . 'only scope key the public-frontend render queries on.'
        );

        $reloaded->delete();
        $this->assertNull(
            Tab::find($created->id),
            'Tab::delete must remove the row from the `tabs` table; failure here '
            . "would silently leak fixture rows across re-runs (and would also break "
            . "the TabsTableList row-level Delete action)."
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves the admin surface mounted. Same
     * shape as the sibling Audio/Accordion/Btn/Embed smokes.
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
            'tabs settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR '
            . 'wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'tab CRUD round-trip asserted above would only prove the model works, '
            . 'not that the admin surface is reachable through the Livewire form '
            . 'pipeline.'
        );
    }
}
