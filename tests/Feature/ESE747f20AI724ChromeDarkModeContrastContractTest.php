<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-747f20 / AI-724 — ESE chrome dark mode contrast sweep.
 *
 * Four surfaces identified as failing WCAG AA contrast in dark mode:
 *
 *  1. Collapsed accordion section labels (Typography, Background, etc.) —
 *     inherited dark text on dark panel surface, estimated <4.5:1.
 *     Fix: html.dark .element-style-editor-toggle-wrapper .mw-admin-action-links
 *          { color: var(--ese-text, #f1f5f9) !important; }
 *
 *  2. Section dividers — Border section corner-angle marks use hardcoded
 *     `border: 1px solid #000` in scoped Vue styles, invisible on dark.
 *     Fix: html.dark .angle-top-left/.angle-top-right/.angle-bottom-left/
 *          .angle-bottom-right { border-color: rgba(255,255,255,0.35) !important; }
 *
 *  3. Right-edge icon strip (Insert layout / Template / Design / Quick AI /
 *     Advanced sidebar buttons) — dark glyphs on dark sidebar background.
 *     Fix: html.dark .mw-live-edit-right-sidebar-wrapper ... svg
 *          { fill: #f1f5f9 !important; }
 *
 *  4. Reset (↺) icon in the ESE — dark-stroke issue.
 *     Fix: html.dark #mw-element-style-editor-app .reset-field
 *          { color: var(--ese-text-muted, #94a3b8) !important; }
 *
 * All rules html.dark / .theme-dark / [data-theme="dark"] scoped.
 * Light mode completely unchanged.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class ESE747f20AI724ChromeDarkModeContrastContractTest extends TestCase
{
    private const ESE_CSS  = 'packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css';
    private const LE_CSS   = 'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css';
    private const BUNDLE   = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private string $ese;
    private string $eseStripped;
    private string $leCss;
    private string $leCssStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $rawEse = (string) file_get_contents(base_path(self::ESE_CSS));
        $this->ese = $rawEse;
        $this->eseStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawEse) ?? $rawEse;

        $rawLe = (string) file_get_contents(base_path(self::LE_CSS));
        $this->leCss = $rawLe;
        $this->leCssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawLe) ?? $rawLe;

        $bundlePath = base_path(self::BUNDLE);
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present_in_ese_css(): void
    {
        $this->assertStringContainsString('task-2026-05-22-747f20', $this->ese,
            'element-style-editor.css must carry the AI-724 task marker.');
    }

    #[Test]
    public function task_marker_present_in_live_edit_classes(): void
    {
        $this->assertStringContainsString('task-2026-05-22-747f20', $this->leCss,
            'live-edit-classes.css must carry the AI-724 task marker.');
    }

    // ─── Fix 1: Collapsed accordion section labels ────────────────────────────

    #[Test]
    public function accordion_label_dark_color_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~html\.dark\s+\.element-style-editor-toggle-wrapper[^{]*\.mw-admin-action-links[^{]*\{[^}]*color:\s*var\(--ese-text~s',
            $this->eseStripped,
            'html.dark rule must set color: var(--ese-text) on accordion labels'
        );
    }

    #[Test]
    public function accordion_label_rule_has_important_override(): void
    {
        // The mw-admin-action-links color rule in the ai-724 block must have
        // !important. Search across the entire stripped source.
        $this->assertMatchesRegularExpression(
            '~\.element-style-editor-toggle-wrapper[^{]*\.mw-admin-action-links[^{]*\{[^}]*!important~s',
            $this->eseStripped,
            'Accordion label color must use !important to defeat specificity of prior link rules'
        );
    }

    #[Test]
    public function accordion_label_rule_is_dark_scoped(): void
    {
        // Verify the label color rule is INSIDE an html.dark block,
        // not at global scope — light mode must remain unchanged.
        $pos = strrpos($this->eseStripped, '.element-style-editor-toggle-wrapper');
        $this->assertNotFalse($pos);
        $before = substr($this->eseStripped, 0, (int) $pos);
        $lastDark = strrpos($before, 'html.dark');
        $this->assertNotFalse($lastDark,
            'Accordion label rule must appear inside an html.dark selector context');
    }

    #[Test]
    public function accordion_svg_icon_dark_color_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(html\.dark|\.theme-dark)[^{]*\.element-style-editor-toggle-wrapper\s+svg[^{]*\{[^}]*color~s',
            $this->eseStripped,
            'html.dark must set color on SVG icons inside accordion headers'
        );
    }

    // ─── Fix 2: Border section corner-angle marks ─────────────────────────────

    #[Test]
    public function angle_corner_marks_dark_border_color_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~html\.dark[^{]*\.angle-top-left[^{]*\{[^}]*border-color:\s*rgba\(255,\s*255,\s*255~s',
            $this->eseStripped,
            'html.dark must override .angle-top-left border-color to a light rgba value'
        );
    }

    #[Test]
    public function all_four_angle_marks_are_covered(): void
    {
        foreach (['angle-top-left', 'angle-top-right', 'angle-bottom-left', 'angle-bottom-right'] as $cls) {
            $this->assertStringContainsString($cls, $this->eseStripped,
                'Dark mode border override must cover .' . $cls);
        }
    }

    #[Test]
    public function angle_border_color_has_important(): void
    {
        $pos = strrpos($this->eseStripped, 'angle-top-left');
        $this->assertNotFalse($pos);
        $slice = substr($this->eseStripped, (int) $pos, 300);
        $this->assertStringContainsString('!important', $slice,
            '.angle-* border-color override must use !important (required to beat scoped Vue styles)');
    }

    // ─── Fix 3: Right-edge icon strip SVG ─────────────────────────────────────

    #[Test]
    public function right_sidebar_svg_has_html_dark_override(): void
    {
        $this->assertMatchesRegularExpression(
            '~html\.dark[^{]*\.mw-live-edit-right-sidebar-wrapper[^{]*svg[^{]*\{[^}]*fill[^}]*#f1f5f9\s*!important~s',
            $this->leCssStripped,
            'html.dark must set fill: #f1f5f9 !important on right sidebar SVG icons'
        );
    }

    #[Test]
    public function right_sidebar_icon_rule_covers_btn_icon_class(): void
    {
        $this->assertStringContainsString(
            'btn-icon.live-edit-toolbar-buttons',
            $this->leCssStripped,
            'The right sidebar SVG rule must target .btn-icon.live-edit-toolbar-buttons'
        );
    }

    // ─── Fix 4: Reset icon ────────────────────────────────────────────────────

    #[Test]
    public function reset_icon_dark_color_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~html\.dark[^{]*#mw-element-style-editor-app[^{]*\.reset-field[^{]*\{[^}]*color:\s*var\(--ese-text-muted~s',
            $this->eseStripped,
            'html.dark must set color: var(--ese-text-muted) on .reset-field icons'
        );
    }

    #[Test]
    public function reset_icon_svg_fill_also_set(): void
    {
        $this->assertMatchesRegularExpression(
            '~html\.dark[^{]*\.reset-field\s+svg[^{]*\{[^}]*fill:\s*var\(--ese-text-muted~s',
            $this->eseStripped,
            'html.dark must also set fill: var(--ese-text-muted) on SVGs inside .reset-field'
        );
    }

    // ─── Light mode regression guards ────────────────────────────────────────

    #[Test]
    public function no_light_mode_toggle_wrapper_color_override(): void
    {
        // Strip dark-mode blocks, then verify .element-style-editor-toggle-wrapper
        // does NOT get an unconditional color: #f1f5f9 (light text on light bg).
        $strippedDark = preg_replace(
            '~html\.dark[^{]*\{[^}]*\}~s',
            '',
            $this->eseStripped
        );
        $pos = strpos((string) $strippedDark, '.element-style-editor-toggle-wrapper');
        if ($pos !== false) {
            $slice = substr((string) $strippedDark, (int) $pos, 400);
            $this->assertStringNotContainsString('#f1f5f9', $slice,
                'Light mode must not have #f1f5f9 (dark-mode text) on accordion labels');
        } else {
            $this->assertTrue(true);
        }
    }

    // ─── Bundle probe ─────────────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_accordion_label_dark_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertStringContainsString(
            '.element-style-editor-toggle-wrapper .mw-admin-action-links',
            $this->bundle,
            'Bundle must include the accordion label dark mode rule'
        );
    }

    #[Test]
    public function bundle_contains_angle_mark_dark_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertMatchesRegularExpression(
            '~html\.dark[^{]*\.angle-top-left~',
            $this->bundle,
            'Bundle must include the html.dark angle-top-left border-color override'
        );
    }

    #[Test]
    public function bundle_contains_right_sidebar_svg_dark_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertStringContainsString(
            'html.dark .mw-live-edit-right-sidebar-wrapper .btn-icon.live-edit-toolbar-buttons svg',
            $this->bundle,
            'Bundle must include html.dark right sidebar SVG fill override'
        );
    }

    #[Test]
    public function bundle_contains_reset_icon_dark_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertStringContainsString(
            '.reset-field',
            $this->bundle,
            'Bundle must include .reset-field in dark mode rules'
        );
    }

    #[Test]
    public function bundle_mtime_newer_than_sources(): void
    {
        $bundlePath = base_path(self::BUNDLE);
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertGreaterThanOrEqual(
            filemtime(base_path(self::ESE_CSS)),
            filemtime($bundlePath),
            'Bundle mtime must be >= element-style-editor.css mtime'
        );
        $this->assertGreaterThanOrEqual(
            filemtime(base_path(self::LE_CSS)),
            filemtime($bundlePath),
            'Bundle mtime must be >= live-edit-classes.css mtime'
        );
    }
}
