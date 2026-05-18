<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-046a37 / AI-861 — storefront add-to-cart CTA gap.
 * Jira: https://microweber.atlassian.net/browse/AI-861
 *
 * Designer's Round 22 commerce-conversion audit caught /shop product
 * cards + product-detail pages rendering ZERO add-to-cart affordances
 * — entire commerce funnel non-functional from the storefront.
 *
 * Two distinct surfaces affected:
 *
 *   1. Product cards (Modules/Shop/.../product-card{,-skin-1}.blade.php):
 *      No add-to-cart button anywhere on the card. Title + price + tags
 *      only. Card-image and title anchored to detail page, but no
 *      explicit buy CTA on the grid surface.
 *
 *   2. Product detail page (via <module type="shop/cart_add"/> rendered
 *      from Templates/Bootstrap/.../product.blade.php:138). The
 *      Modules/Cart/.../templates/{default,shop_inner}.blade.php
 *      templates' `@if(empty($data))` branch rendered ONLY the
 *      admin-editor copy "Click here to edit custom fields" (the
 *      `mw-open-module-settings` class carries NO display:none scope
 *      so it leaks to public visitors), and crucially ZERO add-to-cart
 *      button — leaving every product with no configured prices_data
 *      with no buy CTA at all.
 *
 * Fix shape (Slice A):
 *   - product-card.blade.php + product-card-skin-1.blade.php — add
 *     `<button class="btn btn-primary mt-3 mw-add-to-cart-btn"
 *      data-content-id data-price data-title>` after the tag list.
 *     `mw-add-to-cart-btn` is the canonical class consumed by the
 *     shop.js delegated click handler (used elsewhere by
 *     Modules/Cart/.../templates/default.blade.php +
 *     .../shop_inner.blade.php) — same JS handler fires whether the
 *     click originates from the shop grid OR the product-detail page.
 *   - Modules/Cart/.../templates/default.blade.php (and the
 *     shop_inner.blade.php sibling): gate the admin editor prompt
 *     behind is_admin(); render a fallback `mw-add-to-cart-btn`
 *     button for public users when no prices_data is configured.
 *     Fallback price reads `content_data($for_id)['price']` (with
 *     `0` fallback for misconfigured products).
 *
 * Acceptance gate (verified at HEAD via curl when product data exists):
 *   - curl /shop | grep -oc 'add-to-cart' ≥ 1 (per visible product card)
 *   - curl /<product-slug> | grep -c 'add-to-cart' ≥ 2 (detail page)
 *
 * NOTE: AI-860 runtime fixture-detection guard was un-shipped per task-4ebf70
 * (human architectural critique: prod request paths should not carry
 * fixture-detection logic; root-cause fix is data-layer cleanliness).
 * The historical AI-860 sentinel here was inverted to assert the
 * runtime guard STAYS removed.
 *
 * 5-group structure: A = product-card.blade.php source-presence;
 * B = product-card-skin-1.blade.php source-presence (sibling skin);
 * C = Cart/default.blade.php (empty-data branch carries is_admin() gate +
 * public fallback CTA); D = Cart/shop_inner.blade.php (same fix on the
 * sibling Cart template); E = back-compat regression sentinels.
 */
class Public046a37AI861AddToCartCtaContractTest extends TestCase
{
    /**
     * Pre-strip Blade + PHP comments before negative absence-asserts so
     * docblock prose doesn't self-match (LESSONS selector-self-match
     * UNIFORMITY RULE — 18+ session-recurrences; CLAUDE/AGENTS/copilot
     * "every PHPUnit contract test file that performs ANY
     * assertStringNotContainsString / assertDoesNotMatchRegularExpression
     * against source-file contents MUST pre-strip language comments").
     */
    private function stripBladeAndPhpComments(string $source): string
    {
        $source = preg_replace('~\{\{--.*?--\}\}~s', '', $source);
        $source = preg_replace('~/\*.*?\*/~s', '', (string) $source);
        $source = preg_replace('~//[^\n]*~', '', (string) $source);
        return (string) $source;
    }

