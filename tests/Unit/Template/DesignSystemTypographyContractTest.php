<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-283 Typography Hierarchy contract test.
 *
 * Pins the H1→H6 + Body + Small font-size scale and the two
 * line-height tokens to specific values in design-system.css (the
 * canonical source) AND in the public-side mirror in
 * Templates/Bootstrap/resources/assets/css/public-touch.css.
 *
 * Fails fast in unit CI if a future refactor renames, removes, or
 * changes the value of any token — both the design tokens themselves
 * and the global type rules that apply them to h1..h6 / body / small.
 *
 * The pinned values come from JIRA AI-283 acceptance criteria:
 *   H1=32px, H2=28px, H3=24px, H4=20px, H5=18px, H6=16px,
 *   Body=16px, Small=14px,
 *   line-height: 1.25 headings, 1.5 body.
 */
class DesignSystemTypographyContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    private const EXPECTED_TOKENS = [
        '--font-size-h1' => '32px',
        '--font-size-h2' => '28px',
        '--font-size-h3' => '24px',
        '--font-size-h4' => '20px',
        '--font-size-h5' => '18px',
        '--font-size-h6' => '16px',
        '--font-size-body' => '16px',
        '--font-size-small' => '14px',
        '--line-height-heading' => '1.25',
        '--line-height-body' => '1.5',
    ];

    #[Test]
    public function design_system_css_defines_every_ai283_token(): void
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
    public function public_touch_css_mirrors_every_ai283_token(): void
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
    public function public_touch_css_applies_tokens_to_h1_through_h6(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $tag) {
            $this->assertMatchesRegularExpression(
                '/^\s*' . $tag . '\s*\{[^}]*font-size:\s*var\(--font-size-' . $tag . '\)[^}]*\}/m',
                $css,
                "public-touch.css must apply var(--font-size-{$tag}) to {$tag} element."
            );
            $this->assertMatchesRegularExpression(
                '/^\s*' . $tag . '\s*\{[^}]*line-height:\s*var\(--line-height-heading\)[^}]*\}/m',
                $css,
                "public-touch.css must apply var(--line-height-heading) to {$tag} element."
            );
        }
    }

    #[Test]
    public function public_touch_css_applies_body_token_to_body_element(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/^\s*body\s*\{[^}]*font-size:\s*var\(--font-size-body\)[^}]*\}/m',
            $css,
            'public-touch.css must apply var(--font-size-body) to body element.'
        );
        $this->assertMatchesRegularExpression(
            '/^\s*body\s*\{[^}]*line-height:\s*var\(--line-height-body\)[^}]*\}/m',
            $css,
            'public-touch.css must apply var(--line-height-body) to body element.'
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
