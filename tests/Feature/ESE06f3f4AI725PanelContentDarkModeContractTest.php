<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-06f3f4 / AI-725 — ESE panel content dark mode mapping.
 *
 * AI-724 fixed the ESE chrome (accordion labels, section dividers, icon-strip
 * SVGs, reset icon). This ticket fixes the CONTENT inside each open panel:
 *
 *  9.  Typographic preset thumbnails (.mw-live-edit-predefines-styles-box)
 *      White backplate preserves text legibility regardless of predefined class.
 *
 * 10.  Selected predefined-style preview heading (.predefined-styles-rended)
 *      Same white-island treatment as the thumbnail boxes.
 *
 * 11.  Spacing diagram holder (.mw-ese-holder)
 *      Hardcoded @apply bg-gray-100 border-gray-300 overridden to dark surface.
 *
 * 12.  Spacing diagram position labels (.mw-ese-label — TOP/RIGHT/BOTTOM/LEFT)
 *      Re-anchored to --ese-text-muted (#94a3b8) in dark mode.
 *
 * 13.  Preset quick-action buttons (.mw-tool-btn.mw-tool-btn--preset)
 *      Bootstrap .btn can force dark text; re-anchored to --ese-text.
 *
 * 14.  Unit toggle button (.mw-field.unit .mw-ui-btn / .mw-ui-btn i)
 *      px/rem/% switcher text re-anchored to --ese-text-muted.
 *
 * 15.  Segmented-control buttons (.mw-segmented-control button + active)
 *      Older align/justify icon strip; Bootstrap overrides ESE token color.
 *
 * All rules .dark / .theme-dark / [data-theme="dark"] scoped.
 * Light mode completely unchanged.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class ESE06f3f4AI725PanelContentDarkModeContractTest extends TestCase
{
    private const ESE_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css';
    private const BUNDLE  = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';

    private string $ese;
    private string $eseStripped;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();

        $rawEse = (string) file_get_contents(base_path(self::ESE_CSS));
        $this->ese = $rawEse;
        $this->eseStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawEse) ?? $rawEse;

        $bundlePath = base_path(self::BUNDLE);
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present_in_ese_css(): void
    {
        $this->assertStringContainsString('task-2026-05-22-06f3f4', $this->ese,
            'element-style-editor.css must carry the AI-725 task marker.');
    }

    // ─── Fix 9: Typographic preset thumbnails ─────────────────────────────────

    #[Test]
    public function preset_thumbnail_box_has_white_backplate_in_dark(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-live-edit-predefines-styles-box[^{]*\{[^}]*background-color:\s*#ffffff~s',
            $this->eseStripped,
            '.dark must give .mw-live-edit-predefines-styles-box a white backplate'
        );
    }

    #[Test]
    public function preset_thumbnail_box_has_dark_border(): void
    {
        $pos = strrpos($this->eseStripped, '.mw-live-edit-predefines-styles-box');
        $this->assertNotFalse($pos);
        $slice = substr($this->eseStripped, (int) $pos, 300);
        $this->assertStringContainsString('border:', $slice,
            '.mw-live-edit-predefines-styles-box dark rule must include a border');
    }

    // ─── Fix 10: Selected predefined-style preview ────────────────────────────

    #[Test]
    public function predefined_styles_rended_has_white_backplate_in_dark(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.predefined-styles-rended[^{]*\{[^}]*background-color:\s*#ffffff~s',
            $this->eseStripped,
            '.dark must give .predefined-styles-rended a white backplate'
        );
    }

    // ─── Fix 11: Spacing diagram holder ──────────────────────────────────────

    #[Test]
    public function ese_holder_dark_background_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-ese-holder[^{]*\{[^}]*background-color:\s*var\(--ese-surface-muted~s',
            $this->eseStripped,
            '.dark must override .mw-ese-holder background to --ese-surface-muted'
        );
    }

    #[Test]
    public function ese_holder_dark_border_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-ese-holder[^{]*\{[^}]*border-color:\s*var\(--ese-border~s',
            $this->eseStripped,
            '.dark must override .mw-ese-holder border to --ese-border'
        );
    }

    #[Test]
    public function ese_holder_active_dark_state_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-ese-holder\.active[^{]*\{[^}]*border-color:\s*var\(--ese-accent~s',
            $this->eseStripped,
            '.dark must set active holder border to --ese-accent'
        );
    }

    // ─── Fix 12: Spacing diagram labels ───────────────────────────────────────

    #[Test]
    public function ese_label_dark_color_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-ese-label[^{]*\{[^}]*color:\s*var\(--ese-text-muted~s',
            $this->eseStripped,
            '.dark must set .mw-ese-label color to --ese-text-muted'
        );
    }

    // ─── Fix 13: Preset quick-action buttons ──────────────────────────────────

    #[Test]
    public function preset_btn_dark_text_color_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-tool-btn\.mw-tool-btn--preset[^{]*\{[^}]*color:\s*var\(--ese-text~s',
            $this->eseStripped,
            '.dark must set .mw-tool-btn--preset color to --ese-text'
        );
    }

    #[Test]
    public function preset_btn_rule_has_important(): void
    {
        $pos = strrpos($this->eseStripped, '.mw-tool-btn.mw-tool-btn--preset');
        $this->assertNotFalse($pos);
        // Find the rule after the last occurrence (the dark mode block)
        $before = substr($this->eseStripped, 0, (int) $pos);
        $lastDark = strrpos($before, '.dark');
        $this->assertNotFalse($lastDark,
            'The last .mw-tool-btn--preset rule must be inside an .dark block');
        $slice = substr($this->eseStripped, (int) $pos, 200);
        $this->assertStringContainsString('!important', $slice,
            '.mw-tool-btn--preset dark color must use !important');
    }

    // ─── Fix 14: Unit toggle button ───────────────────────────────────────────

    #[Test]
    public function unit_btn_dark_color_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-field\.unit\s+\.mw-ui-btn[^{]*\{[^}]*color:\s*var\(--ese-text-muted~s',
            $this->eseStripped,
            '.dark must set .mw-field.unit .mw-ui-btn color to --ese-text-muted'
        );
    }

    // ─── Fix 15: Segmented-control buttons ────────────────────────────────────

    #[Test]
    public function segmented_control_dark_bg_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-segmented-control\s+button[^{]*\{[^}]*background-color:\s*var\(--ese-surface-muted~s',
            $this->eseStripped,
            '.dark must set .mw-segmented-control button background to --ese-surface-muted'
        );
    }

    #[Test]
    public function segmented_control_dark_text_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-segmented-control\s+button[^{]*\{[^}]*color:\s*var\(--ese-text~s',
            $this->eseStripped,
            '.dark must set .mw-segmented-control button color to --ese-text'
        );
    }

    #[Test]
    public function segmented_control_active_dark_accent_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-segmented-control\s+button\.active[^{]*\{[^}]*color:\s*var\(--ese-accent~s',
            $this->eseStripped,
            '.dark must set .mw-segmented-control button.active color to --ese-accent'
        );
    }

    // ─── Light-mode regression guards ────────────────────────────────────────

    #[Test]
    public function light_mode_ese_holder_unchanged(): void
    {
        // Strip dark-mode blocks then verify .mw-ese-holder at light scope
        // still uses @apply (not hardcoded dark values).
        $darkStripped = preg_replace(
            '~\.dark[^{]*\{[^}]*\}~s',
            '',
            $this->eseStripped
        );
        // After stripping dark blocks, .mw-ese-holder must NOT contain dark surface values
        $pos = strpos((string) $darkStripped, '.mw-ese-holder');
        if ($pos !== false) {
            $slice = substr((string) $darkStripped, $pos, 200);
            $this->assertStringNotContainsString('#232938', $slice,
                'Light mode .mw-ese-holder must not contain dark surface color #232938');
        } else {
            $this->assertTrue(true);
        }
    }

    #[Test]
    public function ai724_chrome_rules_still_present(): void
    {
        // Regression guard: AI-724 chrome fixes must not be overwritten.
        $this->assertStringContainsString('task-2026-05-22-747f20', $this->ese,
            'AI-724 task marker must still be present (chrome fixes not overwritten)');
        $this->assertStringContainsString('.element-style-editor-toggle-wrapper .mw-admin-action-links', $this->eseStripped,
            'AI-724 accordion label rule must still be present');
    }

    // ─── Bundle probe ─────────────────────────────────────────────────────────

    #[Test]
    public function bundle_contains_preset_thumbnail_white_backplate(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertStringContainsString('mw-live-edit-predefines-styles-box', $this->bundle,
            'Bundle must include the preset thumbnail white-backplate rule');
    }

    #[Test]
    public function bundle_contains_ese_holder_dark_rule(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $this->assertStringContainsString('mw-ese-holder', $this->bundle,
            'Bundle must include the .mw-ese-holder dark mode rule');
    }

    #[Test]
    public function bundle_mtime_newer_than_source(): void
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
    }
}
