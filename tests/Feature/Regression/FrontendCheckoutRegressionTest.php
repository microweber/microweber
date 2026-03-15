<?php

namespace Tests\Feature\Regression;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Config;
use Modules\Cart\Models\Cart;
use Modules\Checkout\Repositories\CheckoutManager;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Full Regression Test Suite - Frontend Checkout Flow
 *
 * End-to-end testing of the complete checkout flow including:
 * - Add to cart via update_cart()
 * - Cart management (update qty, remove, empty)
 * - Checkout process via CheckoutManager
 * - Order creation and verification
 */
class FrontendCheckoutRegressionTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('mail.transport', 'array');
        Config::set('queue.driver', 'sync');
        Product::truncate();
        Order::truncate();
        Cart::truncate();
        empty_cart();
    }

    #[Test]
    public function it_complete_checkout_flow(): void
    {
        $productId = $this->createTestProduct('Checkout Flow Product', 99.99);

        $cartResult = update_cart([
            'content_id' => $productId,
            'qty' => 2,
        ]);

        $this->assertArrayHasKey('success', $cartResult);

        // Verify cart sum
        $cartSum = cart_sum(true);
        $this->assertGreaterThan(0, $cartSum);

        $cartItemsCount = cart_sum(false);
        $this->assertEquals(2, $cartItemsCount);

        // Checkout
        $checkoutStatus = app()->checkout_manager->checkout([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'country' => 'US',
            'zip' => '12345',
            'is_paid' => 1,
            'order_completed' => 1,
        ]);

        $this->assertArrayHasKey('success', $checkoutStatus);
        $this->assertArrayHasKey('id', $checkoutStatus);
        $this->assertArrayHasKey('order_completed', $checkoutStatus);

        // Verify order was created
        $order = Order::find($checkoutStatus['id']);
        $this->assertNotNull($order);
    }

    #[Test]
    public function it_adds_product_to_cart_via_update_cart(): void
    {
        $productId = $this->createTestProduct('Cart Add Product', 49.99);

        $cartResult = update_cart([
            'content_id' => $productId,
            'price' => 49.99,
        ]);

        $this->assertArrayHasKey('success', $cartResult);
        $this->assertArrayHasKey('product', $cartResult);
        $this->assertArrayHasKey('cart_sum', $cartResult);
        $this->assertArrayHasKey('cart_items_quantity', $cartResult);
    }

    #[Test]
    public function it_adds_multiple_products_to_cart(): void
    {
        $productId1 = $this->createTestProduct('Product A', 25.00);
        $productId2 = $this->createTestProduct('Product B', 35.00);

        $result1 = update_cart([
            'content_id' => $productId1,
            'price' => 25.00,
        ]);
        $this->assertArrayHasKey('success', $result1);

        $result2 = update_cart([
            'content_id' => $productId2,
            'price' => 35.00,
        ]);
        $this->assertArrayHasKey('success', $result2);

        $cartItemsCount = cart_sum(false);
        $this->assertEquals(2, $cartItemsCount);
    }

    #[Test]
    public function it_cart_item_quantity_update(): void
    {
        $productId = $this->createTestProduct('Qty Update Product', 10.00);

        $cartResult = update_cart([
            'content_id' => $productId,
            'price' => 10.00,
        ]);

        $this->assertArrayHasKey('success', $cartResult);

        // Get the cart item ID
        $cartItem = Cart::where('rel_id', $productId)
            ->where('order_completed', 0)
            ->first();
        $this->assertNotNull($cartItem);

        // Update quantity
        $updateResult = update_cart_item_qty([
            'id' => $cartItem->id,
            'qty' => 5,
        ]);

        $this->assertArrayHasKey('success', $updateResult);

        $cartItem->refresh();
        $this->assertEquals(5, $cartItem->qty);
    }

    #[Test]
    public function it_cart_item_removal(): void
    {
        $productId1 = $this->createTestProduct('Remove Product 1', 20.00);
        $productId2 = $this->createTestProduct('Remove Product 2', 30.00);

        update_cart(['content_id' => $productId1, 'price' => 20.00]);
        update_cart(['content_id' => $productId2, 'price' => 30.00]);

        $this->assertEquals(2, cart_sum(false));

        // Remove first item
        $cartItem = Cart::where('rel_id', $productId1)
            ->where('order_completed', 0)
            ->first();
        $this->assertNotNull($cartItem);

        $removeResult = remove_cart_item(['id' => $cartItem->id]);
        $this->assertArrayHasKey('success', $removeResult);

        $this->assertEquals(1, cart_sum(false));
    }

    #[Test]
    public function it_empty_cart(): void
    {
        $productId = $this->createTestProduct('Empty Cart Product', 15.00);

        update_cart(['content_id' => $productId, 'price' => 15.00]);
        $this->assertGreaterThan(0, cart_sum(false));

        $emptyResult = empty_cart();
        $this->assertArrayHasKey('success', $emptyResult);
        $this->assertEquals(0, cart_sum(false));
    }

    #[Test]
    public function it_checkout_with_empty_cart_creates_zero_amount_order(): void
    {
        empty_cart();

        $checkoutStatus = app()->checkout_manager->checkout([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertArrayHasKey('success', $checkoutStatus);
        $this->assertEquals(0, $checkoutStatus['amount']);
        $this->assertEquals(0, $checkoutStatus['items_count']);
    }

    #[Test]
    public function it_checkout_creates_order_with_correct_details(): void
    {
        $productId = $this->createTestProduct('Order Details Product', 75.00);

        update_cart([
            'content_id' => $productId,
            'price' => 75.00,
        ]);

        $checkoutStatus = app()->checkout_manager->checkout([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '0881234567',
            'address' => '456 Order Street',
            'city' => 'Order City',
            'state' => 'CA',
            'country' => 'US',
            'zip' => '90210',
            'is_paid' => 1,
            'order_completed' => 1,
        ]);

        $this->assertArrayHasKey('success', $checkoutStatus);
        $this->assertArrayHasKey('id', $checkoutStatus);

        $order = Order::find($checkoutStatus['id']);
        $this->assertNotNull($order);
        $this->assertEquals('jane@example.com', $order->email);
        $this->assertEquals('Jane', $order->first_name);
        $this->assertEquals('Smith', $order->last_name);
    }

    #[Test]
    public function it_checkout_updates_product_quantity(): void
    {
        app()->database_manager->extended_save_set_permission(true);

        $productId = $this->createTestProduct('Stock Product', 50.00, ['data_qty' => 10]);

        $contentData = content_data($productId);
        $this->assertEquals(10, $contentData['qty']);

        update_cart([
            'content_id' => $productId,
            'price' => 50.00,
            'qty' => 3,
        ]);

        $checkoutStatus = app()->checkout_manager->checkout([
            'email' => 'stock@example.com',
            'first_name' => 'Stock',
            'last_name' => 'Test',
            'is_paid' => 1,
            'order_completed' => 1,
        ]);

        $this->assertArrayHasKey('success', $checkoutStatus);

        $contentDataAfter = content_data($productId);
        $this->assertEquals(7, $contentDataAfter['qty']);
    }

    #[Test]
    public function it_cart_sum_calculates_correctly(): void
    {
        $productId = $this->createTestProduct('Sum Product', 33.33);

        update_cart([
            'content_id' => $productId,
            'price' => 33.33,
            'qty' => 3,
        ]);

        $cartAmount = cart_sum(true);
        $this->assertEquals(99.99, $cartAmount);

        $cartItems = cart_sum(false);
        $this->assertEquals(3, $cartItems);
    }

    /**
     * Create a test product using save_content (the actual Microweber API).
     */
    private function createTestProduct(string $title, float $price, array $extra = []): int
    {
        app()->database_manager->extended_save_set_permission(true);

        $params = array_merge([
            'title' => $title,
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => $price],
            ],
            'is_active' => 1,
        ], $extra);

        $savedId = save_content($params);
        $this->assertGreaterThan(0, $savedId);

        return $savedId;
    }
}
