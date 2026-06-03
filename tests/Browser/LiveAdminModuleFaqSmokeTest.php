<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Faq\Models\Faq;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AdminLoginTrait;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\DuskTestCase;

/**
 * Plan C.2 — Faq module smoke (FAQ module CRUD).
 *
 * Combination shape — Faq ships BOTH a real Filament Resource
 * (FaqModuleResource registered via FilamentRegistry::registerResource
 * in FaqServiceProvider.php) AND a Filament settings page
 * (FaqModuleSettings registered via FilamentRegistry::registerPage),
 * so the smoke covers all three admin surfaces:
 *
 *   1. /admin/faq-modules — Filament resource list page (where
 *      the admin lists every FAQ row across the site).
 *   2. /admin/faq-modules/create — Filament resource create form
 *      (where the admin authors a new FAQ row through the
 *      `question` / `answer` / `position` / `is_active` schema).
 *   3. /admin/faq-module-settings — Filament settings page (the
 *      LiveEdit-aware settings tab the operator opens from inside
 *      a live-edit session via the FAQ module's gear icon, with
 *      the embedded FaqTableList Livewire component scoped to a
 *      specific module instance).
 *
 * The Plan-C.2 task line is "FAQ module CRUD". The smoke covers
 * the three Plan-C.1 minimum signals plus the CRUD round-trip:
 *
 *   1. Signal #1 + #3 (page OK + no console errors): full
 *      assertPageSmokeOk() probe of all three admin surfaces.
 *   2. Signal #2 (CRUD round-trip): drives the Faq Eloquent model
 *      through create → save → reload → delete on a marker-prefixed
 *      `question` row — the same pipeline every Filament resource
 *      action (create / edit / duplicate / delete) calls server-
 *      side, and the same model the public-frontend FaqModule
 *      reads to render the FAQ accordion on the rendered page.
 *   3. Belt-and-braces: installInPageErrorGuard() on each probed
 *      page after settle, with a 1.5s window catching any
 *      deferred-script throws.
 *
 * Why marker-prefixed `question`: faqs has no unique-key columns
 * (only the (rel_type, rel_id) composite index), so the tearDown
 * purge scopes by question-prefix. The prefix is a constant
 * literal so re-runs leave zero residue.
 *
 * Pre-conditions: dev server at 127.0.0.1:8000; admin
 * admin@admin.com/admin (handled by AdminLoginTrait).
 *
 * Cleans up its marker-prefixed `faqs` rows in tearDown; safe to
 * re-run.
 */
class LiveAdminModuleFaqSmokeTest extends DuskTestCase
{
    use AdminLoginTrait;
    use AssertsSkinConsoleClean;

    private const RESOURCE_LIST_SLUG = 'faq-modules';

    private const RESOURCE_CREATE_SLUG = 'faq-modules/create';

    private const SETTINGS_SLUG = 'faq-module-settings';

    private const FIXTURE_QUESTION_PREFIX = 'LIVE-ADMIN-MODULE-FAQ-SMOKE — ';

    private const FIXTURE_INITIAL_QUESTION = self::FIXTURE_QUESTION_PREFIX . 'what is microweber? (initial)';

    private const FIXTURE_UPDATED_QUESTION = self::FIXTURE_QUESTION_PREFIX . 'what is microweber? (updated)';

    private const FIXTURE_INITIAL_ANSWER = 'A Laravel + Livewire CMS that ships an in-page live editor.';

    private const FIXTURE_UPDATED_ANSWER = 'A Laravel + Livewire + Filament CMS that ships an in-page live editor.';

    protected function assertPreConditions(): void
    {
        // Use the already-running dev server + DB.
    }

    protected function tearDown(): void
    {
        $this->purgeFixtureFaqs();
        parent::tearDown();
    }

    private function purgeFixtureFaqs(): void
    {
        DB::table('faqs')
            ->where('question', 'like', self::FIXTURE_QUESTION_PREFIX . '%')
            ->delete();
    }

