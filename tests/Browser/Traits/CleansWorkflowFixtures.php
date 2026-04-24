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
 *     snapshots row counts for every table the workflow stages
 *     touch.
 *   - `tearDownCleansWorkflowFixtures` runs the same purge, then
 *     asserts post-teardown counts equal the pre-setUp snapshot —
 *     proving zero residue across the full surface.
 *
 * Tables covered by the snapshot + purge pair (grown through
 * Plan A.3 as each stage landed new writes):
 *   - content, content_data            (Stages 3/4/5/8)
 *   - menus, menus_items               (Stage 3)
 *   - custom_fields, custom_fields_values (Stage 5 product prices)
 *   - media                            (Stage 6 logo)
 *   - options                          (Stages 2/6/7 — non-transient
 *                                       subset; module-layouts-*
 *                                       auto-renders excluded)
 *   - tax_rates                        (Stage 6 currency/tax)
 *   - cart, cart_orders                (Stages 5/8 cart + order)
 *   - users                            (reserved; no stage creates
 *                                       users today, but the snapshot
 *                                       catches any future drift)
 *
 * Markers every workflow row carries so the purger reaches them:
 *   - content.url              → 'workflow-fixture-*' slug
 *   - content.title            → 'workflow-fixture' substring
 *   - menus.title              → 'workflow-fixture' substring
 *   - media.filename / title   → 'workflow-fixture' substring
 *   - options.option_key       → 'workflow_fixture_*' prefix
 *   - tax_rates.name           → 'workflow-fixture' substring
 *   - cart.session_id / title  → 'workflow-fixture' substring
 *   - cart_orders.order_reference_id  → 'workflow-fixture-*'
 *   - cart_orders.email        → '@workflow-fixture.test' domain
 *   - users.email              → '@workflow-fixture.test' domain
 *
 * Non-marker-scoped writes (e.g. save_option on canonical keys like
 * `current_template`, `website_title`, `custom_css`) are handled by
 * per-stage baseline-snapshot + finally{} restore rather than the
 * marker purger, because the canonical option keys must survive
 * across the test. See Stage 2 / Stage 6 / Stage 7 for the pattern.
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
