<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-294 Footer Height contract test.
 *
 * Pins the four footer tokens, the `.footer-background` padding rule,
 * the mobile column-gap compression rule, and the duplicate-address
 * hide rule. Fails fast in unit CI if a future refactor:
 *   - loosens the mobile padding back to the pre-AI-294 spacer-based
 *     defaults
 *   - drops the column-gap override (would let `mb-4` add 24px back
 *     to each stacked column)
 *   - drops the duplicate-address `display: none` rule (would let
 *     the secondary address block expand the footer past 240px)
 */
class DesignSystemFooterContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    private const EXPECTED_TOKENS = [
        '--footer-padding-top' => '40px',
        '--footer-padding-bottom' => '24px',
        '--footer-column-gap-mobile' => 'var(--space-2)',
        '--footer-max-height-mobile' => '240px',
    ];

    #[Test]
    public function design_system_css_defines_every_footer_token(): void
    {
        $this->assertTokensPresent(self::DESIGN_SYSTEM_CSS, self::EXPECTED_TOKENS);
    }

    #[Test]
    public function public_touch_css_mirrors_every_footer_token(): void
    {
        $this->assertTokensPresent(self::PUBLIC_TOUCH_CSS, self::EXPECTED_TOKENS);
    }

    #[Test]
    public function footer_padding_uses_tokens_not_hardcoded_px(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.footer-background\s*\{[^}]*padding-top:\s*var\(--footer-padding-top\)/s',
            $css,
            '.footer-background must apply var(--footer-padding-top), not a hardcoded value.'
        );
        $this->assertMatchesRegularExpression(
            '/\.footer-background\s*\{[^}]*padding-bottom:\s*var\(--footer-padding-bottom\)/s',
            $css,
            '.footer-background must apply var(--footer-padding-bottom).'
        );
    }

    #[Test]
    public function mobile_breakpoint_compresses_column_gap(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        // The compression rule MUST live inside an @media (max-width: 575.98px)
        // block — that's the AI-286 sm-breakpoint boundary.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*575\.98px\)\s*\{[^@]*\.footer-background\s+\.row\s*>\s*\[class\*="col-"\]\.mb-4\s*\{[^}]*margin-bottom:\s*var\(--footer-column-gap-mobile\)/s',
            $css,
            'Footer mobile rule must compress .mb-4 to var(--footer-column-gap-mobile) inside @media (max-width: 575.98px).'
        );
    }

    #[Test]
    public function mobile_breakpoint_hides_duplicate_address(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        // The duplicate-address hide rule targets the second <small>
        // and its <p> sibling inside .col-lg-3.
        $this->assertMatchesRegularExpression(
            '/\.footer-background\s+\.col-lg-3\s*>\s*small:nth-of-type\(2\),\s*\.footer-background\s+\.col-lg-3\s*>\s*small:nth-of-type\(2\)\s*\+\s*p\s*\{[^}]*display:\s*none/s',
            $css,
            'Mobile rule must hide the duplicate (second) address block via display: none.'
        );
    }

    /**
     * @param array<string, string> $tokens
     */
    private function assertTokensPresent(string $path, array $tokens): void
    {
        $css = $this->readCss($path);

        foreach ($tokens as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                basename($path) . " must define {$token}: {$value};"
            );
        }
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
