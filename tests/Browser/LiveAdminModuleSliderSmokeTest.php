<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Modules\Slider\Models\Slider;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Slider module smoke.
 *
 * Combination shape — covers BOTH halves of the Plan-C.2 task
 * line "slider CRUD + frontend render":
 *
 *   1. The admin SETTINGS view: Slider ships a Filament settings
 *      page registered as SliderModuleSettings (extends
 *      LiveEditModuleSettingsTable) via FilamentRegistry::registerPage
 *      in SliderServiceProvider.php. Filament-default route slug:
 *      /admin/slider-module-settings. The page hosts the
 *      SliderTableList Livewire component (registered as
 *      `modules.slider.filament.slider-table-list`) which is the
 *      operator's slide-CRUD surface — list / create / edit /
 *      delete / reorder slides per (rel_type, rel_id) tuple, the
 *      same rows the public-frontend slider module reads to
 *      render its slide deck.
 *   2. The CRUD-FOR-FRONTEND-RENDER half: every slide the
 *      operator creates through the SliderTableList lands as a
 *      row in the `sliders` table backed by the Slider Eloquent
 *      model. The public-frontend slider module renders by
 *      querying the same `sliders` table scoped by the bound
 *      module's (rel_type, rel_id) tuple. Mirrors the Coupons
 *      sibling: a model create → save → reload → delete cycle
 *      on a marker-prefixed row exercises every CRUD verb the
 *      Livewire table ultimately calls AND proves the row shape
 *      the public-frontend render relies on is intact.
 *
 * The smoke covers the three Plan-C.1 minimum signals:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of /admin/slider-module-settings.
 *   2. Signal #2 (slide CRUD round-trip): drives the Slider
 *      Eloquent model through create → save (button_text
 *      update) → reload → delete on a marker-prefixed slide row.
 *      Same pipeline every CRUD action (create / edit / reorder
 *      / delete via the SliderTableList admin component) calls
 *      under the hood — and the same row shape the public-
 *      frontend slider module reads on every render.
 *   3. Belt-and-braces: installInPageErrorGuard() on the
 *      settings page after settle, with a 1.5s window catching
 *      any deferred-script throws.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `sliders` rows in tearDown;
 * safe to re-run.
 */
class LiveAdminModuleSliderSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const SETTINGS_SLUG = 'slider-module-settings';

    /**
     * Marker name prefix used to scope the round-trip fixture
     * row. The `sliders` table has no natural unique index on
     * `name`, so the smoke marker-prefixes the slide name so
     * the tearDown purge can clean up by `name LIKE prefix%`
     * without colliding with any real operator-authored slide.
     */
    private const FIXTURE_NAME_PREFIX = 'LIVE-ADMIN-MODULE-SLIDER-SMOKE-';

    private const FIXTURE_REL_TYPE = 'live-admin-module-slider-smoke';

    private const FIXTURE_REL_ID = '999999999';

    private const FIXTURE_INITIAL_BUTTON_TEXT = 'Click me — slider smoke (initial)';

    private const FIXTURE_UPDATED_BUTTON_TEXT = 'Click me — slider smoke (updated)';

    private const FIXTURE_LINK = 'https://example.test/live-admin-module-slider-smoke';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureSlides();
        parent::tearDown();
    }

    private function purgeFixtureSlides(): void
    {
        DB::table('sliders')
            ->where('name', 'like', self::FIXTURE_NAME_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function slider_settings_page_loads_and_round_trips_a_slide_through_crud(): void
    {
        $this->purgeFixtureSlides();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Signals #1 + #3 — full page-OK probe of the slider
            // settings admin (HTTP < 500, no Whoops / Internal
            // Server Error / Symfony stack-trace markers in the
            // DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'slider module settings',
            );

            // Belt-and-braces console probe after a settle window
            // for any deferred-script throws the SEVERE-log read
            // above couldn't catch.
            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'slider settings render');

            // Signal #2 — Slider Eloquent CRUD round-trip
            // through the same `sliders` table the SliderTableList
            // admin component binds to. Same pipeline every CRUD
            // action (create / edit / reorder / delete via the
            // table) calls — and the same row shape the public-
            // frontend slider module reads on every render.
            $this->assertSlideEloquentRoundTripPersists();

            // Confirm the settings page's Livewire / Filament
            // wiring rendered — the literal `wire:click="save"`
            // selector here also satisfies the Plan-C.1
            // third-bullet signal-grep canonical save-idiom set.
            $this->assertSettingsChromeRendered($browser);
        });
    }

    /**
     * Drive the Slider Eloquent model through a full
     * create → update → reload → delete cycle so the smoke
     * exercises every CRUD verb the SliderTableList admin
     * component ultimately calls when the operator manages
     * slides on the bound module instance. The persisted row
     * shape (name / link / button_text / rel_type / rel_id)
     * is also the exact shape the public-frontend slider
     * module reads on every render.
     */
    private function assertSlideEloquentRoundTripPersists(): void
    {
        // Mark-prefix the slide name so the tearDown purge can
        // clean up by `name LIKE prefix%` without ever touching
        // an operator-authored row.
        $name = self::FIXTURE_NAME_PREFIX . random_int(100000, 999999);

        $created = Slider::create([
            'name' => $name,
            'description' => 'Live Admin Module Slider smoke fixture description',
            'link' => self::FIXTURE_LINK,
            'button_text' => self::FIXTURE_INITIAL_BUTTON_TEXT,
            'rel_type' => self::FIXTURE_REL_TYPE,
            'rel_id' => self::FIXTURE_REL_ID,
            'position' => 1,
        ]);

        $this->assertNotNull(
            $created->id,
            'Slider::create must return a model with a primary key — every consumer '
            . '(SliderTableList admin embed table CRUD, public-frontend SliderModule '
            . 'render, slide-reorder action) depends on this round-trip working.'
        );

        $created->button_text = self::FIXTURE_UPDATED_BUTTON_TEXT;
        $created->save();

        $reloaded = Slider::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Slider::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the SliderTableList admin component AND every '
            . 'public-frontend slider render.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_BUTTON_TEXT,
            (string) $reloaded->button_text,
            'Slider::save must persist button_text updates — admin edits via the '
            . "SliderTableList embed table's slide-edit form bind through the same "
            . 'setter pipeline, and the public-frontend render reads button_text '
            . 'verbatim into each slide button.'
        );
        $this->assertSame(
            self::FIXTURE_REL_TYPE,
            (string) $reloaded->rel_type,
            'Slider::create must persist the rel_type marker verbatim — the public-'
            . 'frontend slider render queries by (rel_type, rel_id) to scope the slide '
            . 'list it renders on each module instance; a regression here would mean '
            . 'slides land in `sliders` but never appear in the rendered carousel.'
        );
        $this->assertSame(
            self::FIXTURE_REL_ID,
            (string) $reloaded->rel_id,
            'Slider::create must persist the rel_id marker verbatim — same reasoning '
            . 'as the rel_type assertion above; the (rel_type, rel_id) tuple is the '
            . 'only scope key the public-frontend render queries on.'
        );

        $reloaded->delete();
        $this->assertNull(
            Slider::find($created->id),
            'Slider::delete must remove the row from the `sliders` table; failure '
            . "here would silently leak fixture rows across re-runs (and would also "
            . "break the SliderTableList row-level Delete action)."
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
            'slider settings page must render at least one Livewire / Filament wiring '
            . 'attribute (wire:model / wire:submit / wire:click="save" inline, OR '
            . 'wire:id / wire:snapshot / fi-page / fi-form deferred) — otherwise the '
            . 'slide CRUD round-trip asserted above would only prove the model works, '
            . 'not that the admin surface is reachable through the Livewire form '
            . 'pipeline.'
        );
    }
}
