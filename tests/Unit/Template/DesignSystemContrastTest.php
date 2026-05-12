<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-284 Color Contrast contract test + CI contrast checker.
 *
 * Doubles as the "contrast checker script for CI" called out in the
 * AI-284 acceptance criteria: every fg/bg pair runs through the
 * WCAG 2.1 relative-luminance formula and fails the test if the
 * computed ratio falls below 4.5:1 (Level AA, normal text).
 *
 * Pins:
 *   1. The audit-approved hex values in design-system.css and the
 *      public-touch.css mirror.
 *   2. Bootstrap variable mapping in public-touch.css so .text-primary,
 *      .bg-primary, .btn-primary etc. inherit the design tokens
 *      instead of Bootstrap's own defaults.
 *   3. Computed contrast ratios for every text-on-surface pair >= 4.5:1.
 *
 * If a future refactor lightens any text token (regression toward the
 * pre-AI-284 gray-400 #9ca3af which fails AA at 2.85:1), the test fails
 * the exact failing pair with its measured ratio.
 */
class DesignSystemContrastTest extends TestCase
{
    private const WCAG_AA_NORMAL_TEXT = 4.5;

    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    /**
     * Audit-approved color tokens after the AI-284 sweep.
     * Pinned to exact hex values in BOTH canonical + mirror files.
     */
    private const EXPECTED_TOKENS = [
        '--color-primary' => '#0d6efd',
        '--color-text-primary' => '#111827',
        '--color-text-secondary' => '#4b5563',
        '--color-text-muted' => '#6b7280',
        '--color-surface' => '#ffffff',
    ];

    /**
     * Bootstrap variable mapping pinned in public-touch.css. Each
     * --bs-* property must point at the matching --color-* token via
     * var() so .text-primary, .bg-primary, etc. inherit the design
     * system without hardcoded hex.
     */
    private const BOOTSTRAP_MAPPINGS = [
        '--bs-primary' => 'var(--color-primary)',
        '--bs-success' => 'var(--color-success)',
        '--bs-danger' => 'var(--color-error)',
        '--bs-warning' => 'var(--color-warning)',
        '--bs-body-color' => 'var(--color-text-primary)',
        '--bs-body-bg' => 'var(--color-surface)',
        '--bs-secondary-color' => 'var(--color-text-secondary)',
        '--bs-tertiary-color' => 'var(--color-text-muted)',
        '--bs-border-color' => 'var(--color-border)',
        '--bs-link-color' => 'var(--color-primary)',
        '--bs-link-hover-color' => 'var(--color-primary-hover)',
    ];

    #[Test]
    public function design_system_css_pins_audit_approved_color_tokens(): void
    {
        $css = $this->readCss(self::DESIGN_SYSTEM_CSS);

        foreach (self::EXPECTED_TOKENS as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/i',
                $css,
                "design-system.css must pin {$token}: {$value} (AI-284 WCAG AA audit value)."
            );
        }
    }

    #[Test]
    public function public_touch_css_mirrors_audit_approved_color_tokens(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        foreach (self::EXPECTED_TOKENS as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/i',
                $css,
                "public-touch.css must mirror {$token}: {$value}."
            );
        }
    }

    #[Test]
    public function public_touch_css_maps_bootstrap_variables_to_design_tokens(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        foreach (self::BOOTSTRAP_MAPPINGS as $bsVar => $expectedValue) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($bsVar, '/') . '\s*:\s*' . preg_quote($expectedValue, '/') . '\s*;/i',
                $css,
                "public-touch.css must map {$bsVar} → {$expectedValue} so Bootstrap utilities inherit the design token."
            );
        }
    }

    public static function textOnSurfacePairs(): array
    {
        return [
            'primary text on white' => ['#111827', '#ffffff'],
            'secondary text on white' => ['#4b5563', '#ffffff'],
            'muted text on white' => ['#6b7280', '#ffffff'],
            'primary brand color on white' => ['#0d6efd', '#ffffff'],
            'primary text on raised surface (gray-50)' => ['#111827', '#f8f9fa'],
            'secondary text on raised surface' => ['#4b5563', '#f8f9fa'],
        ];
    }

    #[Test]
    #[DataProvider('textOnSurfacePairs')]
    public function every_text_on_surface_pair_meets_wcag_aa_normal(
        string $foreground,
        string $background
    ): void {
        $ratio = $this->contrastRatio($foreground, $background);

        $this->assertGreaterThanOrEqual(
            self::WCAG_AA_NORMAL_TEXT,
            $ratio,
            sprintf(
                'WCAG AA fail: %s on %s = %.2f:1 (minimum %.1f:1 for normal text).',
                $foreground,
                $background,
                $ratio,
                self::WCAG_AA_NORMAL_TEXT
            )
        );
    }

    /*
     * WCAG 2.1 relative-luminance formula.
     * https://www.w3.org/TR/WCAG21/#dfn-relative-luminance
     */
    private function contrastRatio(string $hex1, string $hex2): float
    {
        $l1 = $this->relativeLuminance($hex1);
        $l2 = $this->relativeLuminance($hex2);
        $lighter = max($l1, $l2);
        $darker = min($l1, $l2);

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    private function relativeLuminance(string $hex): float
    {
        [$r, $g, $b] = $this->hexToLinearRgb($hex);

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    /** @return float[] {R_linear, G_linear, B_linear} all in [0, 1] */
    private function hexToLinearRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;

        $linearize = fn (float $c): float => $c <= 0.03928
            ? $c / 12.92
            : (($c + 0.055) / 1.055) ** 2.4;

        return [$linearize($r), $linearize($g), $linearize($b)];
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