    private function read(string $relativePath): string
    {
        return (string) file_get_contents(base_path($relativePath));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Modules/Shop/.../product-card.blade.php source-presence
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function product_card_carries_add_to_cart_button(): void
    {
        $source = $this->read('Modules/Shop/resources/views/livewire/shop/product-card.blade.php');
        $this->assertStringContainsString('mw-add-to-cart-btn', $source, 'product-card.blade.php must carry an mw-add-to-cart-btn button so the storefront grid surface has a buy CTA.');
        $this->assertStringContainsString('data-content-id="{{ $product->id }}"', $source);
        $this->assertStringContainsString('data-price="{{ $product->price }}"', $source);
        $this->assertStringContainsString('data-title="{{ $product->title }}"', $source);
        $this->assertStringContainsString('aria-label="{{ _e(\'Add to cart\', true) }}: {{ $product->title }}"', $source);
        $this->assertStringContainsString('task-2026-05-17-046a37', $source, 'task-id marker required for cross-surface grep.');
        $this->assertStringContainsString('AI-861', $source);
    }

    #[Test]
    public function product_card_button_lives_after_tag_loop(): void
    {
        $source = $this->read('Modules/Shop/resources/views/livewire/shop/product-card.blade.php');
        $tagLoopEnd = strpos($source, '@endforeach');
        $this->assertNotFalse($tagLoopEnd, 'product-card.blade.php must contain the tag-list @endforeach anchor.');
        $btnPos = strpos($source, 'mw-add-to-cart-btn');
        $this->assertNotFalse($btnPos);
        $this->assertGreaterThan($tagLoopEnd, $btnPos, 'Add-to-cart button must sit AFTER the tag loop (below price-holder + tags per design).');
    }

    #[Test]
    public function product_card_button_preserves_existing_anchors_and_tag_list(): void
    {
        $source = $this->read('Modules/Shop/resources/views/livewire/shop/product-card.blade.php');
        $this->assertStringContainsString('href="{{content_link($product->id)}}"', $source, 'image+title anchor to detail page must stay intact (AI-861 button is additive, NOT a replacement).');
        $this->assertStringContainsString('wire:click="filterTag', $source, 'tag-chip wire:click filter must stay intact post-AI-861.');
        $this->assertStringContainsString('hasSpecialPrice()', $source, 'special-price branch must stay intact.');
        $this->assertStringContainsString('responsive_thumbnail($product->mediaUrl()', $source, 'AI-265 responsive_thumbnail() preserved.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — product-card-skin-1.blade.php source-presence (sibling)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function product_card_skin_1_carries_add_to_cart_button(): void
    {
        $source = $this->read('Modules/Shop/resources/views/livewire/shop/product-card-skin-1.blade.php');
        $this->assertStringContainsString('mw-add-to-cart-btn', $source, 'product-card-skin-1.blade.php (sibling skin) must also carry the add-to-cart button.');
        $this->assertStringContainsString('data-content-id="{{ $product->id }}"', $source);
        $this->assertStringContainsString('data-price="{{ $product->price }}"', $source);
        $this->assertStringContainsString('data-title="{{ $product->title }}"', $source);
        $this->assertStringContainsString('task-2026-05-17-046a37', $source);
        $this->assertStringContainsString('AI-861', $source);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Modules/Cart/.../default.blade.php empty-data branch
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function cart_default_template_gates_admin_prompt_behind_is_admin(): void
    {
        // Pre-strip Blade comments so docblock prose (which legitimately
        // mentions `mw-open-module-settings` in the AI-861 rationale)
        // does NOT self-match the strpos lookup (selector-self-match
        // UNIFORMITY rule). What we want is the HTML div emission, not
        // the comment reference to it.
        $rawSource = $this->read('Modules/Cart/resources/views/templates/default.blade.php');
        $source = $this->stripBladeAndPhpComments($rawSource);
        $promptPos = strpos($source, 'mw-open-module-settings');
        $this->assertNotFalse($promptPos, 'cart default template must still emit the admin prompt div — within an is_admin() gate.');
        $window = substr($source, max(0, $promptPos - 200), 400);
        $this->assertStringContainsString('is_admin()', $window, 'Admin editor prompt must be gated behind is_admin() so it no longer leaks to public users.');
    }

    #[Test]
    public function cart_default_template_public_fallback_renders_add_to_cart_button(): void
    {
        $source = $this->read('Modules/Cart/resources/views/templates/default.blade.php');
        // Find the empty-data branch + verify it carries the public fallback CTA.
        $emptyBranchPos = strpos($source, '@if(empty($data))');
        $this->assertNotFalse($emptyBranchPos, 'cart default template must still carry the @if(empty($data)) branch.');
        $branchSlice = substr($source, $emptyBranchPos, 3000);
        $this->assertStringContainsString('mw-add-to-cart-btn', $branchSlice, 'Empty-data branch MUST emit an mw-add-to-cart-btn fallback button for public users (AI-861 acceptance gate).');
        $this->assertStringContainsString('mw-add-to-cart-disabled-btn', $branchSlice, 'Out-of-stock variant of fallback CTA MUST also exist for stock=0 products.');
        $this->assertStringContainsString('content_data($for_id)', $branchSlice, 'Fallback price MUST read content_data($for_id) so the button carries the product\'s base price.');
        $this->assertStringContainsString('mwAi861FallbackPrice', $branchSlice, 'Fallback-price variable name carries the AI-861 task marker for audit-grep.');
        $this->assertStringContainsString('task-2026-05-17-046a37', $branchSlice);
        $this->assertStringContainsString('AI-861', $branchSlice);
    }

    #[Test]
    public function cart_default_template_preserves_existing_non_empty_data_path(): void
    {
        $source = $this->read('Modules/Cart/resources/views/templates/default.blade.php');
        // The @else branch (non-empty $data) must still carry the
        // pre-AI-861 mw-add-to-cart-btn + variant-picker UI.
        $elseBranchPos = strpos($source, '@else');
        $this->assertNotFalse($elseBranchPos);
        $elseSlice = substr($source, $elseBranchPos, 3000);
        $this->assertStringContainsString('mw-add-to-cart-btn', $elseSlice, 'Non-empty data branch (regular variant-priced products) must keep its existing buy CTA.');
        $this->assertStringContainsString('<module type="custom_fields"', $elseSlice, 'Variant-picker module reference must remain in the else branch.');
        $this->assertStringContainsString('mw-price-item', $elseSlice, 'Price-item structure must remain in the variant-pricing branch.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — shop_inner.blade.php sibling (same fix shape)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function cart_shop_inner_template_gates_admin_prompt_behind_is_admin(): void
    {
        $rawSource = $this->read('Modules/Cart/resources/views/templates/shop_inner.blade.php');
        $source = $this->stripBladeAndPhpComments($rawSource);
        $promptPos = strpos($source, 'mw-open-module-settings');
        $this->assertNotFalse($promptPos, 'cart shop_inner template must still emit the admin prompt div — within an is_admin() gate.');
        $window = substr($source, max(0, $promptPos - 300), 600);
        $this->assertStringContainsString('is_admin()', $window, 'shop_inner admin prompt must be gated behind is_admin().');
    }

    #[Test]
    public function cart_shop_inner_template_public_fallback_renders_add_to_cart_button(): void
    {
        $source = $this->read('Modules/Cart/resources/views/templates/shop_inner.blade.php');
        $emptyBranchPos = strpos($source, '@if(empty($data))');
        $this->assertNotFalse($emptyBranchPos);
        $branchSlice = substr($source, $emptyBranchPos, 3000);
        $this->assertStringContainsString('mw-add-to-cart-btn', $branchSlice, 'shop_inner empty-data branch MUST emit fallback mw-add-to-cart-btn for public users.');
        $this->assertStringContainsString('content_data($for_id)', $branchSlice);
        $this->assertStringContainsString('task-2026-05-17-046a37', $branchSlice);
        $this->assertStringContainsString('AI-861', $branchSlice);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E — back-compat regression sentinels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function shop_module_directive_still_invoked_on_bootstrap_product_template(): void
    {
        // The product detail page renders the Cart-module via the
        // <module type="shop/cart_add"/> directive in
        // Templates/Bootstrap/.../product.blade.php. Pre-AI-861, this
        // already-existing call was the entry point — AI-861 only fixed
        // the rendered template's branch behaviour, NOT the call site.
        // Sentinel: ensure the call site stays.
        $source = $this->read('Templates/Bootstrap/resources/views/product.blade.php');
        $this->assertStringContainsString('<module type="shop/cart_add"', $source, 'Bootstrap product.blade.php must continue to invoke the Cart-add module on the detail page.');
    }

    #[Test]
    public function fixture_filter_runtime_guard_is_removed_per_task_4ebf70(): void
    {
        // task-2026-05-18-4ebf70 — human dispatch: fixture-detection logic
        // does NOT belong in prod request paths. AI-860 (the LIKE filter +
        // abort(404) short-circuit consuming AdminFixtureGuard::isFixtureSlug)
        // is un-shipped per the human's architectural critique. Root-cause
        // fix is data-layer: don't seed/import fixtures into prod DB.
        // This sentinel inverts the prior AI-861-vs-AI-860-no-regression
        // test — future agents re-adding runtime fixture-detection on the
        // public frontend will false-fail here.
        $shopComponent = $this->read('Modules/Shop/Livewire/ShopComponent.php');
        $this->assertStringNotContainsString('FIXTURE_SLUG_LIKE_PATTERNS', $shopComponent, 'AI-860 LIKE filter MUST stay removed — fixtures should not be filtered in prod request paths (task-4ebf70).');
        $frontendController = $this->read('src/MicroweberPackages/App/Http/Controllers/FrontendController.php');
        $this->assertStringNotContainsString('isFixtureSlug(', $frontendController, 'AI-860 abort(404) short-circuit MUST stay removed — same rationale.');
    }
}
