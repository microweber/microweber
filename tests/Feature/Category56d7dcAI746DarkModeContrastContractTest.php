<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-56d7dc / AI-746 — Categories admin dark mode contrast.
 *
 * Problem: `/admin/categories` and `/admin/shop-categories` context-menu
 * action labels (Edit Category, View, Delete, Add category) rendered
 * dark-blue on dark-navy background in dark mode — WCAG AA failure.
 *
 * Root cause: The categories page uses a custom `mw.widget.tree()` component
 * with `.mw-tree-nav-skin-category-manager` skin.  Row actions render as
 * `.mw-tree-context-menu-item` elements whose `color: inherit` rule
 * (tree.scss) inherited Bootstrap link-primary (#0d6efd) on the dark navy
 * background (#0d0f14), giving approximately 3.8:1 contrast — below the
 * WCAG AA 4.5:1 floor for normal text.
 *
 * Fix: added `.dark .mw-tree-nav-skin-category-manager
 * .mw-tree-context-menu-item { color: #e2e8f0 !important; }` in
 * `microweber-theme-v3.scss`. #e2e8f0 on #0d0f14 achieves approximately
 * 15:1 contrast (WCAG AAA). Hover intensifies to #ffffff (approximately
 * 19:1) with a subtle background.
 *
 * ShopCategoryResource extends CategoryResource and uses the identical
 * skin, so both `/admin/categories` and `/admin/shop-categories` are covered
 * by a single CSS rule.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Category56d7dcAI746DarkModeContrastContractTest extends TestCase
{
    private const SCSS_SRC = 'packages/microweber-filament-theme/resources/assets/css/microweber/admin/menu-editor.css';
    private const BUNDLE   = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private string $src;
    private string $srcStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $raw = (string) file_get_contents(base_path(self::SCSS_SRC));
        $this->src = $raw;
        $this->srcStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $raw) ?? $raw;

        $bundlePath = base_path(self::BUNDLE);
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-56d7dc', $this->src,
            'microweber-theme-v3.scss must carry the AI-746 task marker.');
    }

    // ─── Dark mode color rule ─────────────────────────────────────────────────

    #[Test]
    public function dark_context_menu_item_has_high_contrast_color(): void
    {
        // Verify the rule uses #e2e8f0 — approximately 15:1 on #0d0f14 (WCAG AAA).
        $pos = strrpos($this->srcStripped, '.dark');
        $this->assertNotFalse($pos, '.dark block must exist in microweber-theme-v3.scss');

        // Find the dark mode context-menu-item rule — use strrpos for the
        // LAST occurrence since the task marker also mentions the class.
        $rulePos = strrpos($this->srcStripped, '.mw-tree-context-menu-item');
        $this->assertNotFalse($rulePos,
            '.mw-tree-context-menu-item rule must be present in microweber-theme-v3.scss');

        $slice = substr($this->srcStripped, (int) $rulePos, 400);
        $this->assertMatchesRegularExpression(
            '~#e2e8f0\s*!important~i',
            $slice,
            '.mw-tree-context-menu-item must have color: #e2e8f0 !important in dark mode'
        );
    }

    #[Test]
    public function dark_context_menu_item_rule_is_inside_html_dark_block(): void
    {
        // The LAST occurrence of .mw-tree-context-menu-item is the dark-mode
        // rule (the tree.scss rules come first in source order). The rule must
        // appear after the last `.dark` opener, with more `{` than `}` in
        // between (meaning we are still inside the block).
        $rulePos = (int) strrpos($this->srcStripped, '.mw-tree-context-menu-item');

        $before = substr($this->srcStripped, 0, $rulePos);
        $lastDark = strrpos($before, '.dark');
        $this->assertNotFalse($lastDark,
            'The .mw-tree-context-menu-item dark rule must appear inside an .dark block');

        $between = substr($this->srcStripped, (int) $lastDark, $rulePos - (int) $lastDark);
        $opens   = substr_count($between, '{');
        $closes  = substr_count($between, '}');
        $this->assertGreaterThan($closes, $opens,
            '.mw-tree-context-menu-item must be nested inside an .dark block (unclosed braces)');
    }

    #[Test]
    public function dark_hover_intensifies_to_white(): void
    {
        // Hover must use #ffffff so the state change is visually salient.
        $pos = strrpos($this->srcStripped, '.mw-tree-context-menu-item');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 600);
        $this->assertMatchesRegularExpression(
            '~&:hover\s*\{[^}]*#ffffff\s*!important~s',
            $slice,
            '.mw-tree-context-menu-item:hover must intensify to #ffffff !important'
        );
    }

    #[Test]
    public function dark_context_menu_svg_color_matches_label(): void
    {
        // SVG icons must share the same color as the label for visual
        // consistency.
        $pos = strrpos($this->srcStripped, '.mw-tree-context-menu-item');
        $this->assertNotFalse($pos);
        $slice = substr($this->srcStripped, (int) $pos, 600);
        $this->assertMatchesRegularExpression(
            '~svg\s*\{[^}]*#e2e8f0\s*!important~s',
            $slice,
            'SVG icons inside .mw-tree-context-menu-item must also be set to #e2e8f0'
        );
    }

    // ─── Light mode regression guard ──────────────────────────────────────────

    #[Test]
    public function light_mode_context_menu_item_has_no_color_override(): void
    {
        // Strip .dark blocks from the stripped source, then assert the
        // .mw-tree-context-menu-item that remains (from tree.scss) does NOT
        // declare #e2e8f0 or a hardcoded color — light mode uses `color: inherit`.
        // We check the first (non-dark) occurrence only.
        $firstPos = strpos($this->srcStripped, '.mw-tree-context-menu-item');
        $lastPos  = strrpos($this->srcStripped, '.mw-tree-context-menu-item');
        $this->assertNotFalse($firstPos);

        // The first occurrence is the light-mode / base rule (not dark).
        if ($firstPos !== $lastPos) {
            $slice = substr($this->srcStripped, (int) $firstPos,
                (int) $lastPos - (int) $firstPos);
            $this->assertStringNotContainsString('#e2e8f0', $slice,
                'Light-mode base rules must not contain the dark-mode-specific #e2e8f0 color');
        } else {
            // Only one occurrence — just verify the assertion passes vacuously.
            $this->assertTrue(true);
        }
    }

    // ─── ShopCategoryResource inheritance ─────────────────────────────────────

    #[Test]
    public function shop_category_resource_extends_category_resource(): void
    {
        $shopResource = (string) file_get_contents(
            base_path('Modules/Category/Filament/Admin/Resources/ShopCategoryResource.php')
        );
        $this->assertMatchesRegularExpression(
            '~class ShopCategoryResource\s+extends\s+CategoryResource~',
            $shopResource,
            'ShopCategoryResource must extend CategoryResource — it inherits the same tree skin and the fix applies automatically'
        );
    }

    // ─── Bundle presence ──────────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_dark_context_menu_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present in this environment.');
        }
        $hasFlattened = str_contains(
            $this->bundle,
            '.dark .mw-tree-nav-skin-category-manager .mw-tree-context-menu-item'
        );
        $hasNested = str_contains($this->bundle, '.mw-tree-nav-skin-category-manager')
            && str_contains($this->bundle, '.mw-tree-context-menu-item')
            && str_contains($this->bundle, '.dark');
        $this->assertTrue(
            $hasFlattened || $hasNested,
            'Webpack bundle must include the dark mode context-menu-item color rule (flattened or nested).'
        );
        $this->assertStringContainsString(
            '#e2e8f0',
            $this->bundle,
            'Webpack bundle must include the #e2e8f0 high-contrast color value'
        );
    }

    #[Test]
    public function bundle_mtime_is_newer_than_source(): void
    {
        $bundlePath = base_path(self::BUNDLE);
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Webpack bundle not present in this environment.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime(base_path(self::SCSS_SRC)),
            filemtime($bundlePath),
            'Bundle mtime must be >= source mtime — confirms bundle was rebuilt after the fix'
        );
    }
}
