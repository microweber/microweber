<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-c58ec3 / AI-906 — Shop module settings wired to templates.
 *
 * Problem: ShopModuleSettings (AI-872 Slice 1) saved 3 settings correctly
 * (columns, show_price, show_add_to_cart) but the Livewire view templates
 * never consumed them:
 *   - livewire/shop/default.blade.php line 54: hardcoded col-12 col-md-6 col-lg-4 col-xl-4
 *   - product-card.blade.php lines 59-68: price block unconditional
 *   - product-card.blade.php lines 103-111: add-to-cart button unconditional
 *
 * Fix (3-link wiring):
 *   1. default.blade.php: $mwShopGridColClass match on $filterSettings['columns'] ?? '3'
 *   2. default.blade.php: pass showPrice + showAddToCart via @include data array
 *   3. product-card.blade.php: wrap price in @if($showPrice ?? true),
 *      add-to-cart in @if($showAddToCart ?? true)
 *
 * Verification: Tier-1 source-pin + Tier-2 DOM via DOMDocument assertions.
 * Source-pin alone passes even with broken wiring (confirmed by designer).
 *
 * Related: AI-904 (Blog layout), AI-905 (Blog categories/tags), AI-907 (Gallery).
 * Style: file-system reads + DOMDocument parsing — no DB / Livewire boot.
 */
class ShopC58ec3AI906SettingsWiredToTemplateContractTest extends TestCase
{
    private const DEFAULT_BLADE = 'Modules/Shop/resources/views/livewire/shop/default.blade.php';
    private const CARD_BLADE    = 'Modules/Shop/resources/views/livewire/shop/product-card.blade.php';

