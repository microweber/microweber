<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-291 Button States contract test.
 *
 * Pins the four button-state design tokens and every state rule
 * (focus-visible, active, disabled, loading) to specific shapes in
 * the canonical design-system.css and the public-touch.css mirror.
 *
 * Fails fast in unit CI if a future refactor:
 *   - renames a token (`--btn-focus-ring-width` → `--btn-ring-width`)
 *   - drifts a token value (focus offset 2px → 4px)
 *   - drops `:focus-visible` in favor of `:focus` (a11y regression)
 *   - drops `cursor: not-allowed` on disabled buttons
 *   - drops the loading-state spinner keyframe
 *
 * Follows the same pattern established by
 * DesignSystemTypographyContractTest.
 */
class DesignSystemButtonStatesContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    private const EXPECTED_TOKENS = [
        '--btn-focus-ring-width' => '2px',
        '--btn-focus-ring-offset' => '2px',
        '--btn-active-scale' => '0.98',
        '--btn-disabled-opacity' => '0.5',
    ];

    #[Test]
    public function design_system_css_defines_every_ai291_token(): void
    {
        $css = $this->readCss(self::DESIGN_SYSTEM_CSS);

        foreach (self::EXPECTED_TOKENS as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                "design-system.css must define {$token}: {$value};"
            );
        }
    }

    #[Test]
    public function public_touch_css_mirrors_every_ai291_token(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        foreach (self::EXPECTED_TOKENS as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                "public-touch.css must mirror {$token}: {$value};"
            );
        }
    }

    #[Test]
    public function public_touch_css_uses_focus_visible_not_focus(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        // Must have at least one .btn:focus-visible rule.
        $this->assertMatchesRegularExpression(
            '/\.btn:focus-visible/',
            $css,
            'public-touch.css must use :focus-visible for .btn focus ring (keyboard-only).'
        );

        // Must reference the focus-ring token, not a hardcoded width.
        $this->assertMatchesRegularExpression(
            '/outline:\s*var\(--btn-focus-ring-width\)/',
            $css,
            'Focus ring must use var(--btn-focus-ring-width), not a hardcoded width.'
        );
    }

    #[Test]
    public function public_touch_css_defines_active_state_with_scale(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.btn:not\(:disabled\):not\(\.disabled\):active/',
            $css,
            '.btn active state must be gated on :not(:disabled):not(.disabled).'
        );

        $this->assertMatchesRegularExpression(
            '/transform:\s*scale\(var\(--btn-active-scale\)\)/',
            $css,
            'Active state must use var(--btn-active-scale) for the scale transform.'
        );
    }

    #[Test]
    public function public_touch_css_defines_disabled_state_with_cursor(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/cursor:\s*not-allowed/',
            $css,
            'Disabled buttons must carry `cursor: not-allowed`.'
        );

        $this->assertMatchesRegularExpression(
            '/opacity:\s*var\(--btn-disabled-opacity\)/',
            $css,
            'Disabled buttons must use var(--btn-disabled-opacity), not a hardcoded value.'
        );
    }

    #[Test]
    public function public_touch_css_defines_loading_state(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.btn\[data-loading="true"\]/',
            $css,
            'Loading state must be opt-in via [data-loading="true"].'
        );

        $this->assertMatchesRegularExpression(
            '/\.btn\[aria-busy="true"\]/',
            $css,
            'Loading state must also gate on [aria-busy="true"] (canonical ARIA).'
        );

        $this->assertMatchesRegularExpression(
            '/@keyframes\s+mw-btn-spin/',
            $css,
            'Loading state must define the @keyframes mw-btn-spin animation.'
        );
    }

    #[Test]
    public function public_touch_css_respects_prefers_reduced_motion(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*reduce\)/',
            $css,
            'Button states must drop animations under prefers-reduced-motion.'
        );
    }

    private function readCss(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "CSS file not found: {$path}");

        $css = file_get_contents($real);
        $this->assertNotFalse($css, "Could not read CSS file: {$path}");
        $this->assertNotEmpty($css, "CSS file is empty: {$path}");

        return $css;
    }
}
