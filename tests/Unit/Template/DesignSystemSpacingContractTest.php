<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-285 Spacing System contract test.
 *
 * Pins the 8-step spacing scale and the three component-padding
 * semantic aliases in both the canonical design-system.css and the
 * public-touch.css mirror. Fails fast in unit CI if a future refactor
 * drifts a value, renames a token, or drops the component aliases.
 *
 * Follows the same pattern established by
 * DesignSystemTypographyContractTest / DesignSystemContrastTest.
 */
class DesignSystemSpacingContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    /**
     * Canonical 8-step scale from AI-285 acceptance criteria.
     * 8px is the base unit; --space-1 (4px) is a half-step that
     * survived from the pre-AI-285 token block for badge insets and
     * icon padding (still useful, no reason to drop it).
     */
    private const EXPECTED_SCALE = [
        '--space-1' => '4px',
        '--space-2' => '8px',
        '--space-3' => '12px',
        '--space-4' => '16px',
        '--space-5' => '24px',
        '--space-6' => '32px',
        '--space-7' => '48px',
        '--space-8' => '64px',
    ];

    /**
     * Component-padding semantic aliases (AI-285 acceptance #4).
     * These MUST be `var()` references at the canonical scale step,
     * not hardcoded pixel values — that's the whole point of the
     * alias layer (switch density later in one place).
     */
    private const EXPECTED_COMPONENT_ALIASES = [
        '--space-card' => 'var(--space-4)',
        '--space-form' => 'var(--space-5)',
        '--space-modal' => 'var(--space-6)',
    ];

    #[Test]
    public function design_system_css_defines_full_8_step_scale(): void
    {
        $css = $this->readCss(self::DESIGN_SYSTEM_CSS);

        foreach (self::EXPECTED_SCALE as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                "design-system.css must define {$token}: {$value};"
            );
        }
    }

    #[Test]
    public function public_touch_css_mirrors_full_8_step_scale(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        foreach (self::EXPECTED_SCALE as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                "public-touch.css must mirror {$token}: {$value};"
            );
        }
    }

    #[Test]
    public function design_system_css_defines_component_padding_aliases(): void
    {
        $css = $this->readCss(self::DESIGN_SYSTEM_CSS);

        foreach (self::EXPECTED_COMPONENT_ALIASES as $alias => $reference) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($alias, '/') . '\s*:\s*' . preg_quote($reference, '/') . '\s*;/',
                $css,
                "design-system.css must define {$alias}: {$reference}; (semantic alias, not hardcoded px)."
            );
        }
    }

    #[Test]
    public function public_touch_css_mirrors_component_padding_aliases(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        foreach (self::EXPECTED_COMPONENT_ALIASES as $alias => $reference) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($alias, '/') . '\s*:\s*' . preg_quote($reference, '/') . '\s*;/',
                $css,
                "public-touch.css must mirror {$alias}: {$reference};"
            );
        }
    }

    public static function scaleStepArithmetic(): array
    {
        /*
         * The scale isn't a strict geometric doubling — early steps
         * grow by +4 (4→8→12→16), middle steps by +8 (16→24→32), and
         * upper steps by +16 (32→48→64). The pairs below capture the
         * doublings that DO hold and the AI-285-specific 1.5× ratio
         * for the new --space-7 (32 → 48). A regression that breaks
         * any of these would flag the scale was mis-edited.
         */
        return [
            'space-2 is 2× space-1' => ['--space-1', '--space-2', 2.0],
            'space-4 is 2× space-2' => ['--space-2', '--space-4', 2.0],
            'space-8 is 2× space-4' => ['--space-4', '--space-8', 4.0],
            'space-7 is 1.5× space-6' => ['--space-6', '--space-7', 1.5],
            'space-6 is 2× space-4' => ['--space-4', '--space-6', 2.0],
        ];
    }

    #[Test]
    #[DataProvider('scaleStepArithmetic')]
    public function spacing_scale_steps_follow_expected_arithmetic(
        string $smallerToken,
        string $largerToken,
        float $ratio
    ): void {
        $css = $this->readCss(self::DESIGN_SYSTEM_CSS);

        $smaller = $this->extractPxValue($css, $smallerToken);
        $larger = $this->extractPxValue($css, $largerToken);

        $this->assertSame(
            $ratio,
            $larger / $smaller,
            "Scale arithmetic broken: {$largerToken} ({$larger}px) is not {$ratio}× {$smallerToken} ({$smaller}px)."
        );
    }

    private function extractPxValue(string $css, string $token): float
    {
        $pattern = '/' . preg_quote($token, '/') . '\s*:\s*(\d+(?:\.\d+)?)px\s*;/';
        $this->assertMatchesRegularExpression($pattern, $css, "Token {$token} not found in css.");
        preg_match($pattern, $css, $m);
        return (float) $m[1];
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
