<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-210 footer + product title color unification contract test
 * (task-2026-05-13-c3a280).
 *
 * tester observed warm sandy-orange (#F4A261, from the Big2 template's
 * `--mw-header-link-color`) on product card titles alongside off-white
 * (#F8F9FA, from `--color-footer-text`) footer links — two unrelated
 * values in the same visual scan-region.
 *
 * Fix shape pinned here:
 *   - the canonical design-system token `--color-product-title` resolves
 *     to `var(--color-text-primary)` (NOT a literal hex) so it stays in
 *     palette with body copy and headings;
 *   - a companion `--color-product-title-hover` token resolves to
 *     `var(--color-primary)` so the link affordance remains
 *     discoverable on hover/focus-visible;
 *   - both tokens defined in BOTH the light root + the dark-mode
 *     selector so the two themes carry the same shape;
 *   - tokens mirrored in `Templates/Bootstrap/.../public-touch.css` so
 *     the public template loads the same values;
 *   - product card anchor + h4 selectors emit `color:
 *     var(--color-product-title) !important` (override the Big2-style
 *     hardcoded `--mw-header-link-color` set by downstream stylesheets
 *     that load after this one);
 *   - hover + focus-visible swap to `--color-product-title-hover`.
 *
 * The `!important` qualifiers are documented inline in the source —
 * required because Big2's `design-styles.css` sets `--mw-header-link-
 * color: #f4a261` on `<a>` elements and loads in the same
 * media-query-less context.
 */
class Ai210FooterAndProductTitleColorContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const PUBLIC_TOUCH_CSS_SERVED = __DIR__ . '/../../../public/templates/bootstrap/css/public-touch.css';

    public static function tokenSourceProvider(): array
    {
        return [
            'design-system.css' => [self::DESIGN_SYSTEM_CSS],
            'public-touch.css'  => [self::PUBLIC_TOUCH_CSS],
        ];
    }

    /**
     * @param string $path
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('tokenSourceProvider')]
    #[Test]
    public function color_product_title_token_routes_through_primary_text(string $path): void
    {
        $css = $this->readFile($path);

        // Pin the LIGHT-MODE :root declaration. The dark-mode override
        // is asserted separately below.
        $this->assertMatchesRegularExpression(
            '/--color-product-title:\s*var\(--color-text-primary\)\s*;/',
            $css,
            basename($path) . ' must define --color-product-title: var(--color-text-primary); — NOT a literal hex.'
        );

        // Pin the absence of the legacy #d97706 amber literal on the
        // --color-product-title line so a future refactor that revives
        // the orange outlier fails fast.
        $this->assertDoesNotMatchRegularExpression(
            '/--color-product-title:\s*#d97706/',
            $css,
            basename($path) . ' must NOT carry the legacy --color-product-title: #d97706 literal — replaced by var(--color-text-primary) per AI-210.'
        );
    }

    /**
     * @param string $path
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('tokenSourceProvider')]
    #[Test]
    public function color_product_title_hover_token_routes_through_primary(string $path): void
    {
        $css = $this->readFile($path);

        $this->assertMatchesRegularExpression(
            '/--color-product-title-hover:\s*var\(--color-primary\)\s*;/',
            $css,
            basename($path) . ' must define --color-product-title-hover: var(--color-primary); so link affordance lifts to brand primary on hover.'
        );
    }

    /**
     * @param string $path
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('tokenSourceProvider')]
    #[Test]
    public function dark_mode_override_keeps_same_token_shape(string $path): void
    {
        $css = $this->readFile($path);

        // The dark-mode block must define BOTH tokens with the same
        // `var()` shape so the two themes carry the same contract.
        $this->assertMatchesRegularExpression(
            '/(?:\[data-bs-theme="dark"\]|\.dark)[\s\S]*?--color-product-title:\s*var\(--color-text-primary\)\s*;[\s\S]*?--color-product-title-hover:\s*var\(--color-primary\)\s*;/s',
            $css,
            basename($path) . ' dark-mode block must define BOTH --color-product-title AND --color-product-title-hover as var() references (no literal hex).'
        );

        // The dark-mode block must not carry the legacy #f59e0b amber
        // literal on the --color-product-title line.
        $this->assertDoesNotMatchRegularExpression(
            '/--color-product-title:\s*#f59e0b/',
            $css,
            basename($path) . ' must NOT carry the legacy --color-product-title: #f59e0b dark-mode amber literal — replaced by var(--color-text-primary) per AI-210.'
        );
    }

    #[Test]
    public function product_card_anchor_applies_the_token_with_important(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        // Pin the selector list shape — both .shop-products .product
        // (default skin) AND .mw-online-shop-skin-1-product (skin-1)
        // must apply the token to the anchor + h4.
        $this->assertMatchesRegularExpression(
            '/\.shop-products\s+\.product\s*>\s*a,\s*\.shop-products\s+\.product\s*>\s*a\s+h4,\s*\.mw-online-shop-skin-1-product\s*>\s*a,\s*\.mw-online-shop-skin-1-product\s*>\s*a\s+h4\s*\{[^}]*color:\s*var\(--color-product-title\)\s*!important/s',
            $css,
            'Product card anchor + h4 must apply color: var(--color-product-title) !important so Big2-style hardcoded link colors cannot reintroduce the mismatch.'
        );
    }

    #[Test]
    public function product_card_hover_and_focus_swap_to_hover_token(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.shop-products\s+\.product\s*>\s*a:hover,[\s\S]*?\.shop-products\s+\.product\s*>\s*a:focus-visible,[\s\S]*?\.mw-online-shop-skin-1-product\s*>\s*a:hover,[\s\S]*?\.mw-online-shop-skin-1-product\s*>\s*a:focus-visible[\s\S]*?\{[^}]*color:\s*var\(--color-product-title-hover\)\s*!important/s',
            $css,
            'Product card anchor :hover AND :focus-visible (both default + skin-1) must swap to color: var(--color-product-title-hover) !important so keyboard users get the same affordance as pointer hover.'
        );
    }

    #[Test]
    public function existing_footer_text_rule_remains_intact(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        // The pre-existing AI-294 / earlier footer-text override on
        // every .footer-background element MUST still apply
        // --color-footer-text !important so the AI-210 work cannot
        // accidentally weaken the footer-link unification that already
        // shipped.
        $this->assertMatchesRegularExpression(
            '/\.footer-background,[\s\S]*?\.footer-background\s+a,[\s\S]*?\{[^}]*color:\s*var\(--color-footer-text\)\s*!important/s',
            $css,
            'AI-210 must NOT regress the existing .footer-background a { color: var(--color-footer-text) !important } rule — that rule already covers AC #1 of AI-210 (all footer links use the same color).'
        );
    }

    #[Test]
    public function served_public_touch_mirrors_canonical(): void
    {
        if (!file_exists(self::PUBLIC_TOUCH_CSS_SERVED)) {
            $this->markTestSkipped('Served public-touch.css missing — Templates/Bootstrap has not been published.');
        }

        $canonical = file_get_contents(self::PUBLIC_TOUCH_CSS);
        $served    = file_get_contents(self::PUBLIC_TOUCH_CSS_SERVED);

        $this->assertSame(
            $canonical,
            $served,
            'Served public/templates/bootstrap/css/public-touch.css must byte-match the canonical Templates/Bootstrap/.../public-touch.css.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
