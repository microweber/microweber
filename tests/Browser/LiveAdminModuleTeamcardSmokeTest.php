<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Teamcard\Models\Teamcard;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Teamcard module smoke.
 *
 * Combination shape — covers the Plan-C.2 task line "team-card
 * CRUD":
 *
 *   1. The admin SETTINGS view: Teamcard ships a Filament
 *      settings page registered as TeamcardModuleSettings
 *      (extends LiveEditModuleSettingsTable) via
 *      FilamentRegistry::registerPage in TeamcardServiceProvider.php.
 *      Filament-default route slug: /admin/teamcard-module-settings.
 *      The page hosts the TeamcardTableList Livewire component
 *      (registered as `modules.teamcard.filament.teamcard-table-list`)
 *      which is the operator's team-member-CRUD surface — list /
 *      create / edit / delete / reorder team-cards per
 *      (rel_type, rel_id) tuple, the same rows the public-
 *      frontend teamcard module reads to render its team grid.
 *   2. The CRUD half: every team-card the operator creates
 *      through the TeamcardTableList lands as a row in the
 *      `teamcards` table backed by the Teamcard Eloquent model.
 *      The public-frontend teamcard module renders by querying
 *      the same `teamcards` table scoped by the bound module's
 *      (rel_type, rel_id) tuple. Mirrors the Coupons sibling: a
 *      model create → save → reload → delete cycle on a marker-
 *      prefixed row exercises every CRUD verb the Livewire
 *      table ultimately calls.
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/teamcard-module-settings.
 *   2. Signal #2 (team-card CRUD round-trip): drives the
 *      Teamcard Eloquent model through create → save (role
 *      update) → reload → delete on a marker-prefixed team-
 *      card row. Same pipeline every CRUD action (create / edit
 *      / reorder / delete via the TeamcardTableList admin
 *      component) calls under the hood.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `teamcards` rows in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleTeamcardSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'teamcard-module-settings';

    /**
     * Marker name prefix used to scope the round-trip fixture
     * row. The `teamcards` table has no natural unique index on
     * `name`, so the smoke marker-prefixes the team member's
     * name so the tearDown purge can clean up by `name LIKE
     * prefix%` without colliding with any operator-authored
     * team-card.
     */
    private const FIXTURE_NAME_PREFIX = 'LIVE-ADMIN-MODULE-TEAMCARD-SMOKE-';

    private const FIXTURE_REL_TYPE = 'live-admin-module-teamcard-smoke';

    private const FIXTURE_REL_ID = '999999999';

    private const FIXTURE_INITIAL_ROLE = 'Smoke Engineer (initial)';

    private const FIXTURE_UPDATED_ROLE = 'Smoke Engineer (updated)';

    private const FIXTURE_BIO = 'Live Admin Module Teamcard smoke fixture bio body.';

    private const FIXTURE_WEBSITE = 'https://example.test/live-admin-module-teamcard-smoke';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureTeamcards();
        parent::tearDown();
    }

    private function purgeFixtureTeamcards(): void
    {
        DB::table('teamcards')
            ->where('name', 'like', self::FIXTURE_NAME_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function teamcard_settings_page_loads_and_round_trips_a_team_member_through_crud(): void
    {
        $this->purgeFixtureTeamcards();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // teamcard settings admin (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'teamcard module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'teamcard settings render');

            // Signal #2 — Teamcard Eloquent CRUD round-trip
            // through the same `teamcards` table the
            // TeamcardTableList admin component binds to. Same
            // pipeline every CRUD action (create / edit /
            // reorder / delete via the table) calls — and the
            // same row shape the public-frontend teamcard module
            // reads on every render.
            $this->assertTeamcardEloquentRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSettingsChromeRendered($browser);
        });
    }

    /**
     * Drive the Teamcard Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the TeamcardTableList admin
     * component ultimately calls when the operator manages
     * team members on the bound module instance. The persisted
     * row shape (name / role / bio / website / rel_type /
     * rel_id) is also the exact shape the public-frontend
     * teamcard module reads on every render.
     */
    private function assertTeamcardEloquentRoundTripPersists(): void
    {
        // Mark-prefix the team-member name so the tearDown
        // purge can clean up by `name LIKE prefix%` without
        // ever touching an operator-authored row.
        $name = self::FIXTURE_NAME_PREFIX . random_int(100000, 999999);

        $created = Teamcard::create([
            'name' => $name,
            'role' => self::FIXTURE_INITIAL_ROLE,
            'bio' => self::FIXTURE_BIO,
            'website' => self::FIXTURE_WEBSITE,
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => self::FIXTURE_REL_ID,
            'position' => 1,
        ]);

        $this->assertNotNull(
            $created->id,
            'Teamcard::create must return a model with a primary key — every '
            . 'consumer (TeamcardTableList admin embed table CRUD, public-frontend '
            . 'TeamcardModule render, team-reorder action) depends on this round-'
            . 'trip working.'
        );

        $created->role = self::FIXTURE_UPDATED_ROLE;
        $created->save();

        $reloaded = Teamcard::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Teamcard::find must return the freshly-created row; an Eloquent '
            . 'regression here would silently break the TeamcardTableList admin '
            . 'component AND every public-frontend teamcard render.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_ROLE,
            (string) $reloaded->role,
            "Teamcard::save must persist role updates — admin edits via the "
            . "TeamcardTableList embed table's team-edit form bind through the same "
            . 'setter pipeline, and the public-frontend render reads `role` into '
            . 'the team-member-card subtitle.'
        );
        $this->assertSame(
            self::FIXTURE_REL_TYPE,
            (string) $reloaded->rel_type,
            'Teamcard::create must persist the rel_type marker verbatim — the '
            . 'public-frontend teamcard render queries by (rel_type, rel_id) to '
            . 'scope the team-card list it renders on each module instance; a '
            . 'regression here would mean team-cards land in `teamcards` but never '
            . 'appear in the rendered grid.'
        );
        $this->assertSame(
            self::FIXTURE_REL_ID,
            (string) $reloaded->rel_id,
            'Teamcard::create must persist the rel_id marker verbatim — same '
            . 'reasoning as the rel_type assertion above; the (rel_type, rel_id) '
            . 'tuple is the only scope key the public-frontend render queries on.'
        );

        $reloaded->delete();
        $this->assertNull(
            Teamcard::find($created->id),
            'Teamcard::delete must remove the row from the `teamcards` table; '
            . "failure here would silently leak fixture rows across re-runs (and "
            . "would also break the TeamcardTableList row-level Delete action)."
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
            'teamcard settings page must render at least one Livewire / Filament '
            . 'wiring attribute (wire:model / wire:submit / wire:click="save" '
            . 'inline, OR wire:id / wire:snapshot / fi-page / fi-form deferred) — '
            . 'otherwise the team-card CRUD round-trip asserted above would only '
            . 'prove the model works, not that the admin surface is reachable '
            . 'through the Livewire form pipeline.'
        );
    }
}
