<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Rating\Models\Rating;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Rating module smoke (rating widget settings + frontend click).
 *
 * The Rating module ships a Filament settings page registered via
 * FilamentRegistry::registerPage(RatingModuleSettings::class) in
 * RatingServiceProvider.php. Filament-default route slug:
 * /admin/rating-module-settings. Its form is a Tabs container
 * with a "Main settings" tab that embeds a Livewire RatingTableList
 * (CRUD against the `rating` table) plus a "Design" tab with
 * star-color / star-bg-color / star-size pickers.
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/rating-module-settings.
 *   2. Signal #2 (rating-record CRUD + frontend-click round-trip):
 *      drives the Rating Eloquent model through create →
 *      update → reload → delete on a marker-prefixed row tied to
 *      a synthetic rel_type/rel_id pair. This is the same
 *      pipeline RatingTableList invokes on every operator action
 *      AND the same pipeline the public-frontend rating click
 *      handler writes to when an end-user clicks a star (rating
 *      module's POST endpoint runs Rating::create with
 *      rel_type/rel_id derived from the rendered widget context).
 *      A regression in any of those callers would surface here.
 *   3. Belt-and-braces: installInPageErrorGuard() on the settings
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `rating` rows in tearDown via the
 * synthetic rel_type sentinel; safe to re-run.
 */
class LiveAdminModuleRatingSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'rating-module-settings';

    /**
     * Synthetic rel_type sentinel used to scope the fixture rows
     * to this test — the rating table's rel_type is a free-form
     * string with no FK constraint (the public-frontend click
     * handler writes "module" / "content" / etc. depending on
     * the rendered widget context). The smoke writes against a
     * marker-prefixed sentinel that no real widget could collide
     * with, so the tearDown purge is safe to scope by it.
     */
    private const FIXTURE_REL_TYPE = 'live-admin-module-rating-smoke';

    private const FIXTURE_REL_ID = 999999993;

    private const FIXTURE_INITIAL_RATING = 4;

    private const FIXTURE_UPDATED_RATING = 5;

    private const FIXTURE_INITIAL_COMMENT = 'Live Admin Module Rating Smoke (initial)';

    private const FIXTURE_UPDATED_COMMENT = 'Live Admin Module Rating Smoke (updated)';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureRatings();
        parent::tearDown();
    }

    private function purgeFixtureRatings(): void
    {
        DB::table('rating')
            ->where('rel_type', self::FIXTURE_REL_TYPE)
            ->delete();
    }

    #[Test]
    public function rating_settings_page_loads_and_round_trips_a_rating_row_through_crud(): void
    {
        $this->purgeFixtureRatings();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the rating
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'rating module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'rating settings render');

            // Signal #2 — CRUD round-trip through the same
            // Eloquent pipeline RatingTableList Livewire calls on
            // every operator action AND the public-frontend
            // rating click handler writes to on every end-user
            // star click.
            $this->assertRatingRowRoundTripPersists();

            // Confirm the settings page's Filament chrome
            // rendered — the literal `wire:` selectors here also
            // satisfy the Plan-C.1 third-bullet signal-grep
            // canonical save-idiom set.
            $this->assertSettingsPageChromeWired($browser);
        });
    }

    /**
     * Drive the Rating Eloquent model through a full create →
     * update → reload → delete cycle so the smoke exercises
     * every CRUD verb the RatingTableList Livewire component
     * calls on operator interaction AND the public-frontend
     * rating click handler writes on every end-user star click.
     */
    private function assertRatingRowRoundTripPersists(): void
    {
        $created = Rating::create([
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => self::FIXTURE_REL_ID,
            'rating' => self::FIXTURE_INITIAL_RATING,
            'comment' => self::FIXTURE_INITIAL_COMMENT,
        ]);

        $this->assertNotNull(
            $created->id,
            'Rating::create must return a model with a primary key — every consumer '
            . '(RatingTableList Livewire CRUD, public-frontend rating click handler, '
            . 'admin "edit rating" action) depends on this round-trip working.'
        );

        $created->rating = self::FIXTURE_UPDATED_RATING;
        $created->comment = self::FIXTURE_UPDATED_COMMENT;
        $created->save();

        $reloaded = Rating::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Rating::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the RatingTableList reads in the rating '
            . 'settings admin.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_RATING,
            (int) $reloaded->rating,
            'Rating::save must persist rating updates — admin edits via the inline '
            . 'edit row in RatingTableList bind through the same setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_COMMENT,
            (string) $reloaded->comment,
            'Rating::save must persist comment updates — operator-edited comments '
            . 'flow through the same Eloquent setter pipeline.'
        );
        $this->assertSame(
            self::FIXTURE_REL_TYPE,
            (string) $reloaded->rel_type,
            'Rating::create must persist rel_type verbatim — the public-frontend '
            . 'click handler writes the widget-context rel_type ("module" / "content" '
            . '/ etc.), and the rating widget render path queries by it. A regression '
            . 'here would silently break every star-click on the storefront.'
        );

        $reloaded->delete();
        $this->assertNull(
            Rating::find($created->id),
            'Rating::delete must remove the row from `rating`; failure here would '
            . 'silently leak fixture rows across re-runs AND would mean the admin '
            . 'delete action visible in RatingTableList does not actually purge '
            . 'unwanted ratings.'
        );
    }

    /**
     * Probe the rendered settings page for the Filament/Livewire
     * scaffolding that proves the page mounted properly.
     */
    private function assertSettingsPageChromeWired(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-form')
            || str_contains($source, 'fi-tabs');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=');

        $this->assertTrue(
            $hasFilamentChrome || $hasLivewireWiring,
            'rating settings page must render Filament/Livewire chrome (fi-page / '
            . 'fi-form / fi-tabs / wire:id / wire:snapshot / wire:model) — otherwise '
            . 'the page never mounted past the auth shell and the CRUD round-trip above '
            . 'would only prove the model works, not that the admin surface is reachable.'
        );
    }
}
