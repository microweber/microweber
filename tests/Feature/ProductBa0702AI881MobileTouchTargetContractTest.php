<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-ba0702 / AI-881 — Product module 3 code-level mobile FAILs.
 *
 * Three touch-target violations found via source inspection during the
 * Product module mobile audit (AI-881):
 *
 * FAIL #1 — Tag/category filter buttons in product-card.blade.php:78
 *   Template renders category pills as `.btn.btn-link.p-0` elements.
 *   Bootstrap btn-link + p-0 (zero padding) gives a text-height hit box of
 *   ~20px — below the WCAG 2.5.5 44px floor.
 *   Fix: `.btn.btn-link { min-height: 44px }` inside the touch-viewport @media
 *   block in public-touch.css. The CSS constraint algorithm ensures min-height
 *   wins over the zero padding and computed height.
 *
 * FAIL #2 — Slick carousel arrows in skin-4.blade.php
 *   slick.css sets .slick-prev and .slick-next to 20x30px by default.
 *   Both dimensions are below the 44px floor.
 *   Fix: `.slick-prev, .slick-next { min-width:44px; min-height:44px }` inside
 *   the touch-viewport @media block.
 *
 * FAIL #3 — Cart remove button in cart-items.blade.php:39
 *   Tailwind p-2 (8px padding) + w-5/h-5 SVG icon = 8+20+8 = 36px total height.
 *   Fix: Changed p-2 to p-3 (12px padding). Result: 12+20+12 = 44px height.
 *   Also added `.mw-cart-remove-btn` class for future CSS targeting.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class ProductBa0702AI881MobileTouchTargetContractTest extends TestCase
{
    private const PUBLIC_TOUCH = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH = 'public/templates/bootstrap/css/public-touch.css';
    private const CART_BLADE   = 'Modules/Checkout/resources/views/livewire/cart-items.blade.php';

    private string $css;
    private string $cssStripped;
    private string $cartBlade;
    private string $cartBladeStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $rawCss = (string) file_get_contents(base_path(self::PUBLIC_TOUCH));
        $this->css = $rawCss;
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawCss) ?? $rawCss;

        $rawBlade = (string) file_get_contents(base_path(self::CART_BLADE));
        $this->cartBlade = $rawBlade;
        $this->cartBladeStripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $rawBlade) ?? $rawBlade;
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present_in_public_touch_css(): void
    {
        $this->assertStringContainsString('task-2026-05-22-ba0702', $this->css,
            'public-touch.css must carry the AI-881 task marker.');
    }

    // ─── FAIL #1: .btn.btn-link tag filter touch-target ───────────────────────

    #[Test]
    public function btn_link_min_height_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.btn\.btn-link\s*\{[^}]*min-height:\s*44px~s',
            $this->cssStripped,
            'public-touch.css must add min-height: 44px to .btn.btn-link'
        );
    }

    #[Test]
    public function btn_link_rule_is_inside_touch_media_query(): void
    {
        // Find the last @media rule before the .btn.btn-link rule to confirm
        // it is inside the touch-viewport @media block.
        $rulePos = strrpos($this->cssStripped, '.btn.btn-link');
        $this->assertNotFalse($rulePos, '.btn.btn-link rule must be present');

        // Walk back to find the nearest @media opening brace
        $before  = substr($this->cssStripped, 0, (int) $rulePos);
        $lastAt  = strrpos($before, '@media');
        $this->assertNotFalse($lastAt, '.btn.btn-link must be preceded by a @media block');

        $mediaSlice = substr($this->cssStripped, (int) $lastAt, 80);
        $this->assertStringContainsString('max-width', $mediaSlice,
            '.btn.btn-link rule must sit inside the touch-viewport @media block');
    }

    #[Test]
    public function btn_link_rule_has_display_inline_flex(): void
    {
        // Verify the rule also sets display so the height is respected correctly.
        $pos = strrpos($this->cssStripped, '.btn.btn-link');
        $this->assertNotFalse($pos);
        $slice = substr($this->cssStripped, (int) $pos, 200);
        $this->assertStringContainsString('inline-flex', $slice,
            '.btn.btn-link rule must set display: inline-flex for height to take effect');
    }

    // ─── FAIL #2: .slick-prev / .slick-next arrow touch-targets ──────────────

    #[Test]
    public function slick_arrows_min_height_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.slick-prev\s*,\s*\.slick-next[^{]*\{[^}]*min-height:\s*44px~s',
            $this->cssStripped,
            'public-touch.css must add min-height: 44px to .slick-prev, .slick-next'
        );
    }

    #[Test]
    public function slick_arrows_min_width_rule_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.slick-prev\s*,\s*\.slick-next[^{]*\{[^}]*min-width:\s*44px~s',
            $this->cssStripped,
            'public-touch.css must add min-width: 44px to .slick-prev, .slick-next'
        );
    }

    #[Test]
    public function slick_arrows_rule_is_inside_touch_media_query(): void
    {
        $rulePos = strrpos($this->cssStripped, '.slick-prev');
        $this->assertNotFalse($rulePos, '.slick-prev rule must be present');

        $before = substr($this->cssStripped, 0, (int) $rulePos);
        $lastAt = strrpos($before, '@media');
        $this->assertNotFalse($lastAt, '.slick-prev must be preceded by a @media block');

        $mediaSlice = substr($this->cssStripped, (int) $lastAt, 80);
        $this->assertStringContainsString('max-width', $mediaSlice,
            '.slick-prev/.slick-next must sit inside the touch-viewport @media block');
    }

    // ─── FAIL #3: Cart remove button p-3 fix ─────────────────────────────────

    #[Test]
    public function cart_remove_button_uses_p3_padding(): void
    {
        // The remove button changed from p-2 (8px, gives 36px total) to p-3
        // (12px, gives 12+20+12=44px total). Verify the fix is present.
        $this->assertMatchesRegularExpression(
            '~rounded-md\s+bg-red-600[^"]*p-3~',
            $this->cartBladeStripped,
            'Remove button must use p-3 (12px padding, 44px total height) not p-2'
        );
    }

    #[Test]
    public function cart_remove_button_no_longer_uses_p2(): void
    {
        // Negative guard: the red remove button must not use p-2 any more.
        // Strip blade comments first (selector-self-match guard).
        $this->assertDoesNotMatchRegularExpression(
            '~rounded-md\s+bg-red-600[^"]*p-2~',
            $this->cartBladeStripped,
            'Remove button must NOT use p-2 (was 36px — below 44px WCAG 2.5.5 floor)'
        );
    }

    #[Test]
    public function cart_remove_button_has_mw_class(): void
    {
        // The .mw-cart-remove-btn class was added alongside p-3 for future CSS targeting.
        $this->assertStringContainsString('mw-cart-remove-btn', $this->cartBladeStripped,
            'Remove button must carry the .mw-cart-remove-btn class for future CSS hooks');
    }

    // ─── Byte-identical source ↔ served mirror ────────────────────────────────

    #[Test]
    public function served_mirror_is_byte_identical_to_source(): void
    {
        $source = (string) file_get_contents(base_path(self::PUBLIC_TOUCH));
        $served = (string) file_get_contents(base_path(self::SERVED_TOUCH));
        $this->assertSame($source, $served,
            'public/templates/bootstrap/css/public-touch.css must be byte-identical to source');
    }

    // ─── Regression guards ────────────────────────────────────────────────────

    #[Test]
    public function existing_product_module_touch_targets_unchanged(): void
    {
        // AI-585 slider-nav + AI-603 slider-button rules must still be present.
        $this->assertStringContainsString('mw-slider-v2-buttons-slide', $this->cssStripped,
            'AI-585 slider nav button rule must remain');
        $this->assertStringContainsString('slider-button', $this->cssStripped,
            'AI-603 slider CTA rule must remain');
    }
}
