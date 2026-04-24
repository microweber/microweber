<?php

declare(strict_types=1);

namespace Tests\Browser\Traits;

use Tests\Browser\Support\WorkflowFixturePurger;

/**
 * Auto-cleanup for the full-website-creation workflow Dusk test.
 *
 * Composes with {@see \Tests\Browser\LiveAdminFullWebsiteCreationWorkflowTest}
 * and any later tests that adopt the `workflow-fixture-*` marker
 * convention (see {@see WorkflowFixturePurger::FIXTURE_MARKER}).
 *
 * Lifecycle:
 *   - `setUpCleansWorkflowFixtures` runs a pre-purge so a failed
 *     prior run can't leak state into the current one, then
 *     snapshots row counts for the tables the test touches.
 *   - `tearDownCleansWorkflowFixtures` runs the same purge, then
 *     asserts post-teardown counts equal the pre-setUp snapshot —
 *     proving zero residue across `content`, `content_data`,
 *     `media`, `options`, `users`, and `menus`.
 *
 * Hook naming: Laravel's `InteractsWithTestCaseLifecycle` auto-wires
 * `setUp{TraitBasename}` and `tearDown{TraitBasename}` as
 * before-application-boot / before-application-destroyed callbacks,
 * which fire with the container still alive — strictly better than
 * `#[Before]` / `#[After]` for DB-touching work.
 */
trait CleansWorkflowFixtures
{
    /** @var array<string, int>|null */
    protected ?array $workflowFixtureBaselineCounts = null;

    protected function setUpCleansWorkflowFixtures(): void
    {
        // Pre-purge — a previous failed run might have left rows
        // behind; snapshot AFTER pre-purge so the baseline reflects
        // the install's real steady state.
        WorkflowFixturePurger::purge();
        $this->workflowFixtureBaselineCounts = WorkflowFixturePurger::snapshotCounts();
    }

    protected function tearDownCleansWorkflowFixtures(): void
    {
        WorkflowFixturePurger::purge();

        $after = WorkflowFixturePurger::snapshotCounts();
        $before = $this->workflowFixtureBaselineCounts ?? $after;

        foreach ($before as $table => $baselineCount) {
            $postCount = $after[$table] ?? $baselineCount;
            if ($postCount === $baselineCount) {
                continue;
            }

            $delta = $postCount - $baselineCount;
            $this->fail(sprintf(
                'Workflow fixture leak — `%s` row count drifted by %+d after tearDown (baseline=%d, after=%d). '
                . 'Every row this test creates must carry a `%s`-style marker so WorkflowFixturePurger can reach it.',
                $table,
                $delta,
                $baselineCount,
                $postCount,
                WorkflowFixturePurger::FIXTURE_MARKER,
            ));
        }
    }
}
