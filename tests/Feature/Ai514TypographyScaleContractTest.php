<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-514 — Consistent typography scale across admin + checkout panels.
 *
 * Pins the four shape facts the audit (task 1.4.x) asked for:
 *
 *   1. Type scale via rem units anchored to base 16px:
 *      H1 = 2rem (32px), H2 = 1.5rem (24px), H3 = 1.25rem (20px),
 *      Body = 1rem (16px).
 *   2. Body line-height = 1.6 on `.fi-page` + `.fi-page-content`
 *      descendants.
 *   3. Secondary text color #6B7280 (Tailwind gray-500) on
 *      `.fi-section-header-description`, darkened from the audit's
 *      flagged #9CA3AF (gray-400). Dark-mode variant uses gray-400
 *      for inverse contrast.
 *   4. Font-weight 500 emphasis via `.fi-emphasis` utility class +
 *      <strong>/<b> defaults inside .fi-page / .fi-page-content.
 *
 * Out of scope (deferred to AI-514a — documented in CSS comment):
 *   - Type-scale CSS custom properties (--mw-type-h1, etc.) for
 *     per-resource override convenience.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai514TypographyScaleContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    private function ai514Block(): string
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);
        $start = strpos($css, 'AI-514 — Consistent typography scale');
        $this->assertNotFalse($start, 'mobile-touch.css must contain the AI-514 marker comment.');
        // Find the next section separator (the start of the next block) so that
        // only the AI-514 block is tested, not any subsequent blocks (e.g. AI-517).
        $nextSep = strpos($css, '/* =====', $start + 10);
        return $nextSep !== false ? substr($css, $start, $nextSep - $start) : substr($css, $start);
    }

    #[Test]
    public function ai514_marker_is_present(): void
    {
        $block = $this->ai514Block();
        $this->assertStringContainsString('AI-514 — Consistent typography scale', $block);
    }

    /**
     * Shape facts the AI-514 block must contain. Each row pins one
     * specific selector + declaration so future drift triggers a
     * deliberate decision (rather than a silent change).
     */
    public static function shapeFactsProvider(): array
    {
        return [
            // Type scale.
            'h1 selector admin + checkout' => ['body.fi-panel-admin h1'],
            'h1 size 2rem (32px)'          => ['font-size: 2rem'],
            'h2 size 1.5rem (24px)'        => ['font-size: 1.5rem'],
            'h3 size 1.25rem (20px)'       => ['font-size: 1.25rem'],
            'h4 size 1.125rem'             => ['font-size: 1.125rem'],

            // Body sizing + line-height.
            '.fi-page body size 1rem'      => ['font-size: 1rem'],
            'line-height 1.6'              => ['line-height: 1.6'],
            'admin .fi-page selector'      => ['body.fi-panel-admin .fi-page'],
            'checkout .fi-page selector'   => ['body.fi-panel-checkout .fi-page'],
            'admin .fi-page-content sel'   => ['body.fi-panel-admin .fi-page-content'],

            // Secondary text color.
            'secondary text #6B7280'       => ['color: #6B7280'],
            'section-header-description'   => ['.fi-section-header-description'],

            // Dark-mode secondary text.
            'dark secondary text #9CA3AF'  => ['color: #9CA3AF'],
            'dark-mode prefix'             => ['html.dark body.fi-panel-admin .fi-section-header-description'],

            // Emphasis weight 500.
            '.fi-emphasis class selector'  => ['body.fi-panel-admin .fi-emphasis'],
            'font-weight: 500'             => ['font-weight: 500'],
            'strong inside .fi-page'       => ['body.fi-panel-admin .fi-page strong'],
            'b inside .fi-page'            => ['body.fi-panel-admin .fi-page b'],
        ];
    }

    #[Test]
    #[DataProvider('shapeFactsProvider')]
    public function ai514_block_contains_each_shape_fact(string $needle): void
    {
        $block = $this->ai514Block();
        $this->assertStringContainsString(
            $needle,
            $block,
            "AI-514 block must contain `{$needle}`."
        );
    }

    #[Test]
    public function ai514_does_not_touch_public_storefront(): void
    {
        $block = $this->ai514Block();

        // Regression guard — public templates have their own per-template
        // typography. AI-514 must not bleed into them. Scoped strictly
        // to `body.fi-panel-admin` + `body.fi-panel-checkout`.
        $this->assertStringNotContainsString(
            'body:not(.fi-panel-admin)',
            $block,
            'AI-514 must not target non-panel surfaces.'
        );
        $this->assertStringNotContainsString(
            'body.is-front',
            $block,
            'AI-514 must not target the public-site body class.'
        );
    }

    #[Test]
    public function ai514_uses_rem_not_px(): void
    {
        $block = $this->ai514Block();

        // Rem units anchor the scale to the user's browser default
        // font-size — important for accessibility (a user with
        // larger default font sees the whole scale proportionally
        // bigger). Pinning that we did not regress to fixed px.
        $this->assertMatchesRegularExpression(
            '/font-size:\s*\d+(\.\d+)?rem/',
            $block,
            'AI-514 type scale must use rem units (not px) so it respects browser default font-size.'
        );
        // The only `px` values in the block should be in comments
        // documenting the rem-to-px conversion. The actual declarations
        // are rem-only.
        preg_match_all('/font-size:\s*\d+(\.\d+)?px\b/', $block, $pxDeclarations);
        $this->assertEmpty(
            $pxDeclarations[0],
            'AI-514 must not use px font-size declarations: ' . implode(', ', $pxDeclarations[0])
        );
    }

    #[Test]
    public function ai514_does_not_redefine_filament_panel_chrome(): void
    {
        $block = $this->ai514Block();

        // Regression guard — body sizing is scoped to `.fi-page` /
        // `.fi-page-content` descendants only. Filament tunes its
        // topbar (.fi-topbar) and sidebar (.fi-sidebar) sizing
        // separately; overriding them here would clobber the panel
        // chrome.
        $this->assertStringNotContainsString(
            'body.fi-panel-admin .fi-topbar {',
            $block,
            'AI-514 must not redefine topbar typography.'
        );
        $this->assertStringNotContainsString(
            'body.fi-panel-admin .fi-sidebar {',
            $block,
            'AI-514 must not redefine sidebar typography.'
        );
    }

    #[Test]
    public function ai514_does_not_use_important(): void
    {
        $block = $this->ai514Block();

        // Cascade order (AI-514 lives at the end of mobile-touch.css)
        // is sufficient to override Filament's defaults. !important
        // is a code-smell when cascade already gives us the win.
        // The earlier AI-512 block uses !important on error borders
        // because Filament's error styling is itself !important —
        // typography has no such conflict.
        $this->assertStringNotContainsString(
            '!important',
            $block,
            'AI-514 rules must rely on cascade order, not !important.'
        );
    }
}
