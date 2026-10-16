<?php

namespace Modules\DigitalProduct\Tests\Unit;

use Modules\Checkout\Livewire\CheckoutWizard;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Verifies the checkout shipping-step decision: a cart skips shipping only when
 * EVERY product has content subtype 'downloadable_product'; regular products
 * and mixed carts keep shipping.
 */
class ShippingDecisionTest extends TestCase
{
    protected array $productIds = [];

    protected function makeProduct(string $subtype = 'product'): Product
    {
        $p = new Product();
        $p->title = 'dp-ship-' . uniqid();
        $p->url = $p->title;
        $p->content_type = 'product';
        $p->subtype = $subtype;
        $p->save();

        $this->productIds[] = $p->id;

        return $p;
    }

    #[Test]
    public function digital_only_cart_skips_shipping_but_mixed_and_default_do_not(): void
    {
        $physical = $this->makeProduct();                       // subtype = product
        $digital = $this->makeProduct('downloadable_product');  // subtype = downloadable_product

        // Digital-only → no shipping needed.
        $this->assertFalse(CheckoutWizard::productsRequireShipping([$digital->id]));

        // Mixed (digital + physical) → shipping required.
        $this->assertTrue(CheckoutWizard::productsRequireShipping([$digital->id, $physical->id]));

        // Regular product → requires shipping.
        $this->assertTrue(CheckoutWizard::productsRequireShipping([$physical->id]));

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
