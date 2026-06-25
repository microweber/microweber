<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * task-2026-05-22-77c486 / AI-NEW (from dashboard mobile audit)
 *
 * Agent-test runtime audit at 390×844 found 7 element categories below the
 * WCAG 2.5.5 44×44px minimum on the admin dashboard. All fixes land in
 * mobile-touch.css scoped to body.fi-panel-admin, imported AFTER
 * general-styles.css so !important declarations win the cascade.
 *
 * FAIL elements fixed:
 *   1. Brand mark link .mw-admin-brand-mark         (was 147×36px)
 *   2. Live Edit button .admin-toolbar-live-edit     (was 112×40px)
 *   3. Sidebar items .fi-sidebar-item-btn            (was 40×44px — width only)
 *   4. Sidebar dropdown triggers (fi-sidebar-group-*)(was 40×40px)
 *   5. Welcome widget counters .mw-welcome-widget-counter (was 79-110px × 20px)
 *   6. "View more" link .mw-stats-card-show-more     (was 61×15px)
 *
 * Not fixed (by design): .admin-toolbar-add (+Add button) is hidden at
 * ≤768px via general-styles.css `display: none !important` (AI-704 mobile
 * decision — route is exposed via sidebar nav on mobile).
 */
class Admin77c486DashboardMobileTouchTargetContractTest extends TestCase
{
    private string $css;
    private string $generalStyles;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css')
        );
        $this->generalStyles = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css')
        );
        $this->bundle = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css')
        );
    }

    // ─── §1 Brand mark ───────────────────────────────────────────────────────

    #[Test]
    public function brand_mark_link_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin[^{]*mw-admin-brand-mark[^{]*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->css,
            '.mw-admin-brand-mark must have min-height: 44px !important to overcome the height:100% collapse at mobile.'
        );
    }

    #[Test]
    public function brand_mark_min_height_in_bundle(): void
    {
        $this->assertStringContainsString(
            'body.fi-panel-admin .fi-topbar a.mw-admin-brand-mark',
            $this->bundle,
            'Brand mark rule must be present in the served bundle.'
        );
    }

    // ─── §2 Live Edit button ─────────────────────────────────────────────────

    #[Test]
    public function live_edit_button_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin[^{]*admin-toolbar-live-edit[^{]*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->css,
            '.admin-toolbar-live-edit must get min-height: 44px !important (was 40px — 4px below threshold).'
        );
    }

    #[Test]
    public function add_button_remains_hidden_on_mobile(): void
    {
        // AI-1254 / task-2026-06-08-addmobile SUPERSEDES the AI-704 mobile-hide:
        // +Add is the primary v2 LEFT action, so it now STAYS VISIBLE on mobile,
        // lifted to the WCAG 2.5.5 44px touch-target floor. Guard the new rule.
        $this->assertMatchesRegularExpression(
            '~@media\s*\(max-width:\s*768px\)\s*\{[\s\S]*?\.admin-toolbar-add[\s\S]*?\{[^}]*display:\s*inline-flex\s*!important[\s\S]*?min-height:\s*44px\s*!important~s',
            $this->generalStyles,
            '.admin-toolbar-add must stay visible at 44px on mobile (AI-1254 supersedes the AI-704 hide).'
        );
    }

    // ─── §3 Sidebar items ────────────────────────────────────────────────────

    #[Test]
    public function sidebar_item_btn_gets_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-sidebar-item-btn\s*\{[^}]*min-width:\s*44px\s*!important~s',
            $this->css,
            '.fi-sidebar-item-btn must have min-width: 44px !important (was 40px in collapsed rail mode).'
        );
    }

    #[Test]
    public function sidebar_item_btn_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-sidebar-item-btn\s*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->css,
            '.fi-sidebar-item-btn must have min-height: 44px !important.'
        );
    }

    // ─── §4 Sidebar dropdown triggers ────────────────────────────────────────

    #[Test]
    public function sidebar_group_buttons_get_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin[^{]*fi-sidebar-group-btn[^{]*\{[^}]*min-width:\s*44px\s*!important~s',
            $this->css,
            '.fi-sidebar-group-btn (and variants) must have min-width: 44px !important (was 40px).'
        );
    }

    #[Test]
    public function sidebar_collapse_btn_covered(): void
    {
        $this->assertStringContainsString(
            '.fi-sidebar-group-collapse-btn',
            $this->css,
            'fi-sidebar-group-collapse-btn must be included in the sidebar dropdown trigger fix.'
        );
    }

    #[Test]
    public function sidebar_dropdown_trigger_btn_covered(): void
    {
        $this->assertStringContainsString(
            '.fi-sidebar-group-dropdown-trigger-btn',
            $this->css,
            'fi-sidebar-group-dropdown-trigger-btn must be included in the sidebar dropdown trigger fix.'
        );
    }

    // ─── §5 Welcome widget counters ──────────────────────────────────────────

    #[Test]
    public function welcome_widget_counter_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.mw-welcome-widget-counter\s*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->css,
            '.mw-welcome-widget-counter must have min-height: 44px !important (was 20px).'
        );
    }

    #[Test]
    public function welcome_widget_counter_in_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.mw-welcome-widget-counter\s*\{[^}]*min-height:\s*44px~s',
            $this->bundle,
            'mw-welcome-widget-counter min-height rule must be present in the served bundle.'
        );
    }

    // ─── §6 "View more" link ─────────────────────────────────────────────────

    #[Test]
    public function stats_card_show_more_gets_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.mw-stats-card-show-more\s*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->css,
            '.mw-stats-card-show-more must have min-height: 44px !important (was 15px).'
        );
    }

    #[Test]
    public function stats_card_show_more_in_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.mw-stats-card-show-more\s*\{[^}]*min-height:\s*44px~s',
            $this->bundle,
            'mw-stats-card-show-more min-height rule must be present in the served bundle.'
        );
    }

    // ─── Task marker and media query scope ───────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-77c486',
            $this->css,
            'mobile-touch.css must carry the task-2026-05-22-77c486 marker.'
        );
    }

    #[Test]
    public function rules_are_inside_touch_media_query(): void
    {
        // All fixes must live inside the standard touch-viewport @media block.
        $markerPos = strpos($this->css, 'task-2026-05-22-77c486');
        $this->assertNotFalse($markerPos, 'task marker must exist.');

        $slice = substr($this->css, $markerPos, 4000);
        $this->assertStringContainsString(
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)',
            $slice,
            'The touch-target fixes must be inside the standard touch-viewport @media block.'
        );
    }
}
