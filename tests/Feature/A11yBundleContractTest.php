<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-125 / AI-134..139 — bundled a11y improvements.
 *
 * Pins:
 *   - A11Y bundle CSS exists at the canonical path.
 *   - Each of the 6 sub-tickets has at least one rule keyed by
 *     its OOYES audit id.
 *   - Contrast helper exists with `contrastRatio`,
 *     `contrastBadge`, `hexToRgb` exports.
 *   - `prefers-reduced-motion: reduce` block scopes the
 *     animation-disable rule.
 *
 * Style after the cycle-52..124 contract tests.
 */
class A11yBundleContractTest extends TestCase
{
    private const CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/a11y-bundle.css';
    private const JS  = 'packages/frontend-assets/resources/assets/ui/a11y/contrast.js';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function a11y_bundle_css_covers_each_sub_ticket(): void
    {
        $src = $this->read(self::CSS);

        // Each of the 6 sub-tickets gets a labelled section.
        foreach ([
            'AI-134 — A11Y-01',
            'AI-135 — A11Y-02',
            'AI-136 — A11Y-03',
            'AI-137 — A11Y-04',
            'AI-138 — A11Y-05',
            'AI-139 — A11Y-06',
        ] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $src,
                "a11y-bundle.css must declare a section for `{$needle}`"
            );
        }
    }

    #[Test]
    public function reduced_motion_block_disables_animations(): void
    {
        $src = $this->read(self::CSS);

        // prefers-reduced-motion media block must collapse animation
        // and transition durations to ~0.
        $this->assertMatchesRegularExpression(
            '/@media \\(prefers-reduced-motion: reduce\\)\\s*\\{[\\s\\S]{0,500}animation-duration:\\s*0\\.01ms[\\s\\S]{0,200}transition-duration:\\s*0\\.01ms/s',
            $src,
            'a11y-bundle.css must scope an `@media (prefers-reduced-motion: reduce)` block that zeroes animation+transition durations'
        );
    }

    #[Test]
    public function status_pill_glyphs_are_text_not_color_only(): void
    {
        $src = $this->read(self::CSS);

        // Pin the glyph content for each status — colour-blind
        // users get the icon as a parallel signal.
        $this->assertStringContainsString(
            '.mw-status-pill.mw-status-success::before { content: "✓"; }',
            $src,
            'success pill must carry a checkmark glyph'
        );
        $this->assertStringContainsString(
            '.mw-status-pill.mw-status-danger::before { content: "✕"; }',
            $src,
            'danger pill must carry an X glyph'
        );
    }

    #[Test]
    public function contrast_helper_exports_required_functions(): void
    {
        $src = $this->read(self::JS);

        foreach (['contrastRatio', 'contrastBadge', 'hexToRgb', 'relativeLuminance'] as $needle) {
            $this->assertStringContainsString(
                "export function {$needle}",
                $src,
                "contrast.js must export `{$needle}`"
            );
        }

        // WCAG threshold pin.
        $this->assertStringContainsString(
            'ratio >= 7',
            $src,
            'contrast.js must use WCAG AAA threshold 7:1'
        );
        $this->assertStringContainsString(
            'ratio >= 4.5',
            $src,
            'contrast.js must use WCAG AA threshold 4.5:1'
        );
    }

    #[Test]
    public function aria_disabled_visual_state_pinned(): void
    {
        $src = $this->read(self::CSS);

        // aria-disabled MUST visually match the SR-announced state.
        $this->assertMatchesRegularExpression(
            '/\\[aria-disabled="true"\\]\\s*\\{[\\s\\S]{0,200}opacity:\\s*0\\.55[\\s\\S]{0,100}cursor:\\s*not-allowed/',
            $src,
            'a11y-bundle.css must visually dim aria-disabled="true" elements (opacity + cursor)'
        );
    }
}
