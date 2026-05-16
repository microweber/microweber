<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-18be49 / AI-738 — Dashboard "Last comments 135"
 * stat orphaned. Jira:
 *   https://microweber.atlassian.net/browse/AI-738
 *
 * Designer dispatch 2026-05-16T15:41:31 — dashboard stat with no
 * drill-down + misleading time scope.
 *
 * Phase 1 (this ship):
 *   - Wrap link to /admin/settings/comments (the Comments settings
 *     destination) instead of the orphaned /admin/comments.
 *   - Time-scope the label "Last comments (30 days)".
 *   - Time-scope the underlying query (created_at >= now()-30d) so
 *     the label is truthful.
 *   - Add hover affordance on the card so the click signal reads
 *     even though the card was already an <a> wrapper.
 *
 * Phase 2 (deferred per dispatch): cascade scope-narrowing to
 * Emails / Sales / Recent Orders — designer didn't verify each
 * destination yet, flagged as Phase 2.
 */
class Admin18be49AI738DashboardCommentsStatContractTest extends TestCase
{
    private string $widgetSrc;
    private string $css;
    private string $bladeSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->widgetSrc = (string) file_get_contents(base_path(
            'app/Filament/Admin/Widgets/DashboardQuickStatsWidget.php'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        ));
        $this->bladeSrc = (string) file_get_contents(base_path(
            'resources/views/filament/admin/widgets/dashboard-quick-stats-widget.blade.php'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — destination URL + label change
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function comments_stat_routes_to_settings_comments(): void
    {
        // Designer dispatch: <a href="/admin/settings/comments">
        $this->assertMatchesRegularExpression(
            "/mw_admin_prefix_url\\(\\)\\s*\\.\\s*['\"]\\/settings\\/comments['\"]/",
            $this->widgetSrc,
            'Last comments URL must point to /admin/settings/comments per AI-738 dispatch.'
        );
    }

    #[Test]
    public function legacy_orphaned_comments_url_is_gone(): void
    {
        // Strip block + line comments before scanning so the
        // migration-rationale comment that legitimately mentions
        // the old URL doesn't false-match.
        $rules = preg_replace('/\/\*.*?\*\//s', '', $this->widgetSrc);
        $rules = preg_replace('/\/\/.*$/m', '', $rules);
        // Old URL = /comments (no /settings/ prefix).
        $this->assertDoesNotMatchRegularExpression(
            "/mw_admin_prefix_url\\(\\)\\s*\\.\\s*['\"]\\/comments['\"]/",
            $rules,
            'Legacy /admin/comments URL must no longer appear in the widget rendered output.'
        );
    }

    #[Test]
    public function label_carries_30_day_scope_suffix(): void
    {
        $this->assertMatchesRegularExpression(
            "/'label'\\s*=>\\s*['\"]Last comments \\(30 days\\)['\"]/",
            $this->widgetSrc,
            "Label must read \"Last comments (30 days)\" per AI-738 — time-scope suffix matches the underlying query window."
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — query time-scoped to last 30 days
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function comments_count_query_uses_30_day_window(): void
    {
        $this->assertMatchesRegularExpression(
            "/DB::table\\(\\s*['\"]comments['\"]\\s*\\)\\s*[\\s\\S]*?->where\\(\\s*['\"]created_at['\"]\\s*,\\s*['\"]>=['\"]\\s*,\\s*now\\(\\)->subDays\\(30\\)\\s*\\)/",
            $this->widgetSrc,
            'getCommentsCount() must filter comments created_at >= now()->subDays(30) so the count matches the label.'
        );
    }

    #[Test]
    public function comments_count_query_preserves_exception_handler(): void
    {
        // Defensive — if the comments table doesn't exist (multi-
        // tenant edge case), the original try/catch returns '0'.
        // Must remain.
        $this->assertMatchesRegularExpression(
            '/getCommentsCount[\s\S]*?try\s*\{[\s\S]*?\}\s*catch\s*\(\\\\?Throwable[^)]*\)\s*\{[\s\S]*?return\s+[\'"]0[\'"]/',
            $this->widgetSrc,
            'getCommentsCount() must retain try/catch fallback returning "0" when DB query fails.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — hover affordance CSS
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function hover_affordance_css_lifts_and_shadows_card(): void
    {
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stat-card:hover[^{]*\{[^}]*transform:\s*translateY\(-1px\)/i',
            $this->css,
            'Card :hover must apply translateY(-1px) lift per AI-738 affordance fix.'
        );
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stat-card:hover[^{]*\{[^}]*box-shadow:/i',
            $this->css,
            'Card :hover must apply a box-shadow signalling click affordance.'
        );
    }

    #[Test]
    public function hover_affordance_css_has_focus_visible_outline(): void
    {
        // Keyboard parity per WCAG 2.4.7.
        $this->assertMatchesRegularExpression(
            '/body\.fi-panel-admin\s+\.mw-quick-stat-card:focus-visible\s*\{[^}]*outline:\s*2px\s+solid\s+var\(--ese-accent/i',
            $this->css,
            ':focus-visible must apply a 2 px solid --ese-accent outline for keyboard parity (WCAG 2.4.7).'
        );
    }

    #[Test]
    public function hover_affordance_respects_reduced_motion(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*prefers-reduced-motion:\s*reduce\s*\)\s*\{[^}]*\.mw-quick-stat-card[^}]*transition:\s*none/i',
            $this->css,
            'prefers-reduced-motion must disable the hover transition.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — back-compat + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function blade_view_still_wraps_in_anchor(): void
    {
        // Regression guard: the Blade view's <a href="..."> wrap
        // must remain (audit's "non-clickable" finding was about
        // the lack of hover affordance, not the missing link).
        $this->assertMatchesRegularExpression(
            '/<a\s+href="\{\{\s*\$stat\[\'url\'\]\s*\}\}"\s+class="mw-quick-stat-card/',
            $this->bladeSrc,
            'Stat card must still wrap in <a href> for navigation.'
        );
    }

    #[Test]
    public function task_id_and_ai738_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-18be49', $this->widgetSrc);
        $this->assertStringContainsString('AI-738', $this->widgetSrc);
        $this->assertStringContainsString('task-2026-05-17-18be49', $this->css);
        $this->assertStringContainsString('AI-738', $this->css);
    }
}
