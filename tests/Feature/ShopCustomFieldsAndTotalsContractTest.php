<?php

declare(strict_types=1);

namespace Tests\Feature;

use Modules\CustomFields\Models\CustomField;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Three storefront defects found exercising shipping + custom fields end-to-end.
 *
 * task-2026-06-06-cfrel — product custom fields never rendered or got captured.
 *   (a) CustomField::getWithValues did an EXACT rel_type match, so a caller
 *       passing the shorthand 'content' (or 'product') matched nothing — content
 *       fields are stored as the fully-qualified Content class. Now normalised.
 *   (b) The custom_fields module defaulted rel_type to 'module' and never
 *       auto-detected the current content, so <module type="custom_fields"/> on
 *       a product page rendered "No field data available". Now auto-detects.
 *
 * task-2026-06-06-cfcart — the add-to-cart button only POSTed
 *   data-content-id + data-price, dropping every custom-field selection (and
 *   its price modifier). The handler now serialises the holder's fields.
 *
 * task-2026-06-06-shipdbl — the checkout order-summary double-counted shipping:
 *   $this->cartTotal = cart_total() (which already includes shipping) and the
 *   summary then added shipping again. Switched to cart_sum() (items only).
 */
class ShopCustomFieldsAndTotalsContractTest extends TestCase
{
    #[Test]
    public function normalize_rel_type_maps_content_family_to_the_content_class(): void
    {
        $contentClass = \Modules\Content\Models\Content::class;

        $this->assertSame($contentClass, CustomField::normalizeRelType('content'));
        $this->assertSame($contentClass, CustomField::normalizeRelType('product'));
        $this->assertSame($contentClass, CustomField::normalizeRelType('page'));
        $this->assertSame($contentClass, CustomField::normalizeRelType('post'));

        // Form-builder fields and already-qualified class names are untouched.
        $this->assertSame('module', CustomField::normalizeRelType('module'));
        $this->assertSame($contentClass, CustomField::normalizeRelType($contentClass));
        $this->assertSame('', CustomField::normalizeRelType(''));
    }

    #[Test]
    public function getWithValues_normalizes_the_rel_type_before_querying(): void
    {
        $src = (string) file_get_contents(base_path('Modules/CustomFields/Models/CustomField.php'));
        $this->assertMatchesRegularExpression(
            "/->where\('rel_type',\s*static::normalizeRelType\(/",
            $src,
            'getWithValues must normalize rel_type before the where() so shorthand tokens resolve.'
        );
    }

    #[Test]
    public function custom_fields_module_auto_detects_content(): void
    {
        $src = (string) file_get_contents(base_path('Modules/CustomFields/Microweber/CustomFieldsModule.php'));

        $this->assertStringContainsString('resolveRelTypeAndId', $src,
            'The module must resolve rel_type + rel_id via resolveRelTypeAndId().');
        // Auto-detect the current content when no explicit ids / form signal.
        $this->assertStringContainsString('content_id()', $src,
            'The module must auto-detect the current content via content_id().');
        // The injected data-content-id (cart-add form) must be honoured.
        $this->assertStringContainsString('data-content-id', $src,
            'The module must read the injected data-content-id attribute.');
        // Form-builder modules keep working: a default-fields / for-id signal
        // must still route to the module context.
        $this->assertStringContainsString('default-fields', $src,
            'Form-builder modules must keep their module rel_type via the default-fields/for-id signal.');
    }

    #[Test]
    public function add_to_cart_button_sends_custom_field_selections(): void
    {
        $src = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/api-core/core/core/shop.js'
        ));

        // add_item merges a fields object into the POST.
        $this->assertMatchesRegularExpression(
            '/add_item:\s*function\s*\(content_id,\s*price,\s*c,\s*fields\)/',
            $src,
            'add_item must accept a fields argument.'
        );
        $this->assertMatchesRegularExpression(
            '/add_and_show_modal:\s*function\s*\(content_id,\s*price,\s*c,\s*fields\)/',
            $src,
            'add_and_show_modal must forward the fields argument.'
        );
        // The delegated button handler gathers named inputs from the holder.
        $this->assertStringContainsString('.mw-add-to-cart-holder', $src,
            'The button handler must read the enclosing add-to-cart holder.');
        $this->assertMatchesRegularExpression(
            '/add_and_show_modal\(contentId,\s*price,\s*title,\s*fields\)/',
            $src,
            'The button handler must pass the gathered fields to the cart.'
        );
    }

    #[Test]
    public function built_frontend_bundle_carries_the_fields_forwarding(): void
    {
        $bundle = base_path('public/vendor/microweber-packages/frontend-assets/build/frontend.js');
        if (! is_file($bundle)) {
            $this->markTestSkipped('Built frontend bundle not present.');
        }
        $js = (string) file_get_contents($bundle);
        $this->assertStringContainsString('mw-add-to-cart-holder', $js,
            'The served bundle must carry the custom-field gathering logic.');
    }

    #[Test]
    public function checkout_subtotal_uses_items_sum_not_total_with_shipping(): void
    {
        $src = (string) file_get_contents(base_path('Modules/Checkout/Livewire/CheckoutWizard.php'));

        // cartTotal (the items subtotal) must be cart_sum(), never cart_total()
        // — cart_total() bakes in shipping, which the summary then adds again.
        $this->assertSame(
            0,
            preg_match('/\$this->cartTotal\s*=\s*cart_total\(\)/', $src),
            'cartTotal must not be set from cart_total() (that double-counts shipping).'
        );
        $this->assertGreaterThanOrEqual(
            2,
            substr_count($src, '$this->cartTotal = cart_sum();'),
            'Both loadCartData() and onShippingChanged() must set cartTotal from cart_sum().'
        );
        // The order summary still adds shipping on top of the items subtotal.
        $this->assertMatchesRegularExpression(
            '/\$total\s*=\s*\$subtotal\s*\+\s*\$shipping/',
            $src,
            'The order summary must add shipping to the items subtotal exactly once.'
        );
    }
}
