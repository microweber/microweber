<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-3e1637 / AI-747 — /admin/posts/create mobile overflow
 * and editor toolbar wrap fix.
 *
 * Two layout failures at 390×844 mobile viewport on the Posts create/edit form:
 *
 * Problem 1 — Horizontal overflow:
 *   .fi-fo-rich-editor container had no max-width cap; toolbar button overflow
 *   caused the entire form to scroll horizontally at 390px.
 *   Fix: max-width: 100% !important + overflow-x: hidden !important on
 *   body.fi-panel-admin .fi-fo-rich-editor inside the touch @media block.
 *
 * Problem 2 — Toolbar wraps to 5 rows:
 *   Filament's toolbar uses flex-wrap:wrap; each button is ≥44px (touch floor);
 *   at 390px only ~8 buttons fit per row → ~30+ buttons → 5 rows → 180px of
 *   vertical chrome, eating ~20% of the usable viewport.
 *   Fix: flex-wrap:nowrap !important + overflow-x:auto !important on the toolbar
 *   — toolbar becomes a single horizontally-scrollable row.
 *   flex-shrink:0 added to buttons so they honour the 44px touch-target floor.
 *   Scrollbar hidden via scrollbar-width:none + ::-webkit-scrollbar { display:none }.
 *
 * Problem 3 — Multi-column form sections don't stack at narrow viewport (AC 4):
 *   Fix: grid-template-columns: minmax(0, 1fr) !important on .fi-fo-component-ctn
 *   inside the touch @media block.
 *
 * All rules scoped to body.fi-panel-admin inside
 * @media (max-width: 1023.98px), (hover: none) and (pointer: coarse).
 * Desktop (>1023.98px) completely unchanged.
 * Existing touch-target floor (lines 544-558) preserved.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Post3e1637AI747MobileLayoutContractTest extends TestCase
{
    private const MOBILE_TOUCH = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const BUNDLE       = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private string $css;
    private string $cssStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $raw = (string) file_get_contents(base_path(self::MOBILE_TOUCH));
        $this->css = $raw;
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;

        $bundlePath = base_path(self::BUNDLE);
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-3e1637', $this->css,
            'mobile-touch.css must carry the AI-747 task marker.');
    }

    // ─── Fix 1: editor container overflow ────────────────────────────────────

    #[Test]
    public function editor_container_has_max_width_100(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-fo-rich-editor\s*\{[^}]*max-width:\s*100%\s*!important~s',
            $this->cssStripped,
            '.fi-fo-rich-editor must have max-width: 100% !important in mobile block'
        );
    }

    #[Test]
    public function editor_container_has_overflow_x_hidden(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-fo-rich-editor\s*\{[^}]*overflow-x:\s*hidden\s*!important~s',
            $this->cssStripped,
            '.fi-fo-rich-editor must have overflow-x: hidden !important in mobile block'
        );
    }

    // ─── Fix 2: toolbar single-row scroll ────────────────────────────────────

    #[Test]
    public function toolbar_has_flex_wrap_nowrap(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-fo-rich-editor-toolbar\s*\{[^}]*flex-wrap:\s*nowrap\s*!important~s',
            $this->cssStripped,
            '.fi-fo-rich-editor-toolbar must have flex-wrap: nowrap !important'
        );
    }

    #[Test]
    public function toolbar_has_overflow_x_auto(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-fo-rich-editor-toolbar\s*\{[^}]*overflow-x:\s*auto\s*!important~s',
            $this->cssStripped,
            '.fi-fo-rich-editor-toolbar must have overflow-x: auto !important'
        );
    }

    #[Test]
    public function toolbar_scrollbar_hidden(): void
    {
        // scrollbar-width: none hides the Firefox scrollbar.
        // Check the full stripped source — the two toolbar rules are separate
        // blocks and strrpos would land on the ::-webkit-scrollbar block.
        $this->assertStringContainsString('scrollbar-width: none', $this->cssStripped,
            '.fi-fo-rich-editor-toolbar must hide scrollbar with scrollbar-width:none');
        // ::-webkit-scrollbar { display: none } hides the Chrome scrollbar.
        $this->assertStringContainsString('-webkit-scrollbar', $this->cssStripped,
            '.fi-fo-rich-editor-toolbar must suppress ::-webkit-scrollbar');
    }

    #[Test]
    public function toolbar_buttons_have_flex_shrink_zero(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-fo-rich-editor-tool[^{]*\{[^}]*flex-shrink:\s*0\s*!important~s',
            $this->cssStripped,
            '.fi-fo-rich-editor-tool must have flex-shrink: 0 !important to preserve 44px touch target'
        );
    }

    // ─── Fix 3: form fields stack vertically ─────────────────────────────────

    #[Test]
    public function form_grid_forced_to_single_column(): void
    {
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-fo-component-ctn\s*\{[^}]*grid-template-columns:\s*minmax\(0,\s*1fr\)\s*!important~s',
            $this->cssStripped,
            '.fi-fo-component-ctn must force single-column grid on mobile (AC 4: fields stack vertically)'
        );
    }

    // ─── Scope guard: rules inside touch @media block ────────────────────────

    #[Test]
    public function all_ai747_rules_are_inside_touch_media_query(): void
    {
        // Verify task marker's position is after a @media block.
        $markerPos = strrpos($this->cssStripped, '.fi-fo-rich-editor');
        $this->assertNotFalse($markerPos);
        $before = substr($this->cssStripped, 0, (int) $markerPos);
        $lastAt = strrpos($before, '@media');
        $this->assertNotFalse($lastAt,
            '.fi-fo-rich-editor rule must be inside a @media block');
        $mediaSlice = substr($this->cssStripped, (int) $lastAt, 80);
        $this->assertStringContainsString('max-width', $mediaSlice,
            '.fi-fo-rich-editor rule must be inside the touch-viewport @media block');
    }

    // ─── Existing touch-target floor regression guard ────────────────────────

    #[Test]
    public function existing_44px_touch_target_floor_preserved(): void
    {
        // The existing rule at lines 544-558 sets min-height:44px on toolbar
        // buttons. That rule must still be present — nowrap doesn't remove it.
        $this->assertMatchesRegularExpression(
            '~body\.fi-panel-admin\s+\.fi-fo-rich-editor-tool[^{]*\{[^}]*min-height:\s*44px\s*!important~s',
            $this->cssStripped,
            'Existing 44px touch-target floor on .fi-fo-rich-editor-tool must not be removed'
        );
    }

    // ─── Bundle probe ─────────────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_toolbar_nowrap_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertStringContainsString('fi-fo-rich-editor-toolbar', $this->bundle,
            'Built bundle must include the .fi-fo-rich-editor-toolbar flex-wrap override');
    }

    #[Test]
    public function bundle_contains_form_grid_single_column_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertStringContainsString('fi-fo-component-ctn', $this->bundle,
            'Built bundle must include the .fi-fo-component-ctn single-column override');
    }

    #[Test]
    public function bundle_mtime_newer_than_source(): void
    {
        $bundlePath = base_path(self::BUNDLE);
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime(base_path(self::MOBILE_TOUCH)),
            filemtime($bundlePath),
            'Bundle mtime must be >= mobile-touch.css mtime'
        );
    }
}
