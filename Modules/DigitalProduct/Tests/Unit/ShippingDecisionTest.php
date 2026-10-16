<?php

namespace Modules\DigitalProduct\Tests\Unit;

use Modules\Checkout\Livewire\CheckoutWizard;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Verifies the checkout shipping-step decision: a cart skips shipping only when
 * EVERY product is explicitly flagged non-shipping (content_data
 * requires_shipping === '0'); default and mixed carts keep shipping.
 */
class ShippingDecisionTest extends TestCase
{
    protected array $productIds = [];

    protected function makeProduct(?string $requiresShipping = null): Product
    {
        $p = new Product();
        $p->title = 'dp-ship-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->subtype = 'product';
        $p->save();

        if ($requiresShipping !== null) {
            app()->data_fields_manager->save([
                'content_id' => $p->id,
                'field_name' => 'requires_shipping',
                'field_value' => $requiresShipping,
            ]);
        }

        $this->productIds[] = $p->id;

        return $p;
    }

    #[Test]
    public function digital_only_cart_skips_shipping_but_mixed_and_default_do_not(): void
    {
        if (function_exists('cache_clear')) {
            cache_clear();
        }

        $physical = $this->makeProduct();          // no flag → requires shipping
        $explicitPhysical = $this->makeProduct('1'); // requires_shipping = 1
        $digital = $this->makeProduct('0');          // requires_shipping = 0

        if (function_exists('cache_clear')) {
            cache_clear();
        }

        // Digital-only → no shipping needed.
        $this->assertFalse(CheckoutWizard::productsRequireShipping([$digital->id]));

        // Mixed (digital + physical) → shipping required.
        $this->assertTrue(CheckoutWizard::productsRequireShipping([$digital->id, $physical->id]));

        // Unflagged product → requires shipping (default on).
        $this->assertTrue(CheckoutWizard::productsRequireShipping([$physical->id]));

        // Explicitly requires_shipping=1 → requires shipping.
        $this->assertTrue(CheckoutWizard::productsRequireShipping([$explicitPhysical->id]));

        // Empty / unknown → safe default requires shipping.
        $this->assertTrue(CheckoutWizard::productsRequireShipping([]));
        $this->assertTrue(CheckoutWizard::productsRequireShipping([null]));
    }

    protected function tearDown(): void
    {
        if (!empty($this->productIds)) {
            Product::whereIn('id', $this->productIds)->forceDelete();
        }

        parent::tearDown();
    }
}
