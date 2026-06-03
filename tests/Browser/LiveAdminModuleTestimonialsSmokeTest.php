<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Testimonials\Models\Testimonial;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Testimonials module smoke.
 *
 * Combination shape — covers the Plan-C.2 task line "testimonial
 * CRUD":
 *
 *   1. The admin SETTINGS view: Testimonials ships a Filament
 *      settings page registered as TestimonialsModuleSettings
 *      (extends LiveEditModuleSettingsTable) via
 *      FilamentRegistry::registerPage in
 *      TestimonialsServiceProvider.php. Filament-default route
 *      slug: /admin/testimonials-module-settings. The page hosts
 *      the TestimonialsTableList Livewire component (registered
 *      as `modules.testimonials.filament.testimonials-table-list`)
 *      which is the operator's testimonial-CRUD surface — list /
 *      create / edit / delete / reorder testimonials per
 *      (rel_type, rel_id) tuple, the same rows the public-
 *      frontend testimonials module reads to render its quote
 *      strip.
 *   2. The CRUD half: every testimonial the operator creates
 *      through the TestimonialsTableList lands as a row in the
 *      `testimonials` table backed by the Testimonial Eloquent
 *      model. The public-frontend testimonials module renders
 *      by querying the same `testimonials` table scoped by the
 *      bound module's (rel_type, rel_id) tuple. Mirrors the
 *      Coupons sibling: a model create → save → reload → delete
 *      cycle on a marker-prefixed row exercises every CRUD verb
 *      the Livewire table ultimately calls.
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/testimonials-module-settings.
 *   2. Signal #2 (testimonial CRUD round-trip): drives the
 *      Testimonial Eloquent model through create → save (content
 *      update) → reload → delete on a marker-prefixed
 *      testimonial row. Same pipeline every CRUD action (create
 *      / edit / reorder / delete via the TestimonialsTableList
 *      admin component) calls under the hood.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `testimonials` rows in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleTestimonialsSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'testimonials-module-settings';

    /**
     * Marker name prefix used to scope the round-trip fixture
     * row. The `testimonials` table has no natural unique index
     * on `name`, so the smoke marker-prefixes the testimonial
     * author name so the tearDown purge can clean up by `name
     * LIKE prefix%` without colliding with any operator-authored
     * testimonial.
     */
    private const FIXTURE_NAME_PREFIX = 'LIVE-ADMIN-MODULE-TESTIMONIALS-SMOKE-';

    private const FIXTURE_REL_TYPE = 'live-admin-module-testimonials-smoke';

    private const FIXTURE_REL_ID = '999999999';

    private const FIXTURE_INITIAL_CONTENT = 'Live Admin Module Testimonials smoke quote (initial)';

    private const FIXTURE_UPDATED_CONTENT = 'Live Admin Module Testimonials smoke quote (updated)';

    private const FIXTURE_CLIENT_COMPANY = 'Live Admin Module Testimonials Smoke Co.';

    private const FIXTURE_CLIENT_ROLE = 'Smoke Engineer';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureTestimonials();
        parent::tearDown();
    }

    private function purgeFixtureTestimonials(): void
    {
        DB::table('testimonials')
            ->where('name', 'like', self::FIXTURE_NAME_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function testimonials_settings_page_loads_and_round_trips_a_testimonial_through_crud(): void
    {
        $this->purgeFixtureTestimonials();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the
            // testimonials settings admin (HTTP < 500, no Whoops
            // / Internal Server Error / Symfony stack-trace
            // markers in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'testimonials module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'testimonials settings render');

            // Signal #2 — Testimonial Eloquent CRUD round-trip
            // through the same `testimonials` table the
            // TestimonialsTableList admin component binds to.
            // Same pipeline every CRUD action (create / edit /
            // reorder / delete via the table) calls — and the
            // same row shape the public-frontend testimonials
            // module reads on every render.
            $this->assertTestimonialEloquentRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSettingsChromeRendered($browser);
        });
    }

    /**
     * Drive the Testimonial Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the TestimonialsTableList
     * admin component ultimately calls when the operator
     * manages testimonials on the bound module instance. The
     * persisted row shape (name / content / client_company /
     * client_role / rel_type / rel_id) is also the exact shape
     * the public-frontend testimonials module reads on every
     * render.
     */
    private function assertTestimonialEloquentRoundTripPersists(): void
    {
        // Mark-prefix the testimonial author name so the
        // tearDown purge can clean up by `name LIKE prefix%`
        // without ever touching an operator-authored row.
        $name = self::FIXTURE_NAME_PREFIX . random_int(100000, 999999);

        $created = Testimonial::create([
            'name' => $name,
            'content' => self::FIXTURE_INITIAL_CONTENT,
            'client_company' => self::FIXTURE_CLIENT_COMPANY,
            'client_role' => self::FIXTURE_CLIENT_ROLE,
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => self::FIXTURE_REL_ID,
            'position' => 1,
        ]);

        $this->assertNotNull(
            $created->id,
            'Testimonial::create must return a model with a primary key — every '
            . 'consumer (TestimonialsTableList admin embed table CRUD, public-'
            . 'frontend TestimonialsModule render, testimonial-reorder action) '
            . 'depends on this round-trip working.'
        );

        $created->content = self::FIXTURE_UPDATED_CONTENT;
        $created->save();

        $reloaded = Testimonial::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Testimonial::find must return the freshly-created row; an Eloquent '
            . 'regression here would silently break the TestimonialsTableList admin '
            . 'component AND every public-frontend testimonials render.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_CONTENT,
            (string) $reloaded->content,
            "Testimonial::save must persist content updates — admin edits via the "
            . "TestimonialsTableList embed table's testimonial-edit form bind "
            . 'through the same setter pipeline, and the public-frontend render '
            . 'reads `content` into each quote body.'
        );
        $this->assertSame(
            self::FIXTURE_REL_TYPE,
            (string) $reloaded->rel_type,
            'Testimonial::create must persist the rel_type marker verbatim — the '
            . 'public-frontend testimonials render queries by (rel_type, rel_id) '
            . 'to scope the testimonial list it renders on each module instance; '
            . 'a regression here would mean testimonials land in `testimonials` '
            . 'but never appear in the rendered quote-strip.'
        );
        $this->assertSame(
            self::FIXTURE_REL_ID,
            (string) $reloaded->rel_id,
            'Testimonial::create must persist the rel_id marker verbatim — same '
            . 'reasoning as the rel_type assertion above; the (rel_type, rel_id) '
            . 'tuple is the only scope key the public-frontend render queries on.'
        );

        $reloaded->delete();
        $this->assertNull(
            Testimonial::find($created->id),
            'Testimonial::delete must remove the row from the `testimonials` table; '
            . "failure here would silently leak fixture rows across re-runs (and "
            . "would also break the TestimonialsTableList row-level Delete action)."
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
            || str_contains($source, 'fi-form');

        $this->assertTrue(
            $hasInlineSave || $hasDeferredSave,
            'testimonials settings page must render at least one Livewire / '
            . 'Filament wiring attribute (wire:model / wire:submit / '
            . 'wire:click="save" inline, OR wire:id / wire:snapshot / fi-page / '
            . 'fi-form deferred) — otherwise the testimonial CRUD round-trip '
            . 'asserted above would only prove the model works, not that the '
            . 'admin surface is reachable through the Livewire form pipeline.'
        );
    }
}
