<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-328124 / AI-862 — Bootstrap grid math fix for the /shop
 * filter sidebar at tablet 768 (iPad portrait).
 * Jira: https://microweber.atlassian.net/browse/AI-862
 *
 * Designer's Round 23 tablet 768 sweep caught the /shop filter sidebar
 * rendering 116px wide at iPad portrait — the unique viewport where
 * Bootstrap `col-md-2` hits the legibility cliff ("Discount filter"
 * label collapsed to 1px wide). The sidebar + grid columns summed to
 * 11 (col-md-2 + col-md-9), leaving a visible ~60px dead-space band.
 *
 * Why this hid until Round 23:
 *   - 1440 desktop: col-md-2 inherits to col-lg-2 = 197px (cramped legible)
 *   - 390 mobile: col-12 stacks (no md/lg cascade involved)
 *   - 768 is the unique viewport where col-md-2 = exactly 116px
 *
 * Fix shape (Option A2 per designer — preferred over A1 lighter touch):
 * stack at md, sidebar-only at lg+. Both columns hit `col-12` at md (≥768)
 * so the filter rail stacks ABOVE the product grid for touch-easier
 * tablet UX; at lg+ (≥992) sidebar returns as `col-lg-3` and grid as
 * `col-lg-9`, summing to 12 (no dead-space band). 1440 desktop layout
 * unchanged in spirit — sidebar present, just renders 240px @ 992 /
 * 300px @ 1440 vs. the pre-fix 197px @ 1440 (slight widen).
 *
 * Trade-off named + accepted by designer: ~1 row of vertical filter
 * pills at tablet portrait = legibility win.
 *
 * Tier-3 acceptance (verified at HEAD via curl):
 *   - /shop carries `col-12 col-lg-3` filter rail (1 instance per template render)
 *   - /shop carries `col-12 col-lg-9` product grid (1 instance per template render)
 *   - /shop has ZERO `col-12 col-md-2` (legacy 11-col shape eliminated)
 *   - /shop has ZERO `col-12 col-md-9` (legacy 11-col shape eliminated)
 *
 * 4-group structure: A = default.blade.php source-presence (new shape
 * present + legacy absent); B = skin-1.blade.php sibling skin same
 * coverage; C = back-compat regression sentinels (existing tags filter +
 * livewire bindings + product grid responsive shape AI-286 preserved);
 * D = pattern-set integrity (sum-to-12 + breakpoint-jump-at-lg invariants).
 */
