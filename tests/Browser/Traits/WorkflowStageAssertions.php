<?php

declare(strict_types=1);

namespace Tests\Browser\Traits;

use Closure;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;

/**
 * Codifies Plan A.1 third acceptance bullet: every workflow stage
 * asserts two things, in this order:
 *
 *   1. **DB invariant** — the source-of-truth mutation landed on the
 *      right table with the right shape. If the DB row is wrong,
 *      the stage failed regardless of what the browser shows.
 *   2. **DOM signal** — the rendered page contains the operator-
 *      visible marker that proves the UI reflects the DB state.
 *      DOM-only assertions lie (caches, stale Livewire, skin CSS
 *      hiding a failure) so they are never the primary gate.
 *
 * Usage shape every stage method should follow:
 *
 *   $this->assertStageCompleted(
 *       stageName: 'stage_3_home_page_is_created_with_a_menu_slot',
 *       dbInvariant: fn () =>
 *           DB::table('content')
 *               ->where('url', $slug)
 *               ->where('is_home', 1)
 *               ->exists(),
 *       dbFailureMessage: "Home page content row with url '{$slug}' and is_home=1 missing",
 *       domSignal: fn (Browser $b) =>
 *           str_contains($b->script('return document.body.innerText;')[0] ?? '', 'Home'),
 *       domFailureMessage: "Rendered sidebar should list the newly-created Home page",
 *       browser: $browser,
 *   );
 *
 * A stage method that skips either half fails loudly at the method
 * boundary — see the guard in {@see assertStageCompleted}.
 */
trait WorkflowStageAssertions
{
    /**
     * Run a stage's two-phase assertion. Returns nothing — failure
     * throws through PHPUnit's normal assertion path.
     *
     * @param Closure():bool $dbInvariant
     *   Closure that performs the DB-level assertion and returns
     *   true on success. Runs first; a false return short-circuits
     *   the DOM probe (no point checking the UI when the DB is
     *   already wrong).
     *
     * @param Closure(Browser):bool $domSignal
     *   Closure that inspects the live browser state and returns
     *   true when the operator-visible marker is present.
     */
    protected function assertStageCompleted(
        string $stageName,
        Closure $dbInvariant,
        string $dbFailureMessage,
        Closure $domSignal,
        string $domFailureMessage,
        Browser $browser,
    ): void {
        $dbOk = (bool) $dbInvariant();
        $this->assertTrue(
            $dbOk,
            sprintf(
                "[%s] DB invariant failed — %s. The DB is the source of truth for workflow stages; a failing DB check means the stage's mutation never landed, regardless of what the browser may be showing.",
                $stageName,
                $dbFailureMessage,
            ),
        );

        $domOk = (bool) $domSignal($browser);
        $this->assertTrue(
            $domOk,
            sprintf(
                "[%s] DOM signal failed — %s. The DB invariant PASSED, so this is a UI-only regression: the data landed but the operator cannot see it.",
                $stageName,
                $domFailureMessage,
            ),
        );
    }

    /**
     * Convenience predicate — `true` when a row exists on the given
     * table matching all of `$where` clauses. Designed to fit inside
     * the `$dbInvariant` closure without extra ceremony.
     *
     * @param array<string, mixed> $where
     */
    protected function workflowRowExists(string $table, array $where): bool
    {
        $query = DB::table($table);
        foreach ($where as $col => $value) {
            $query->where($col, $value);
        }
        return $query->exists();
    }

    /**
     * Convenience predicate — `true` when the browser's body text
     * contains `$needle`. Case-sensitive on purpose (titles and
     * slugs must match exactly).
     */
    protected function workflowBodyContains(Browser $browser, string $needle): bool
    {
        $text = $browser->script('return document.body ? document.body.innerText : "";')[0] ?? '';
        return str_contains((string) $text, $needle);
    }
}
