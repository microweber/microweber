<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-87 / AI-75 / TICKET-BB — CSP-hardening inline-onclick sweep
 * regression coverage.
 *
 * Pins:
 *   - The 16 in-scope public module-skin Blade files no longer carry
 *     ANY `onclick=` attribute. Inline `onclick=` is blocked by a
 *     strict CSP `script-src 'self'`; replacing with data-* +
 *     delegated listener keeps functionality without violating CSP.
 *   - The shared listener `csp-skin-handlers.js` defines four
 *     handler families (gallery, productImage, cart-add-and-checkout,
 *     pinmarklet) using Element.closest() for ancestor walking so
 *     a click on an inner <img> still triggers the wrapper handler.
 *   - The listener is idempotently attached via
 *     `window.__mwCspSkinHandlersAttached` so Livewire / module
 *     re-renders don't stack duplicate listeners.
 *   - Each migrated skin emits the canonical data-* attribute the
 *     listener expects for its case.
 *
 * Style after the cycle-52..86 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class CspOnclickSweepContractTest extends TestCase
{
    /**
     * Public-facing module-skin files migrated in cycle-87. The 16
     * here are the ones the audit listed; admin-only views (Ai chat,
     * Marketplace modal, Newsletter list, Updater notice) are
     * deliberately out of scope per the brief.
     */
    private const SKIN_PATHS = [
        // Pictures — gallery + productImage handlers
        'Modules/Pictures/resources/views/templates/default.blade.php',
        'Modules/Pictures/resources/views/templates/skin-14.blade.php',
        'Modules/Pictures/resources/views/templates/shop-inner-templates.blade.php',
        'Modules/Pictures/resources/views/templates/masonry.blade.php',
        'Modules/Pictures/resources/views/templates/button_gallery.blade.php',
        'Modules/Pictures/resources/views/templates/skin-6.blade.php',
        'Modules/Pictures/resources/views/templates/skin-3-beauty.blade.php',
        'Modules/Pictures/resources/views/templates/skin-14-ocean.blade.php',
        'Modules/Pictures/resources/views/templates/simple.blade.php',
        'Modules/Pictures/resources/views/templates/skin-12.blade.php',
        'Modules/Pictures/resources/views/templates/shop-inner-templates-2.blade.php',
        // Sharer — pinmarklet handler
        'Modules/Sharer/resources/views/templates/default.blade.php',
        // Content / Page — cart-add-and-checkout handler
        'Modules/Content/resources/views/templates/default.blade.php',
        'Modules/Content/resources/views/templates/dictionary.blade.php',
        'Modules/Content/resources/views/templates/skin-1.blade.php',
        'Modules/Page/resources/views/templates/default.blade.php',
    ];

    private string $listenerSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listenerSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/core/core/csp-skin-handlers.js'
        ));
    }

    #[Test]
    public function no_inline_onclick_remains_in_migrated_skins(): void
    {
        // Strip Blade `{{-- ... --}}` AND HTML `<!-- ... -->` comments
        // first so audit-trail doc-comments referencing the prior
        // `onclick=` shape don't trigger a false positive.
        foreach (self::SKIN_PATHS as $rel) {
            $path = base_path($rel);
            $this->assertFileExists($path, "Expected migrated skin: {$rel}");
            $src = file_get_contents($path);
            $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);
            $stripped = preg_replace('/<!--[\\s\\S]*?-->/', '', $stripped);

            // Bare `onclick="..."` shape forbidden — the migration
            // moved every handler to a data-* attribute. Allow
            // commented-out HTML/Blade examples.
            $this->assertStringNotContainsString(
                'onclick="',
                $stripped,
                "{$rel}: must not carry any inline onclick=\"...\" attribute (CSP-violation)"
            );
        }
    }

    #[Test]
    public function gallery_data_attributes_are_present_in_pictures_skins(): void
    {
        // Pictures skins that previously used mw.gallery() must now
        // emit data-mw-gallery + data-mw-gallery-index.
        $galleryFiles = [
            'Modules/Pictures/resources/views/templates/default.blade.php',
            'Modules/Pictures/resources/views/templates/masonry.blade.php',
            'Modules/Pictures/resources/views/templates/button_gallery.blade.php',
            'Modules/Pictures/resources/views/templates/skin-3-beauty.blade.php',
            'Modules/Pictures/resources/views/templates/simple.blade.php',
            'Modules/Pictures/resources/views/templates/skin-12.blade.php',
        ];
        foreach ($galleryFiles as $rel) {
            $src = file_get_contents(base_path($rel));
            $this->assertStringContainsString(
                'data-mw-gallery=',
                $src,
                "{$rel}: must carry data-mw-gallery= attribute (CSP-safe gallery launcher)"
            );
            $this->assertStringContainsString(
                'data-mw-gallery-index=',
                $src,
                "{$rel}: must carry data-mw-gallery-index= attribute"
            );
        }
    }

    #[Test]
    public function product_image_data_attributes_are_present_in_shop_inner_skins(): void
    {
        // Shop-inner Pictures skins use setProductImage() — must
        // now emit data-mw-product-image + data-mw-product-image-target
        // + data-mw-product-image-index.
        $shopInnerFiles = [
            'Modules/Pictures/resources/views/templates/skin-14.blade.php',
            'Modules/Pictures/resources/views/templates/shop-inner-templates.blade.php',
            'Modules/Pictures/resources/views/templates/skin-6.blade.php',
            'Modules/Pictures/resources/views/templates/skin-14-ocean.blade.php',
            'Modules/Pictures/resources/views/templates/shop-inner-templates-2.blade.php',
        ];
        foreach ($shopInnerFiles as $rel) {
            $src = file_get_contents(base_path($rel));
            $this->assertStringContainsString(
                'data-mw-product-image=',
                $src,
                "{$rel}: must carry data-mw-product-image= attribute"
            );
            $this->assertStringContainsString(
                'data-mw-product-image-target=',
                $src,
                "{$rel}: must carry data-mw-product-image-target= attribute"
            );
        }
    }

    #[Test]
    public function cart_add_and_checkout_data_attribute_present_in_content_page_skins(): void
    {
        // task-2026-05-17-aeb113 / AI-806 superseded: ALL price / cart /
        // shop.js code paths were dropped entirely from the Page skin
        // (Modules/Page/resources/views/templates/default.blade.php) —
        // pages never carry prices, so the gated dead code (incl. the
        // data-mw-cart-add-and-checkout attribute) was a copy-paste leak
        // from an old Products template. It is therefore out of scope for
        // the cart-add-and-checkout handler. The three Content list skins
        // still carry the attribute and remain pinned.
        $cartFiles = [
            'Modules/Content/resources/views/templates/default.blade.php',
            'Modules/Content/resources/views/templates/dictionary.blade.php',
            'Modules/Content/resources/views/templates/skin-1.blade.php',
        ];
        foreach ($cartFiles as $rel) {
            $src = file_get_contents(base_path($rel));
            $this->assertStringContainsString(
                'data-mw-cart-add-and-checkout=',
                $src,
                "{$rel}: must carry data-mw-cart-add-and-checkout= attribute"
            );
        }
    }

    #[Test]
    public function pinmarklet_data_attribute_present_in_sharer(): void
    {
        $src = file_get_contents(base_path(
            'Modules/Sharer/resources/views/templates/default.blade.php'
        ));
        $this->assertStringContainsString(
            'data-mw-pinmarklet',
            $src,
            'Sharer/default.blade.php: must carry data-mw-pinmarklet attribute'
        );
    }

    #[Test]
    public function listener_attaches_idempotently_with_global_flag(): void
    {
        // Without the idempotency guard, Livewire / module re-renders
        // would stack duplicate listeners and one click would fire
        // mw.gallery() / mw.cart.add_and_checkout() multiple times.
        $this->assertStringContainsString(
            'window.__mwCspSkinHandlersAttached',
            $this->listenerSrc,
            'csp-skin-handlers.js: must guard re-attachment via window.__mwCspSkinHandlersAttached flag'
        );
        $this->assertMatchesRegularExpression(
            "/if\\s*\\(\\s*window\\.__mwCspSkinHandlersAttached\\s*\\)\\s*\\{\\s*\\n\\s*return;/s",
            $this->listenerSrc,
            'csp-skin-handlers.js: idempotency guard must early-return if flag already true'
        );
    }

    #[Test]
    public function listener_handles_all_data_attribute_families(): void
    {
        $required = [
            "event.target.closest('[data-mw-gallery]')",
            "event.target.closest('[data-mw-product-image]')",
            "event.target.closest('[data-mw-cart-add-and-checkout]')",
            "event.target.closest('[data-mw-pinmarklet]')",
            // task-2026-06 CSP migration of the Sharer "Copy link" button.
            "event.target.closest('[data-mw-copy-link]')",
        ];
        foreach ($required as $needle) {
            $this->assertStringContainsString(
                $needle,
                $this->listenerSrc,
                "csp-skin-handlers.js: must dispatch on `{$needle}`"
            );
        }

        // Element.closest() walks the ancestor chain so a click on an
        // inner <img> still triggers the wrapper's handler. Pin that
        // we use closest() (not target.matches() which only matches
        // the click target itself) — one call per handler family.
        $this->assertSame(
            count($required),
            substr_count($this->listenerSrc, 'event.target.closest('),
            'csp-skin-handlers.js: must call event.target.closest() once per handler family for ancestor walking'
        );
    }

    #[Test]
    public function listener_safely_decodes_base64_json_gallery_payload(): void
    {
        // The gallery payload is base64-encoded JSON to dodge HTML-
        // attribute quoting issues (descriptions can contain ", ',
        // < etc.). Pin the safe-decode helper.
        $this->assertStringContainsString(
            'function safeJsonParse(value)',
            $this->listenerSrc,
            'csp-skin-handlers.js: must define safeJsonParse helper'
        );
        $this->assertMatchesRegularExpression(
            "/atob\\(value\\)/s",
            $this->listenerSrc,
            'csp-skin-handlers.js: safeJsonParse must base64-decode via atob()'
        );
        // The atob-then-JSON.parse path must be wrapped in try/catch
        // so a malformed attribute returns null, not throws.
        $this->assertMatchesRegularExpression(
            "/try\\s*\\{[\\s\\S]*?atob\\(value\\)[\\s\\S]*?JSON\\.parse[\\s\\S]*?\\}\\s*catch/s",
            $this->listenerSrc,
            'csp-skin-handlers.js: safeJsonParse must wrap atob/JSON.parse in try/catch'
        );
    }
}
