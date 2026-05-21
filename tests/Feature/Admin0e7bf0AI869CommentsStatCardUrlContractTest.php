<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-21-0e7bf0 / AI-869 — Dashboard "Last comments" stat
 * card broken link. P2 Bug.
 * Jira: https://microweber.atlassian.net/browse/AI-869
 *
 * Pre-fix: DashboardQuickStatsWidget stat card URL was set to
 * `/admin/settings/comments` (returns HTTP 404). This was introduced
 * by AI-738 (task-2026-05-17-18be49) when the label was updated to
 * "Last comments (30 days)". The welcome sub-line already correctly
 * linked to `/admin/comments` (HTTP 200).
 *
 * Fix: one-line URL correction in
 * `app/Filament/Admin/Widgets/DashboardQuickStatsWidget.php`
 * from `mw_admin_prefix_url() . '/settings/comments'`
 *   to `mw_admin_prefix_url() . '/comments'`.
 *
 * Note: "Comments Settings" link on the comments list page correctly
 * points to `/admin/comments-module-settings-admin` — that route is
 * valid and separate from this fix.
 */
class Admin0e7bf0AI869CommentsStatCardUrlContractTest extends TestCase
{
    private string $source;
    private string $stripped;

    protected function setUp(): void
    {
        parent::setUp();
        $this->source = (string) file_get_contents(
            base_path('app/Filament/Admin/Widgets/DashboardQuickStatsWidget.php')
        );
        // Strip PHP block comments + line comments (selector-self-match guard).
        $this->stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $this->source) ?? $this->source;
        $this->stripped = preg_replace('~//[^\n]*~', '', $this->stripped) ?? $this->stripped;
    }

    #[Test]
    public function comments_stat_url_uses_admin_comments_route(): void
    {
        $this->assertMatchesRegularExpression(
            "~mw_admin_prefix_url\(\)\s*\.\s*'/comments'~",
            $this->stripped,
            "DashboardQuickStatsWidget comments stat must link to mw_admin_prefix_url() . '/comments' (HTTP 200)."
        );
    }

    #[Test]
    public function broken_settings_comments_url_absent(): void
    {
        $this->assertStringNotContainsString(
            '/settings/comments',
            $this->stripped,
            "DashboardQuickStatsWidget must NOT link to '/settings/comments' — that route is a 404."
        );
    }

    #[Test]
    public function label_still_carries_30_day_scope(): void
    {
        $this->assertStringContainsString(
            'Last comments (30 days)',
            $this->source,
            'Comments stat label must still read "Last comments (30 days)" (AI-738 label preserved).'
        );
    }

    #[Test]
    public function task_id_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-21-0e7bf0',
            $this->source,
            'DashboardQuickStatsWidget must carry task-0e7bf0 marker.'
        );
    }
}
