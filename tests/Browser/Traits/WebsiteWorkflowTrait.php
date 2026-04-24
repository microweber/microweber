<?php

declare(strict_types=1);

namespace Tests\Browser\Traits;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\Browser\Support\WorkflowFixturePurger;

/**
 * Stage-scoped helpers for the full-website-creation Dusk workflow.
 *
 * The workflow test walks through eight stages (Plan A.3 in
 * TODO.md) — create, pick template, add pages, drop a layout, add
 * a shop, configure settings, apply a palette, publish. Each stage
 * needs the same supporting primitives: seed a fixture-marked row,
 * visit a canvas page in admin mode, visit the same page as a
 * public guest, assert a DB + DOM outcome, and compose with the
 * other workflow traits (env resolver, stage assertions, purger).
 *
 * Extracting those primitives into a trait keeps the big test
 * file dominated by stage intent, not plumbing. A stage method
 * becomes:
 *
 *   $pageId = $this->seedWorkflowPage('home', [...]);
 *   $this->browse(function (Browser $b) use ($pageId) {
 *       $this->visitAsOperator($b, '/admin/pages');
 *       // ... drive the form ...
 *       $this->assertStageOutcome($b, 'stage_3_home_page_is_created', ...);
 *   });
 *
 * All methods here preserve the Plan A.1 contract — every row
 * created carries a {@see WorkflowFixturePurger::FIXTURE_MARKER}
 * marker so the tearDown purger can reach it.
 *
 * Depends on the sibling traits being composed on the same test
 * class: {@see AdminLoginTrait}, {@see ResolvesWorkflowEnvironment},
 * {@see WorkflowStageAssertions}, {@see CleansWorkflowFixtures}.
 */
trait WebsiteWorkflowTrait
{
    /**
     * Seed a `content` row with a workflow-fixture marker on its
     * slug + title so the tearDown purger always reaches it.
     * Returns the new content id. Extra columns override the
     * sensible defaults (`content_type=page`, `subtype=static`,
     * `is_active=1`).
     *
     * @param array<string, mixed> $overrides
     */
    protected function seedWorkflowPage(string $localSlugSuffix, array $overrides = []): int
    {
        $slug = WorkflowFixturePurger::FIXTURE_MARKER . '-' . ltrim($localSlugSuffix, '-');

        $defaults = [
            'title' => 'Workflow page — ' . WorkflowFixturePurger::FIXTURE_MARKER . ' — ' . $localSlugSuffix,
            'content_type' => 'page',
            'subtype' => 'static',
            'url' => $slug,
            'is_active' => 1,
            'is_home' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return (int) DB::table('content')->insertGetId(array_replace($defaults, $overrides));
    }

    /**
     * Visit a path as the authenticated admin. Logs in on first
     * call and reuses the cached session. Pauses long enough for
     * Filament/Livewire hydration to land.
     */
    protected function visitAsOperator(Browser $browser, string $path, int $pauseMs = 3000): Browser
    {
        $this->loginAsAdmin($browser);
        $browser->visit($path)->pause($pauseMs);
        $this->ensureLoggedIn($browser);
        return $browser;
    }

    /**
     * Drop the admin session and visit the given public path as a
     * guest. Use for Stage 8's "operator logs out, checks the
     * published site" assertions.
     */
    protected function visitAsPublicGuest(Browser $browser, string $path, int $pauseMs = 2000): Browser
    {
        // Clearing cookies is the cheapest way to invalidate the
        // admin session without fighting Dusk's session cache.
        try {
            $browser->driver->manage()->deleteAllCookies();
        } catch (\Throwable) {
            // Headless Chrome occasionally rejects cookie deletion
            // between page loads; swallow and rely on a fresh visit.
        }

        // Invalidate the shared login cache on DuskTestCase so any
        // subsequent `visitAsOperator` re-logs in cleanly.
        \Tests\DuskTestCase::$adminLoggedIn = false;

        $browser->visit($path)->pause($pauseMs);
        return $browser;
    }

    /**
     * Short-circuit helper that wraps {@see assertStageCompleted}
     * for the common case: a row-exists DB invariant plus a
     * body-text-contains DOM signal.
     *
     * For stages that need richer assertions (multiple DB columns,
     * computed CSS values), call {@see assertStageCompleted}
     * directly with custom closures.
     *
     * @param array<string, mixed> $whereRow
     */
    protected function assertStageOutcome(
        Browser $browser,
        string $stageName,
        string $table,
        array $whereRow,
        string $expectInDom,
    ): void {
        $this->assertStageCompleted(
            stageName: $stageName,
            dbInvariant: fn (): bool => $this->workflowRowExists($table, $whereRow),
            dbFailureMessage: sprintf(
                "`%s` row matching %s was not persisted",
                $table,
                json_encode($whereRow, JSON_UNESCAPED_SLASHES),
            ),
            domSignal: fn (Browser $b): bool => $this->workflowBodyContains($b, $expectInDom)
                || str_contains((string) ($b->script('return document.title;')[0] ?? ''), $expectInDom),
            domFailureMessage: "Rendered page must contain '{$expectInDom}' in body text or <title>",
            browser: $browser,
        );
    }

    /**
     * True when the page source is clean — no 500, no Whoops. Used
     * at every stage's admin-page entry point to surface server
     * errors before the stage's real assertions run (a 500 masked
     * inside a Filament `<livewire:...>` component shows up as a
     * DB check failure several lines later, which is a much worse
     * signal than this one-liner).
     */
    protected function workflowPageRenderedCleanly(Browser $browser): bool
    {
        $source = $browser->driver->getPageSource();
        return ! str_contains($source, 'Internal Server Error')
            && ! str_contains($source, 'Whoops');
    }
}