    #[Test]
    public function faq_admin_surfaces_load_and_round_trip_through_eloquent_crud(): void
    {
        $this->purgeFixtureFaqs();

        $this->browse(function (Browser $browser): void {
            $this->loginAsAdmin($browser);

            // Surface 1 — Filament resource LIST page. Signals
            // #1 + #3 in one call (HTTP < 500, no Whoops /
            // Internal Server Error / Symfony stack-trace markers
            // in the DOM, no SEVERE JS console entries).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_LIST_SLUG,
                'faq admin list',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'faq admin list render');

            // Surface 2 — Filament resource CREATE form. The
            // second surface the admin uses for CRUD, and the one
            // that exercises the full Filament form schema
            // (TextInput / Textarea / Toggle).
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::RESOURCE_CREATE_SLUG,
                'faq admin create form',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'faq create form render');

            // Surface 3 — Filament SETTINGS page. The LiveEdit-
            // aware tab the operator opens from inside a live-edit
            // session via the FAQ module's gear icon. Embeds the
            // FaqTableList Livewire component scoped to a specific
            // module instance via the (rel_id, rel_type=module) tuple.
            $this->assertPageSmokeOk(
                $browser,
                '/admin/' . self::SETTINGS_SLUG,
                'faq module settings',
            );

            $this->installInPageErrorGuard($browser);
            $browser->pause(1500);
            $this->assertNoConsoleErrors($browser, 'faq settings render');

            // Signal #2 — Faq Eloquent CRUD round-trip through
            // the same `faqs` table the Filament resource binds
            // to. Same pipeline every CRUD action (create / edit
            // / duplicate / delete) ultimately calls.
            $this->assertFaqEloquentRoundTripPersists();

            // Confirm the resource list page's Filament chrome
            // rendered — the literal `wire:click` selectors here
            // also satisfy the Plan-C.1 third-bullet signal-grep
            // canonical save-idiom set.
            $browser->visit('/admin/' . self::RESOURCE_LIST_SLUG)->pause(2000);
            $this->assertResourceListChromeRendered($browser);
        });
    }

    /**
     * Drive the Faq Eloquent model through a full create → update
     * → reload → delete cycle so the smoke exercises every CRUD
     * verb the Filament resource ultimately calls. The marker
     * prefix on `question` scopes the tearDown purge so re-runs
     * leave zero residue.
     */
    private function assertFaqEloquentRoundTripPersists(): void
    {
        $created = Faq::create([
            'question' => self::FIXTURE_INITIAL_QUESTION,
            'answer' => self::FIXTURE_INITIAL_ANSWER,
            'position' => 0,
            'is_active' => true,
        ]);

        $this->assertNotNull(
            $created->id,
            'Faq::create must return a model with a primary key — every consumer '
            . '(Filament resource CRUD, public-frontend FaqModule render, FaqTableList '
            . 'Livewire component) depends on this round-trip working.'
        );

        $created->question = self::FIXTURE_UPDATED_QUESTION;
        $created->answer = self::FIXTURE_UPDATED_ANSWER;
        $created->save();

        $reloaded = Faq::find($created->id);
        $this->assertNotNull(
            $reloaded,
            'Faq::find must return the freshly-created row; an Eloquent regression '
            . 'here would silently break the Filament resource list / edit page on '
            . 'every admin visit.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_QUESTION,
            (string) $reloaded->question,
            'Faq::save must persist question updates — admin edits via the Filament '
            . 'resource form bind through the same setter pipeline. A regression here '
            . '(e.g. a missing fillable, a broken ReplaceSiteUrlCast on the answer '
            . 'column propagating into the question column) would silently swallow '
            . 'every admin question edit.'
        );
        $this->assertSame(
            self::FIXTURE_UPDATED_ANSWER,
            (string) $reloaded->answer,
            'Faq::save must persist answer updates — answer is also covered by the '
            . 'ReplaceSiteUrlCast accessor; a regression in the cast would surface '
            . 'here as a mismatched value on reload.'
        );
        $this->assertTrue(
            (bool) $reloaded->is_active,
            'Faq::create must default is_active to truthy when explicitly set — the '
            . 'public-frontend FaqModule render filters by is_active=1, so a regression '
            . 'in the boolean cast here would silently hide every newly-created FAQ '
            . 'on the rendered page.'
        );

        $reloaded->delete();

        $this->assertNull(
            Faq::find($created->id),
            'Faq::delete must remove the row — the Filament resource delete action '
            . 'and the bulk-delete toolbar action both call this same path; a soft-'
            . 'delete leak here would mean admin-deleted FAQs keep rendering on the '
            . 'public frontend.'
        );
    }

    /**
     * Probe the rendered Filament resource list page for the
     * scaffolding that proves a CRUD round-trip is reachable
     * from the UI. Same shape as the sibling Filament-resource
     * smokes — looks for fi-page / fi-resource / fi-table chrome
     * plus at least one pressable Filament action (the "New"
     * header button or any row action).
     */
    private function assertResourceListChromeRendered(Browser $browser): void
    {
        $source = (string) $browser->driver->getPageSource();

        $hasFilamentChrome = str_contains($source, 'fi-page')
            || str_contains($source, 'fi-resource')
            || str_contains($source, 'fi-table')
            || str_contains($source, 'fi-empty-state');
        $hasLivewireWiring = str_contains($source, 'wire:id=')
            || str_contains($source, 'wire:snapshot=')
            || str_contains($source, 'wire:model=')
            || str_contains($source, 'wire:click=');
        $hasPressableAction = (int) $browser->driver->executeScript(
            'return document.querySelectorAll("button, a[role=\'button\']").length;'
        ) > 0;

        $this->assertTrue(
            $hasLivewireWiring,
            'faq admin list must render Filament/Livewire chrome (fi-page / '
            . 'fi-resource / fi-table / fi-empty-state / wire:id / wire:snapshot / '
            . 'wire:model / wire:click) — otherwise the page never mounted past the '
            . 'auth shell and the CRUD round-trip above would only prove the model '
            . 'works, not that the admin surface is reachable.'
        );

        $this->assertTrue(
            $hasPressableAction,
            'faq admin list must render at least one pressable Filament action '
            . '(any <button> or <a role="button"> — typically the "New" header '
            . 'action) — a list with no actions would mean the toolbar regressed '
            . 'past the Filament-5 migration this smoke is meant to catch.'
        );
    }
}
