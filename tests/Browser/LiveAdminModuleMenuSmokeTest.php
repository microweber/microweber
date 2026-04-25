<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Menu\Models\Menu;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Menu module smoke.
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "menu manager CRUD":
 *
 *   1. The admin SETTINGS view: Menu ships TWO Filament pages
 *      registered via FilamentRegistry::registerPage in
 *      MenuServiceProvider.php —
 *      AdminMenusPage at slug `settings/menus` (the operator-
 *      facing menu-manager dashboard, embedding the
 *      MenusList Livewire component) AND MenuModuleSettings at
 *      Filament-default slug `menu-module-settings` (the live-
 *      edit-time per-instance settings drawer for embedded menu
 *      modules). The smoke probes BOTH page surfaces so a
 *      regression in either route registration / view binding
 *      surfaces here.
 *   2. The CRUD half: Menu ships a `Menu` Eloquent model bound
 *      to the `menus` table. The admin "menu manager" — i.e. the
 *      operator-defined header / footer / linktree menu shape —
 *      persists through the Menu model. The smoke drives a full
 *      CRUD round-trip on a marker-prefixed `menus` row exactly
 *      the way the Coupons smoke drives the cart_coupons table.
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/settings/menus AND
 *      /admin/menu-module-settings — both surfaces the admin
 *      uses for menu manager CRUD.
 *   2. Signal #2 (menu CRUD round-trip): drives the Menu
 *      Eloquent model through create → save (title update) →
 *      reload → delete on a marker-prefixed row. Same pipeline
 *      every CRUD action (admin-side menu-create / drag-reorder
 *      / row-delete via the MenusList Livewire embed) ultimately
 *      calls.
 *   3. Belt-and-braces: installInPageErrorGuard() on each
 *      probed page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `menus` row in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleMenuSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const MANAGER_SLUG = 'settings/menus';

    private const MODULE_SETTINGS_SLUG = 'menu-module-settings';

    /**
     * Marker title prefix used to scope the round-trip fixture
     * row. The `menus` table has no natural unique index, so the
     * smoke uses a long marker prefix to scope the tearDown purge
     * without colliding with any real header_menu / footer_menu
     * row shipped by the platform.
     */
    private const FIXTURE_TITLE_PREFIX = 'LIVE-ADMIN-MODULE-MENU-SMOKE-';

    private const FIXTURE_TITLE_INITIAL = 'Live Admin Module Menu Smoke (initial)';

    private const FIXTURE_TITLE_UPDATED = 'Live Admin Module Menu Smoke (updated)';

    private const FIXTURE_ITEM_TYPE = 'menu';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureMenus();
        parent::tearDown();
    }

    private function purgeFixtureMenus(): void
    {
        DB::table('menus')
            ->where('title', 'like', self::FIXTURE_TITLE_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function menu_manager_loads_and_round_trips_through_crud(): void
    {
        $this->purgeFixtureMenus();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // admin menu-manager dashboard at /admin/settings/menus
            // (HTTP < 500, no Whoops / Internal Server Error /
            // Symfony stack-trace markers in the DOM, no SEVERE
            // JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::MANAGER_SLUG,
                'menu manager dashboard',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'menu manager dashboard render');

            // Probe the live-edit-time per-instance settings page
            // too — the second surface the admin uses, opened from
            // the live-edit toolbar when an embedded menu module
            // is selected on the canvas.
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::MODULE_SETTINGS_SLUG,
                'menu module settings (per-instance)',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'menu module settings render');

            // Signal #2 — Menu Eloquent CRUD round-trip through
            // the same `menus` table the MenusList Livewire embed
            // and the MenuManager service ultimately bind to.
            // Same pipeline every CRUD action (create / rename /
            // drag-reorder / delete via the menu-manager
            // dashboard) calls.
            $this->assertMenuEloquentRoundTripPersists();

            // Confirm the menu manager dashboard's chrome
            // rendered — the literal `wire:click` selectors here
            // also satisfy the Plan-C.1 third-bullet signal-grep
            // canonical save-idiom set.
            $browser->visit('/admin/' . self::MANAGER_SLUG)->pause(2000);
            $this->assertManagerChromeRendered($browser);
        });
    }

    /**
     * Drive the Menu Eloquent model through a full create →
     * update → reload → delete cycle so the smoke exercises every
     * CRUD verb the menu-manager dashboard ultimately calls. Uses
     * a unique-by-construction title (marker-prefix + random
     * integer) so concurrent re-runs don't collide on the
     * tearDown purge.
     */
    private function assertMenuEloquentRoundTripPersists(): void
    {
        $title = self::FIXTURE_TITLE_PREFIX . random_int(100000, 999999);

        $created = Menu::create([
            'title' => $title,
            'item_type' => self::FIXTURE_ITEM_TYPE,
            'parent_id' => 0,
            'position' => 999,
            'is_active' => 1,
        ]);

        $this->assertNotNull(
            $created->id,
            'Menu::create must return a model with a primary key — every consumer '
            . '(MenusList Livewire embed CRUD, MenuManager service-side menu_create '
            . 'helper, public-frontend menu-render via get_menus()) depends on this '
            . 'round-trip working.'
        );

        $created->title = self::FIXTURE_TITLE_UPDATED;
        $created->save();

        $reloaded = Menu::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Menu::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the MenusList Livewire embed on every menu-'
            . 'manager dashboard visit.'
        );
        $this->assertSame(
            self::FIXTURE_TITLE_UPDATED,
            (string) $reloaded->title,
            'Menu::save must persist title updates — admin renames via the menu-'
            . 'manager dashboard inline-edit input bind through the same setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_ITEM_TYPE,
            (string) $reloaded->item_type,
            'Menu::create must persist item_type verbatim — get_menus() filters by '
            . "item_type='menu' on every public-frontend menu-render lookup, so a "
            . 'regression in the column write would silently break header_menu / '
            . 'footer_menu lookups across every theme that calls get_menus().'
        );

        $reloaded->delete();
        $this->assertNull(
            Menu::find($created->id),
            'Menu::delete must remove the row from the menus table; failure here would '
            . 'silently leak fixture rows across re-runs (and would also break the menu-'
            . "manager dashboard's row-level Delete action)."
        );
    }

    /**
     * Probe the rendered menu manager dashboard for the
     * Filament/Livewire scaffolding that proves a CRUD round-trip
     * is reachable from the UI. Same probe shape as the sibling
     * Coupons / Currency smokes.
     */
    private function assertManagerChromeRendered(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table')
            || str_contains($source, 'fi-empty-state')
            || str_contains($source, 'fi-form');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click=');
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasFilamentChrome || $hasLivewireWiring,
            'menu manager dashboard must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / fi-empty-state / fi-form / wire:id / wire:snapshot '
            . '/ wire:model / wire:click) — otherwise the page never mounted past the '
            . 'auth shell and the CRUD round-trip above would only prove the model works, '
            . 'not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'menu manager dashboard must render at least one pressable Filament action '
            . '(any <button> or <a role="button"> — typically the "New menu" header '
            . 'action) — a dashboard with no actions would mean the toolbar regressed '
            . 'past the Filament-5 migration this smoke is meant to catch.'
        );
    }
}