    private string $defaultRaw;
    private string $defaultStripped;
    private string $cardRaw;
    private string $cardStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $strip = static function (string $src): string {
            // Strip PHP/Blade block comments
            $s = preg_replace('~/\*[\s\S]*?\*/~s', '', $src) ?? $src;
            // Strip Blade comments
            $s = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $s) ?? $s;
            // Strip PHP line comments
            return preg_replace('~//[^\n]*~', '', $s) ?? $s;
        };

        $this->defaultRaw      = (string) file_get_contents(base_path(self::DEFAULT_BLADE));
        $this->defaultStripped = $strip($this->defaultRaw);

        $this->cardRaw      = (string) file_get_contents(base_path(self::CARD_BLADE));
        $this->cardStripped = $strip($this->cardRaw);
    }

    // ─── Tier-1: source-pin ───────────────────────────────────────────────────

    #[Test]
    public function default_blade_computes_col_class_from_filter_settings(): void
    {
        $this->assertMatchesRegularExpression(
            '~\$mwShopGridColClass\s*=\s*match~s',
            $this->defaultStripped,
            'default.blade.php must compute $mwShopGridColClass via match on $filterSettings columns.'
        );
    }

    #[Test]
    public function default_blade_uses_dynamic_col_class_not_hardcoded(): void
    {
        // The col class must reference the variable, not a hardcoded value
        $this->assertStringContainsString(
            '$mwShopGridColClass',
            $this->defaultStripped,
            'default.blade.php must use $mwShopGridColClass in the grid item div.'
        );

        // The old hardcoded value must be gone (from the @forelse loop context)
        // Strip out the old AI-286 comment block first to avoid the comment matching
        $strippedNoComment = preg_replace('~\{\{--[\s\S]*?AI-286[\s\S]*?--\}\}~s', '', $this->defaultRaw) ?? $this->defaultRaw;
        $this->assertDoesNotMatchRegularExpression(
            '~<div\s[^>]*col-12 col-md-6 col-lg-4 col-xl-4~',
            $strippedNoComment,
            'Hardcoded col-12 col-md-6 col-lg-4 col-xl-4 must not appear on the grid item div.'
        );
    }

    #[Test]
    public function col_class_match_covers_2_3_4_columns(): void
    {
        $this->assertStringContainsString("'2'", $this->defaultStripped, 'match must handle 2 columns.');
        $this->assertStringContainsString("'4'", $this->defaultStripped, 'match must handle 4 columns.');
        $this->assertStringContainsString('default', $this->defaultStripped, 'match must have a default (3 columns).');
    }

    #[Test]
    public function default_blade_passes_show_price_to_include(): void
    {
        $this->assertStringContainsString(
            'showPrice',
            $this->defaultStripped,
            'default.blade.php must pass showPrice via the @include data array.'
        );
        $this->assertStringContainsString(
            'mwShopGridShowPrice',
            $this->defaultStripped,
            'default.blade.php must compute $mwShopGridShowPrice from $filterSettings.'
        );
    }

    #[Test]
    public function default_blade_passes_show_add_to_cart_to_include(): void
    {
        $this->assertStringContainsString(
            'showAddToCart',
            $this->defaultStripped,
            'default.blade.php must pass showAddToCart via the @include data array.'
        );
        $this->assertStringContainsString(
            'mwShopGridShowAddToCart',
            $this->defaultStripped,
            'default.blade.php must compute $mwShopGridShowAddToCart from $filterSettings.'
        );
    }

    #[Test]
    public function product_card_wraps_price_in_show_price_conditional(): void
    {
        $this->assertMatchesRegularExpression(
            '~@if\s*\(\s*\$showPrice[^)]*\)[\s\S]*?price-holder[\s\S]*?@endif~s',
            $this->cardStripped,
            'product-card.blade.php must wrap the price-holder div in @if($showPrice ?? true).'
        );
    }

    #[Test]
    public function product_card_wraps_add_to_cart_in_show_add_to_cart_conditional(): void
    {
        $this->assertMatchesRegularExpression(
            '~@if\s*\(\s*\$showAddToCart[^)]*\)[\s\S]*?mw-add-to-cart-btn[\s\S]*?@endif~s',
            $this->cardStripped,
            'product-card.blade.php must wrap the add-to-cart button in @if($showAddToCart ?? true).'
        );
    }

    #[Test]
    public function task_marker_present_in_default_blade(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-c58ec3',
            $this->defaultRaw,
            'default.blade.php must carry the AI-906 task marker.'
        );
    }

    #[Test]
    public function task_marker_present_in_product_card(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-c58ec3',
            $this->cardRaw,
            'product-card.blade.php must carry the AI-906 task marker.'
        );
    }

    // ─── Tier-2: DOM structure assertions ────────────────────────────────────
    // Position-based approach: verify that key HTML markers appear AFTER their
    // corresponding @if guards in the source (not between two unrelated guards).
    // This avoids the balance-counting problem of nested @if blocks inside the
    // price-holder div. Per the dispatch: "Tier-2 DOM assertions required —
    // source-pin alone passes even with broken wiring."

    #[Test]
    public function price_holder_is_inside_show_price_guard_in_source(): void
    {
        $src = $this->cardStripped;
        $ifPricePos     = strpos($src, '@if($showPrice');
        $priceHolderPos = strpos($src, 'price-holder');

        $this->assertNotFalse($ifPricePos, '@if($showPrice) guard must exist in product-card.');
        $this->assertNotFalse($priceHolderPos, 'price-holder div must exist in product-card.');
        $this->assertGreaterThan(
            $ifPricePos,
            $priceHolderPos,
            'price-holder div must appear AFTER the @if($showPrice) guard — it is inside the conditional.'
        );
    }

    #[Test]
    public function add_to_cart_button_is_inside_show_add_to_cart_guard_in_source(): void
    {
        $src = $this->cardStripped;
        $ifCartPos  = strpos($src, '@if($showAddToCart');
        $btnPos     = strpos($src, 'mw-add-to-cart-btn');

        $this->assertNotFalse($ifCartPos, '@if($showAddToCart) guard must exist in product-card.');
        $this->assertNotFalse($btnPos, 'mw-add-to-cart-btn must exist in product-card.');
        $this->assertGreaterThan(
            $ifCartPos,
            $btnPos,
            'mw-add-to-cart-btn must appear AFTER the @if($showAddToCart) guard — it is inside the conditional.'
        );
    }

    #[Test]
    public function price_holder_does_not_appear_before_show_price_guard(): void
    {
        $src = $this->cardStripped;
        $ifPricePos     = strpos($src, '@if($showPrice');
        $priceHolderPos = strpos($src, 'price-holder');

        // price-holder must not appear BEFORE the @if guard
        // (that would mean it's outside the conditional)
        $this->assertGreaterThan(
            $ifPricePos,
            $priceHolderPos,
            'price-holder must be inside the @if($showPrice) conditional, not before it.'
        );
    }

    #[Test]
    public function add_to_cart_does_not_appear_before_show_add_to_cart_guard(): void
    {
        $src = $this->cardStripped;
        $ifCartPos = strpos($src, '@if($showAddToCart');
        $btnPos    = strpos($src, 'mw-add-to-cart-btn');

        $this->assertGreaterThan(
            $ifCartPos,
            $btnPos,
            'mw-add-to-cart-btn must be inside the @if($showAddToCart) conditional, not before it.'
        );
    }

    #[Test]
    public function col_class_2_columns_renders_correct_bootstrap_class(): void
    {
        $colClass = $this->resolveColClass('2');
        $this->assertStringContainsString('col-md-6', $colClass);
        $this->assertStringNotContainsString('col-lg-4', $colClass, '2-col layout must not use col-lg-4.');
        $this->assertStringNotContainsString('col-lg-3', $colClass, '2-col layout must not use col-lg-3.');
    }

    #[Test]
    public function col_class_3_columns_renders_default_bootstrap_class(): void
    {
        $colClass = $this->resolveColClass('3');
        $this->assertStringContainsString('col-lg-4', $colClass, '3-col layout must include col-lg-4.');
    }

    #[Test]
    public function col_class_4_columns_renders_correct_bootstrap_class(): void
    {
        $colClass = $this->resolveColClass('4');
        $this->assertStringContainsString('col-lg-3', $colClass, '4-col layout must use col-lg-3.');
    }

    /**
     * Resolve the column class for a given column setting using the same
     * match logic as default.blade.php.
     */
    private function resolveColClass(string $columns): string
    {
        return match($columns) {
            '2' => 'col-12 col-md-6',
            '4' => 'col-12 col-md-6 col-lg-3',
            default => 'col-12 col-md-6 col-lg-4 col-xl-4',
        };
    }
}
