<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-288 / AI-289 / AI-290 P2 bundle contract test.
 *
 * Pins:
 *   - AI-288 Motion: 3 duration tokens + 3 easing curves.
 *   - AI-289 Elevation: 4 shadow-* tokens, 3 legacy --shadow-1/2/3
 *     alias references, 8 z-index tokens, .card hover rule.
 *   - AI-290 Icons: 5 --icon-size-* tokens, 5 .icon-* CSS classes.
 *
 * Bundled as one test class because all three tickets shipped in the
 * same commit and share the same canonical + mirror pair. Keeping
 * them in one file makes the pinning visible in a single place.
 *
 * Fails fast in unit CI if a future refactor drifts a value, renames
 * a token, drops the legacy shadow aliases (would break pre-AI-289
 * consumers), or removes the icon-size class shape.
 */
class DesignSystemP2BundleContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    // -------------------------------------------------------------------
    // AI-288 Motion
    // -------------------------------------------------------------------
    private const EXPECTED_MOTION_TOKENS = [
        '--duration-fast' => '150ms',
        '--duration-normal' => '250ms',
        '--duration-slow' => '350ms',
        '--ease-default' => 'cubic-bezier(0.4, 0, 0.2, 1)',
        '--ease-in' => 'cubic-bezier(0.4, 0, 1, 1)',
        '--ease-out' => 'cubic-bezier(0, 0, 0.2, 1)',
    ];

    // -------------------------------------------------------------------
    // AI-289 Elevation + z-index
    // -------------------------------------------------------------------
    private const EXPECTED_SHADOW_TOKENS = [
        '--shadow-sm' => '0 1px 3px rgba(0, 0, 0, 0.1)',
        '--shadow-md' => '0 4px 6px rgba(0, 0, 0, 0.1)',
        '--shadow-lg' => '0 10px 15px rgba(0, 0, 0, 0.1)',
        '--shadow-xl' => '0 20px 40px rgba(0, 0, 0, 0.15)',
    ];

    private const EXPECTED_LEGACY_SHADOW_ALIASES = [
        '--shadow-1' => 'var(--shadow-sm)',
        '--shadow-2' => 'var(--shadow-md)',
        '--shadow-3' => 'var(--shadow-lg)',
    ];

    private const EXPECTED_Z_INDEX_TOKENS = [
        '--z-base' => '0',
        '--z-sticky' => '10',
        '--z-dropdown' => '20',
        '--z-fixed' => '30',
        '--z-overlay' => '40',
        '--z-popover' => '50',
        '--z-modal' => '100',
        '--z-toast' => '200',
    ];

    // -------------------------------------------------------------------
    // AI-290 Icons
    // -------------------------------------------------------------------
    private const EXPECTED_ICON_TOKENS = [
        '--icon-size-xs' => '12px',
        '--icon-size-sm' => '16px',
        '--icon-size-md' => '20px',
        '--icon-size-lg' => '24px',
        '--icon-size-xl' => '32px',
    ];

    #[Test]
    public function design_system_css_defines_every_motion_token(): void
    {
        $this->assertTokensPresent(self::DESIGN_SYSTEM_CSS, self::EXPECTED_MOTION_TOKENS);
    }

    #[Test]
    public function public_touch_css_mirrors_every_motion_token(): void
    {
        $this->assertTokensPresent(self::PUBLIC_TOUCH_CSS, self::EXPECTED_MOTION_TOKENS);
    }

    #[Test]
    public function design_system_css_defines_every_shadow_token_and_legacy_alias(): void
    {
        $this->assertTokensPresent(self::DESIGN_SYSTEM_CSS, self::EXPECTED_SHADOW_TOKENS);
        $this->assertTokensPresent(self::DESIGN_SYSTEM_CSS, self::EXPECTED_LEGACY_SHADOW_ALIASES);
    }

    #[Test]
    public function public_touch_css_mirrors_shadow_tokens(): void
    {
        $this->assertTokensPresent(self::PUBLIC_TOUCH_CSS, self::EXPECTED_SHADOW_TOKENS);
    }

    #[Test]
    public function design_system_css_defines_every_z_index_token(): void
    {
        $this->assertTokensPresent(self::DESIGN_SYSTEM_CSS, self::EXPECTED_Z_INDEX_TOKENS);
    }

    #[Test]
    public function public_touch_css_mirrors_z_index_tokens(): void
    {
        $this->assertTokensPresent(self::PUBLIC_TOUCH_CSS, self::EXPECTED_Z_INDEX_TOKENS);
    }

    #[Test]
    public function design_system_css_defines_every_icon_size_token(): void
    {
        $this->assertTokensPresent(self::DESIGN_SYSTEM_CSS, self::EXPECTED_ICON_TOKENS);
    }

    #[Test]
    public function public_touch_css_mirrors_icon_size_tokens(): void
    {
        $this->assertTokensPresent(self::PUBLIC_TOUCH_CSS, self::EXPECTED_ICON_TOKENS);
    }

    #[Test]
    public function public_touch_css_hover_elevates_card_to_shadow_md(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.card:hover\s*\{[^}]*box-shadow:\s*var\(--shadow-md\)/s',
            $css,
            '.card:hover must elevate to var(--shadow-md) per AI-289 acceptance.'
        );
        $this->assertMatchesRegularExpression(
            '/\.card\s*\{[^}]*transition:[^}]*box-shadow\s+var\(--duration-normal\)\s+var\(--ease-default\)/s',
            $css,
            '.card must transition box-shadow under the AI-288 motion tokens (duration-normal + ease-default).'
        );
    }

    #[Test]
    public function public_touch_css_defines_five_icon_size_classes(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        foreach (['xs', 'sm', 'md', 'lg', 'xl'] as $size) {
            $this->assertMatchesRegularExpression(
                '/\.icon-' . $size . '\s*\{[^}]*width:\s*var\(--icon-size-' . $size . '\)[^}]*height:\s*var\(--icon-size-' . $size . '\)/s',
                $css,
                ".icon-{$size} must apply var(--icon-size-{$size}) to both width and height."
            );
        }
    }

    #[Test]
    public function icon_classes_share_baseline_alignment_rule(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        // All five icon classes share the alignment rule via comma
        // grouping; verify the grouped selector exists with the
        // baseline-correction vertical-align.
        $this->assertMatchesRegularExpression(
            '/\.icon-xs,\s*\.icon-sm,\s*\.icon-md,\s*\.icon-lg,\s*\.icon-xl\s*\{[^}]*vertical-align:\s*-0\.125em/s',
            $css,
            'Icon classes must share `vertical-align: -0.125em` to sit on the text baseline.'
        );
    }

    // -------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------

    /**
     * @param array<string, string> $tokens token-name => expected-value
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
