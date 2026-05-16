<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-6cb0d8 / AI-703 CHANGE — localStorage bridge
 * observer target moved from body to .fi-sidebar. Jira:
 *   https://microweber.atlassian.net/browse/AI-703
 *
 * Designer verified original AI-703 ship (task-29342d) at desktop
 * 1440 + overlay 900 + mobile 390 + dark — 6 of 7 acceptance
 * points passed but the localStorage bridge was stuck:
 *   Step                         | localStorage value
 *   ---------------------------- | -----------------------
 *   Initial                      | "collapsed" (stuck)
 *   Click collapse → 128px       | "collapsed" (no update)
 *   Click open → 240px           | "collapsed" (no update)
 *
 * Root cause: Filament v5 does NOT toggle `fi-sidebar-collapsed-
 * on-desktop` or `fi-sidebar-open` on body in this build. The
 * sidebar state lives in Alpine `$store('sidebar').isOpen` and
 * the `.fi-sidebar` element itself carries `fi-sidebar-open` via
 * `x-bind:class="{ 'fi-sidebar-open': $store.sidebar.isOpen }"`
 * (vendor/filament/filament/resources/views/livewire/sidebar.
 * blade.php line 19). Body class watching was the wrong target.
 *
 * Fix per designer's Option A: observe `.fi-sidebar` element's
 * class list instead. Three-state mapping preserved:
 *   - 'pinned'    (.fi-sidebar.fi-sidebar-open AND viewport ≥ 1024 px)
 *   - 'rail'      (.fi-sidebar present, NOT .fi-sidebar-open AND ≥ 1024 px)
 *   - 'collapsed' (< 1024 px OR no .fi-sidebar)
 * Window resize fires writeMode so transitions across 1024 px
 * update the stored value.
 */
class Admin6cb0d8AI703ChangeBridgeObserverContractTest extends TestCase
{
    private string $src;

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'src/MicroweberPackages/Admin/Filament/FilamentAdminPanelProvider.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — observer target moved from body to .fi-sidebar
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function observer_attaches_to_fi_sidebar_element_not_body(): void
    {
        // attachObserver() reads getSidebar() and observes the
        // sidebar element directly.
        $this->assertMatchesRegularExpression(
            '/attachObserver\s*\(\s*\)\s*\{[\s\S]*?getSidebar\(\)[\s\S]*?observer\.observe\(\s*sidebar/',
            $this->src,
            'attachObserver() must observe the sidebar element returned by getSidebar(), not body.'
        );
    }

    #[Test]
    public function observer_does_not_attach_to_body(): void
    {
        // Strip comments before scanning so the migration-rationale
        // docblock (which legitimately quotes "observe body") doesn't
        // false-match. LESSONS selector-self-match guard reapplied.
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->src);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        // The body element must not appear as an observer target
        // in the AI-703 bridge code path.
        $this->assertDoesNotMatchRegularExpression(
            '/observer\.observe\(\s*body\s*,/',
            $rules,
            'Post-CHANGE: observer.observe(body, …) is gone — body class watching was the wrong target.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — getSidebar() helper + DESKTOP_PX tiebreaker
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function get_sidebar_uses_query_selector_for_fi_sidebar(): void
    {
        $this->assertMatchesRegularExpression(
            "/function\\s+getSidebar\\s*\\(\\s*\\)\\s*\\{[\\s\\S]*?return\\s+document\\.querySelector\\(\\s*'\\.fi-sidebar'\\s*\\)/",
            $this->src,
            "getSidebar() must return document.querySelector('.fi-sidebar')."
        );
    }

    #[Test]
    public function read_mode_distinguishes_rail_via_desktop_breakpoint(): void
    {
        // The new readMode() returns 'collapsed' when viewport <
        // DESKTOP_PX (1024) regardless of sidebar state. Pin the
        // breakpoint + the readMode logic.
        $this->assertMatchesRegularExpression(
            "/DESKTOP_PX\\s*=\\s*1024/",
            $this->src,
            'DESKTOP_PX constant must equal 1024 — the rail/collapsed breakpoint.'
        );
        $this->assertMatchesRegularExpression(
            '/window\.innerWidth\s*>=\s*DESKTOP_PX/',
            $this->src,
            'readMode() must compare window.innerWidth >= DESKTOP_PX to gate the rail branch.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — resilience: retry attach + resize listener
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function attach_observer_retries_until_fi_sidebar_renders(): void
    {
        // Livewire hydration order means .fi-sidebar may render
        // AFTER the BODY_END script runs. The retry loop keeps
        // trying until success or 20 attempts (~2 seconds).
        $this->assertMatchesRegularExpression(
            '/setInterval[\s\S]*?attachObserver\(\)\s*\|\|\s*\+\+tries\s*>\s*20/',
            $this->src,
            'attachObserver must retry every 100ms up to 20 tries while .fi-sidebar has not rendered yet.'
        );
    }

    #[Test]
    public function resize_listener_re_syncs_mode_on_breakpoint_crossings(): void
    {
        // Window resize must re-fire writeMode so transitions
        // across the 1024 px breakpoint correctly update the
        // stored mode (rail ↔ collapsed when viewport resizes).
        $this->assertMatchesRegularExpression(
            "/window\\.addEventListener\\(\\s*'resize'\\s*,\\s*writeMode\\s*\\)/",
            $this->src,
            'window.resize listener must call writeMode so breakpoint crossings update the stored mode.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — three-state mapping preserved
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function three_canonical_mode_values_still_returned(): void
    {
        foreach (['pinned', 'rail', 'collapsed'] as $mode) {
            $this->assertMatchesRegularExpression(
                "/return\\s+'{$mode}'/",
                $this->src,
                "Three-state mapping preserved: bridge must still return '{$mode}' as one of the localStorage values."
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_change_markers_pinned(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-17-6cb0d8',
            $this->src,
            'AI-703 CHANGE task-id marker must be present.'
        );
        $this->assertStringContainsString(
            'AI-703 CHANGE',
            $this->src,
            'Comment must explicitly cite "AI-703 CHANGE" so the audit chain is grep-able.'
        );
        // Original ship task ID preserved for audit chain.
        $this->assertStringContainsString(
            'task-2026-05-16-29342d',
            $this->src,
            'Original AI-703 ship task-id must remain in the comment for the audit chain (new → old continuity).'
        );
    }
}