class Shop328124AI862SidebarTabletGridContractTest extends TestCase
{
    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    private function stripBladeAndPhpComments(string $source): string
    {
        $source = preg_replace('~\{\{--.*?--\}\}~s', '', $source);
        $source = preg_replace('~/\*.*?\*/~s', '', (string) $source);
        $source = preg_replace('~//[^\n]*~', '', (string) $source);
        return (string) $source;
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Modules/Shop/.../default.blade.php source-presence
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function default_template_carries_new_lg_breakpoint_filter_sidebar(): void
    {
        $source = $this->read('Modules/Shop/resources/views/livewire/shop/default.blade.php');
        $this->assertStringContainsString('<div class="col-12 col-lg-3">', $source, 'default.blade.php filter sidebar MUST be `col-12 col-lg-3` (Option A2 — stack at md, sidebar at lg+).');
        $this->assertStringContainsString('<div class="col-12 col-lg-9">', $source, 'default.blade.php product grid MUST be `col-12 col-lg-9` (matches sidebar breakpoint, sums to 12 at lg+).');
        $this->assertStringContainsString('task-2026-05-17-328124', $source, 'AI-862 task-id marker required for cross-surface grep.');
        $this->assertStringContainsString('AI-862', $source);
    }

    #[Test]
    public function default_template_does_not_carry_legacy_md_2_or_md_9_grid_shape(): void
    {
        $source = $this->stripBladeAndPhpComments(
            $this->read('Modules/Shop/resources/views/livewire/shop/default.blade.php')
        );
        // Strip language comments before negative absence assertion so
        // docblock prose (which mentions the legacy shape in the AI-862
        // rationale) does NOT self-match (LESSONS selector-self-match
        // UNIFORMITY rule — 19+ session-recurrences).
        $this->assertStringNotContainsString('<div class="col-12 col-md-2">', $source, 'Legacy `col-12 col-md-2` filter rail MUST be eliminated.');
        $this->assertStringNotContainsString('<div class="col-12 col-md-9">', $source, 'Legacy `col-12 col-md-9` grid pane MUST be eliminated.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Modules/Shop/.../skin-1.blade.php sibling skin
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function skin_1_template_carries_new_lg_breakpoint_filter_sidebar(): void
    {
        $source = $this->read('Modules/Shop/resources/views/livewire/shop/skin-1.blade.php');
        $this->assertStringContainsString('<div class="col-12 col-lg-3">', $source, 'skin-1.blade.php filter sidebar MUST be `col-12 col-lg-3` (sibling-skin parity with default.blade.php).');
        $this->assertStringContainsString('<div class="col-12 col-lg-9">', $source, 'skin-1.blade.php product grid MUST be `col-12 col-lg-9`.');
        $this->assertStringContainsString('task-2026-05-17-328124', $source);
        $this->assertStringContainsString('AI-862', $source);
    }

    #[Test]
    public function skin_1_template_does_not_carry_legacy_md_2_or_md_9_grid_shape(): void
    {
        $source = $this->stripBladeAndPhpComments(
            $this->read('Modules/Shop/resources/views/livewire/shop/skin-1.blade.php')
        );
        $this->assertStringNotContainsString('<div class="col-12 col-md-2">', $source);
        $this->assertStringNotContainsString('<div class="col-12 col-md-9">', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — back-compat regression sentinels (existing features stay)
    // ─────────────────────────────────────────────────────────────────────

    /**
     * @return array<string, array{0: string}>
     */
    public static function shopTemplatePaths(): array
    {
        return [
            'default' => ['Modules/Shop/resources/views/livewire/shop/default.blade.php'],
            'skin-1'  => ['Modules/Shop/resources/views/livewire/shop/skin-1.blade.php'],
        ];
    }

    #[Test]
    #[DataProvider('shopTemplatePaths')]
    public function shop_template_preserves_filter_includes(string $path): void
    {
        $source = $this->read($path);
        $this->assertStringContainsString("@include('modules.shop::livewire.shop.filters.categories.index')", $source);
        $this->assertStringContainsString("@include('modules.shop::livewire.shop.filters.price_range.index')", $source);
        $this->assertStringContainsString("@include('modules.shop::livewire.shop.filters.offers.index')", $source);
        $this->assertStringContainsString("@include('modules.shop::livewire.shop.filters.custom_fields.index')", $source);
        $this->assertStringContainsString("@include('modules.shop::livewire.shop.filters.tags.index')", $source);
        $this->assertStringContainsString("@include('modules.shop::livewire.shop.filters.top.index')", $source);
    }

    #[Test]
    #[DataProvider('shopTemplatePaths')]
    public function shop_template_preserves_aria_live_grid(string $path): void
    {
        $source = $this->read($path);
        $this->assertMatchesRegularExpression(
            '/aria-live="polite"\s+wire:loading\.attr="aria-busy"/',
            $source,
            'aria-live grid + wire:loading.attr aria-busy contract MUST stay intact (a11y commitment).'
        );
    }

    #[Test]
    public function default_template_preserves_ai286_responsive_product_grid(): void
    {
        // AI-286 set the 3-col (lg/xl) → 2-col (md) → 1-col (sm) product
        // card grid responsive shape. AI-862 must NOT touch the inner
        // product-card grid responsive classes.
        //
        // Updated 2026-06 (task-2026-05-22-c58ec3 / AI-906): the grid
        // class is now configurable via a match() on the saved Columns
        // setting. The AI-286 cascade is the DEFAULT arm
        // (`col-12 col-md-6 col-lg-4 col-xl-4`), and the `mb-5` spacing
        // moved onto the wrapping <div class="{{ $mwShopGridColClass }} mb-5">.
        // Assert the default-arm cascade is still the 1 → 2 → 3-col shape.
        $source = $this->read('Modules/Shop/resources/views/livewire/shop/default.blade.php');
        $this->assertStringContainsString("default => 'col-12 col-md-6 col-lg-4 col-xl-4'", $source, 'AI-286 product-card grid responsive shape MUST stay intact as the default match() arm (1 → 2 → 3-col cascade).');
    }

    #[Test]
    #[DataProvider('shopTemplatePaths')]
    public function shop_template_preserves_empty_state_or_forelse(string $path): void
    {
        $source = $this->read($path);
        // default.blade.php uses @forelse + empty-state SVG card; skin-1
        // uses @foreach. Both shapes must stay intact (AI-861 add-to-cart
        // CTA on the inner product-card is fired via include).
        $this->assertTrue(
            str_contains($source, '@forelse') || str_contains($source, '@foreach'),
            'Shop template must continue to iterate $products via @forelse or @foreach.'
        );
        $this->assertStringContainsString("@include('modules.shop::livewire.shop.product-card", $source, 'product-card include must stay intact (AI-861 buy CTA lives in that partial).');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — pattern-set integrity (sum-to-12 + breakpoint-jump-at-lg)
    // ─────────────────────────────────────────────────────────────────────

    /**
     * @return array<string, array{0: string}>
     */
    public static function gridMathPaths(): array
    {
        return [
            'default' => ['Modules/Shop/resources/views/livewire/shop/default.blade.php'],
            'skin-1'  => ['Modules/Shop/resources/views/livewire/shop/skin-1.blade.php'],
        ];
    }

    #[Test]
    #[DataProvider('gridMathPaths')]
    public function lg_breakpoint_columns_sum_to_12_at_lg_plus(string $path): void
    {
        $source = $this->read($path);
        // Exactly 1 `col-12 col-lg-3` (filter rail) + 1 `col-12 col-lg-9`
        // (grid pane) per template render — sums to 12. Anything else
        // = layout regression.
        $rail = substr_count($source, '<div class="col-12 col-lg-3">');
        $grid = substr_count($source, '<div class="col-12 col-lg-9">');
        $this->assertSame(1, $rail, sprintf('%s must carry exactly 1 col-lg-3 filter rail.', basename($path)));
        $this->assertSame(1, $grid, sprintf('%s must carry exactly 1 col-lg-9 grid pane.', basename($path)));
        $this->assertSame(12, 3 + 9, 'Self-documenting math invariant: 3 + 9 = 12 (full Bootstrap row, no dead-space).');
    }

    #[Test]
    #[DataProvider('gridMathPaths')]
    public function md_breakpoint_filter_rail_stacks_via_col_12_only(string $path): void
    {
        // Option A2 contract: at md (≥768) the filter rail + grid both
        // hit `col-12` so the rail stacks ABOVE the grid. There MUST
        // NOT be any sidebar-shape `col-md-N` declaration on either
        // column at the sidebar-vs-grid layer. (Inner product-card
        // grid retains `col-md-6` for the responsive product cascade
        // — that's a DIFFERENT shape and stays intact per AI-286.)
        $source = $this->stripBladeAndPhpComments($this->read($path));
        $this->assertDoesNotMatchRegularExpression(
            '/<div class="col-12 col-md-(?!6\b)[0-9]+(\s[^"]*)?">/',
            $source,
            sprintf('%s must NOT carry any sidebar-layer col-md-N (other than the AI-286 product-card col-md-6 inner grid).', basename($path))
        );
    }
}
