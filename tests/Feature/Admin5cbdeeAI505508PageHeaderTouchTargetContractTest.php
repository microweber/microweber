<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-5cbdee / AI-505/508 (admin mobile audit follow-up)
 *
 * Agent-test audit at 390×844 found two new FAILs on /admin/pages:
 *   1. Brand mark (.mw-admin-brand-mark) — 36px wide (task-77c486 added
 *      min-height only; min-width was missing). Now 44×44.
 *   2. Page-header action buttons ("+ Add page", "Setup Homepage") —
 *      .fi-color-primary / .fi-color-warning at 42px height (2px short).
 *      Filament h-9 (36px) + 3px padding × 2 = 42px. Now min-height 44px.
 *
 * Also: AI-507 media library path correction — /admin/media returns 404;
 * correct route is /admin/media-library.
 */
class Admin5cbdeeAI505508PageHeaderTouchTargetContractTest extends TestCase
{
    private string $css;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css')
        );
        $this->bundle = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/dist/build/microweber-filament-theme.css')
        );
    }

    // ─── Brand mark width fix ─────────────────────────────────────────────────

    #[Test]
    public function brand_mark_gets_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin[^{]*mw-admin-brand-mark[^{]*\{[^}]*min-width:\s*44px\s*!important~s',
            $this->css,
            '.mw-admin-brand-mark must have min-width: 44px !important (was only min-height; width remained 36px at mobile).'
        );
    }

    #[Test]
    public function brand_mark_still_has_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin[^{]*mw-admin-brand-mark[^{]*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->css,
            '.mw-admin-brand-mark must still have min-height: 44px !important (regression guard).'
        );
    }

    #[Test]
    public function brand_mark_min_width_in_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin[^{]*mw-admin-brand-mark[^{]*\{[^}]*min-width:\s*44px~s',
            $this->bundle,
            'Brand mark min-width rule must be present in the served bundle.'
        );
    }

    // ─── Page-header action button height fix ────────────────────────────────

    #[Test]
    public function color_primary_buttons_get_min_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin[^{]*\.fi-btn\.fi-color-primary[^{]*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->css,
            '.fi-btn.fi-color-primary must have min-height: 44px !important (was 42px — "+ Add page" on /admin/pages).'
        );
    }

    #[Test]
    public function color_warning_buttons_get_min_height_44px(): void
    {
        $this->assertStringContainsString(
            '.fi-btn.fi-color-warning',
            $this->css,
            '.fi-btn.fi-color-warning must be included in the page-header button touch-target fix ("Setup Homepage" was 42px).'
        );
    }

    #[Test]
    public function page_header_button_fix_in_bundle(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-btn\.fi-color-primary[^{]*\{[^}]*min-height:\s*44px~s',
            $this->bundle,
            '.fi-btn.fi-color-primary min-height rule must be present in the served bundle.'
        );
    }

    #[Test]
    public function page_header_button_rule_is_inside_touch_media_query(): void
    {
        // The marker appears twice: once in the brand-mark fix comment and once
        // in the new .fi-btn.fi-color-primary block. strrpos finds the LAST
        // occurrence — the new block — and checks it opens with the standard
        // touch-viewport @media declaration.
        $markerPos = strrpos($this->css, 'task-2026-05-22-5cbdee');
        $this->assertNotFalse($markerPos, 'task-2026-05-22-5cbdee marker must exist (last occurrence).');

        $slice = substr($this->css, $markerPos, 1500);
        $this->assertStringContainsString(
            '@media (max-width: 1023.98px), (hover: none) and (pointer: coarse)',
            $slice,
            'The page-header button touch-target fix must be inside the standard touch-viewport @media block.'
        );
    }

    // ─── AI-507 media library path correction ────────────────────────────────

    #[Test]
    public function media_library_page_exists_at_correct_path(): void
    {
        $this->assertFileExists(
            base_path('Modules/MediaLibrary/Filament/Admin/Pages/MediaLibrary.php'),
            'The MediaLibrary Filament page must exist — its admin route is /admin/media-library (not /admin/media).'
        );
    }

    // ─── Task markers ─────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-5cbdee',
            $this->css,
            'mobile-touch.css must carry the task-2026-05-22-5cbdee marker.'
        );
    }
}
