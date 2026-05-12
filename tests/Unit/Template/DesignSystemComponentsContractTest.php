<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-287 Component Consistency contract test.
 *
 * Pins the canonical component tokens (button radius/padding for
 * primary + secondary, card radius/padding/shadow) and the CSS rules
 * that apply them to Bootstrap classes (.btn-primary, .btn-secondary
 * / .btn-outline-primary / .btn-outline-secondary, .card).
 *
 * Fails fast in unit CI if a future refactor:
 *   - drifts a radius value (6px → 4px)
 *   - drifts the card shadow rgba opacity
 *   - drops the `.btn.btn-primary { border-radius: var(--btn-primary-radius); }`
 *     rule (which is what makes the design tokens actually take effect)
 *   - decouples --card-padding from --space-card (would let the AI-287
 *     and AI-285 specs drift independently)
 */
class DesignSystemComponentsContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    private const EXPECTED_TOKENS = [
        '--btn-primary-radius' => '6px',
        '--btn-primary-padding-y' => '8px',
        '--btn-primary-padding-x' => '16px',
        '--btn-secondary-radius' => '6px',
        '--btn-secondary-padding-y' => '6px',
        '--btn-secondary-padding-x' => '12px',
        '--card-radius' => '8px',
        '--card-shadow' => '0 2px 8px rgba(0, 0, 0, 0.1)',
    ];

    #[Test]
    public function design_system_css_defines_every_component_token(): void
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
    public function public_touch_css_mirrors_every_component_token(): void
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
    public function card_padding_references_space_card_alias_not_hardcoded_px(): void
    {
        // The --card-padding token must point at the AI-285 semantic
        // alias --space-card so changing card density is a one-token
        // change (and the AI-287 / AI-285 specs can't drift apart).
        foreach ([self::DESIGN_SYSTEM_CSS, self::PUBLIC_TOUCH_CSS] as $path) {
            $css = $this->readCss($path);
            $this->assertMatchesRegularExpression(
                '/--card-padding\s*:\s*var\(--space-card\)\s*;/',
                $css,
                basename($path) . ' must define --card-padding: var(--space-card); (not a hardcoded px value).'
            );
        }
    }

    #[Test]
    public function btn_primary_rule_applies_radius_and_padding_tokens(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.btn\.btn-primary\s*\{[^}]*border-radius:\s*var\(--btn-primary-radius\)/s',
            $css,
            '.btn.btn-primary must apply var(--btn-primary-radius).'
        );
        $this->assertMatchesRegularExpression(
            '/\.btn\.btn-primary\s*\{[^}]*padding:\s*var\(--btn-primary-padding-y\)\s+var\(--btn-primary-padding-x\)/s',
            $css,
            '.btn.btn-primary must apply var() padding tokens (y then x).'
        );
    }

    #[Test]
    public function btn_secondary_and_outline_rules_apply_secondary_tokens(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        // Secondary rule covers solid + both outline variants.
        $this->assertMatchesRegularExpression(
            '/\.btn\.btn-secondary,\s*\.btn\.btn-outline-primary,\s*\.btn\.btn-outline-secondary\s*\{/s',
            $css,
            'Secondary button rule must cover .btn-secondary, .btn-outline-primary, .btn-outline-secondary.'
        );
        $this->assertMatchesRegularExpression(
            '/\.btn\.btn-secondary,\s*\.btn\.btn-outline-primary,\s*\.btn\.btn-outline-secondary\s*\{[^}]*border-radius:\s*var\(--btn-secondary-radius\)/s',
            $css,
            'Secondary button rule must apply var(--btn-secondary-radius).'
        );
    }

    #[Test]
    public function card_rule_applies_radius_padding_and_shadow(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.card\s*\{[^}]*border-radius:\s*var\(--card-radius\)/s',
            $css,
            '.card must apply var(--card-radius).'
        );
        $this->assertMatchesRegularExpression(
            '/\.card\s*\{[^}]*padding:\s*var\(--card-padding\)/s',
            $css,
            '.card must apply var(--card-padding).'
        );
        $this->assertMatchesRegularExpression(
            '/\.card\s*\{[^}]*box-shadow:\s*var\(--card-shadow\)/s',
            $css,
            '.card must apply var(--card-shadow).'
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
