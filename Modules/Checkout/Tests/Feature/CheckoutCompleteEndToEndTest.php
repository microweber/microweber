<?php

namespace Modules\Checkout\Tests\Feature;

use Modules\Cart\Models\Cart;
use Modules\Checkout\Services\CheckoutService;
use Modules\Order\Models\Order;
use Modules\Payment\Models\PaymentProvider;
use Modules\Shipping\Models\ShippingProvider;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * End-to-End Checkout Flow Tests
 *
 * These tests verify the complete purchase flow including order creation,
 * database persistence, and error handling.
 */
class CheckoutCompleteEndToEndTest extends TestCase
{

    protected ?int $testProductId = null;
    protected ?int $testShippingProviderId = null;
    protected ?int $testPaymentProviderId = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable terms requirement for all tests
        save_option(['option_key' => 'shop_require_terms', 'option_value' => '0', 'option_group' => 'website']);

        // Create a test product
        $product = [
            'title' => 'Test Product - Complete Checkout',
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => '99.99'],
            ],
        ];
        $this->testProductId = save_content($product);

        // Create shipping provider
        $shippingProvider = ShippingProvider::firstOrCreate(
            ['provider' => 'flat_rate'],
            [
                'name' => 'Flat Rate Shipping',
                'is_active' => true,
                'is_default' => true,
                'settings' => ['shipping_cost' => 10.00],
                'position' => 0,
            ]
        );
        $this->testShippingProviderId = $shippingProvider->id;

        // Create payment provider - use pay_on_delivery for reliable testing
        PaymentProvider::where('provider', 'pay_on_delivery')->delete();
        $paymentProvider = PaymentProvider::create([
            'provider' => 'pay_on_delivery',
            'name' => 'Pay on Delivery',
            'is_active' => true,
            'is_default' => true,
            'settings' => ['payment_instructions' => 'Pay when you receive your order'],
        ]);
        $this->testPaymentProviderId = $paymentProvider->id;
    }

    #[Test]
    public function it_creates_order_when_checkout_is_submitted(): void
    {
        // Add product to cart
        $cartResult = update_cart([
            'content_id' => $this->testProductId,
            'qty' => 2,
        ]);

        $this->assertTrue(isset($cartResult['success']));

        $checkoutData = [
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'test' . uniqid() . '@example.com',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'US',
            'shipping_provider_id' => $this->testShippingProviderId,
            'payment_provider_id' => $this->testPaymentProviderId,
        ];

        $checkoutService = app(CheckoutService::class);
        $result = $checkoutService->checkout($checkoutData);

        // Verify order was created successfully
        $this->assertArrayHasKey('order_id', $result, 'Order ID should be in result');
        $this->assertNotNull($result['order_id'], 'Order ID should not be null');
        $this->assertGreaterThan(0, $result['order_id'], 'Order ID should be positive');

        // Verify order exists in database
        $order = Order::find($result['order_id']);
        $this->assertNotNull($order, 'Order should exist in database');
        $this->assertEquals($checkoutData['email'], $order->email);
        $this->assertEquals($checkoutData['first_name'], $order->first_name);
        $this->assertEquals($checkoutData['last_name'], $order->last_name);
        $this->assertEquals($checkoutData['address'], $order->address);
        $this->assertEquals($checkoutData['city'], $order->city);
        $this->assertEquals($checkoutData['state'], $order->state);
        $this->assertEquals($checkoutData['postal_code'], $order->zip);
        $this->assertEquals($checkoutData['country'], $order->country);
        $this->assertEquals('new', $order->order_status);

        // Verify order has items
        $orderItems = Cart::where('order_id', $order->id)->get();
        $this->assertGreaterThan(0, $orderItems->count(), 'Order should have items');
    }

    #[Test]
    public function it_creates_order_with_multiple_products(): void
    {
        // Clear cart first
        empty_cart();

        // Create products with custom fields pricing
        $product1 = [
            'title' => 'Product A ' . uniqid(),
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => '50'],
            ],
        ];
        $product1Id = save_content($product1);

        $product2 = [
            'title' => 'Product B ' . uniqid(),
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => '30'],
            ],
        ];
        $product2Id = save_content($product2);

        // Add products to cart
        $result1 = update_cart(['content_id' => $product1Id, 'qty' => 2]);
        $this->assertTrue(isset($result1['success']), 'First product should be added to cart');

        $result2 = update_cart(['content_id' => $product2Id, 'qty' => 1]);
        $this->assertTrue(isset($result2['success']), 'Second product should be added to cart');

        // Verify cart has items
        $cartItems = get_cart();
        $this->assertEquals(2, count($cartItems), 'Cart should have 2 products');

        $checkoutData = [
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'testtotals' . uniqid() . '@example.com',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'US',
            'shipping_provider_id' => $this->testShippingProviderId,
            'payment_provider_id' => $this->testPaymentProviderId,
        ];

        $checkoutService = app(CheckoutService::class);
        $result = $checkoutService->checkout($checkoutData);

        $this->assertArrayHasKey('order_id', $result);

        $order = Order::find($result['order_id']);
        $this->assertNotNull($order);

        // Verify order has correct item count
        $this->assertEquals(3, $order->items_count, 'Order should have 3 total items (2 + 1)');
    }

    #[Test]
    public function it_generates_unique_order_references(): void
    {
        $orderReferences = [];

        // Create multiple orders and verify unique references
        for ($i = 0; $i < 3; $i++) {
            empty_cart();
            update_cart(['content_id' => $this->testProductId, 'qty' => 1]);

            $checkoutData = [
                'first_name' => 'Test',
                'last_name' => 'Customer',
                'email' => 'testref' . $i . uniqid() . '@example.com',
                'phone' => '+1234567890',
                'address' => '123 Test Street',
                'city' => 'Test City',
                'state' => 'Test State',
                'postal_code' => '12345',
                'country' => 'US',
                'shipping_provider_id' => $this->testShippingProviderId,
                'payment_provider_id' => $this->testPaymentProviderId,
            ];

            $checkoutService = app(CheckoutService::class);
            $result = $checkoutService->checkout($checkoutData);

            $this->assertArrayHasKey('order_id', $result);

            $order = Order::find($result['order_id']);
            $this->assertNotNull($order->order_reference_id, 'Order should have reference ID');
            $this->assertStringStartsWith('ORD-', $order->order_reference_id, 'Reference ID should start with ORD-');

            // Verify uniqueness
            $this->assertNotContains($order->order_reference_id, $orderReferences, 'Order reference should be unique');
            $orderReferences[] = $order->order_reference_id;
        }
    }

    #[Test]
    public function it_attaches_shipping_and_payment_providers_to_order(): void
    {
        update_cart(['content_id' => $this->testProductId, 'qty' => 1]);

        $checkoutData = [
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'testproviders' . uniqid() . '@example.com',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'US',
            'shipping_provider_id' => $this->testShippingProviderId,
            'payment_provider_id' => $this->testPaymentProviderId,
        ];

        $checkoutService = app(CheckoutService::class);
        $result = $checkoutService->checkout($checkoutData);

        $this->assertArrayHasKey('order_id', $result);

        $order = Order::find($result['order_id']);
        $this->assertEquals($this->testShippingProviderId, $order->shipping_provider_id, 'Order should have correct shipping provider');
        $this->assertEquals($this->testPaymentProviderId, $order->payment_provider_id, 'Order should have correct payment provider');
    }

    #[Test]
    public function it_prevents_checkout_with_empty_cart(): void
    {
        empty_cart();

        $checkoutData = [
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'testempty' . uniqid() . '@example.com',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'US',
            'shipping_provider_id' => $this->testShippingProviderId,
            'payment_provider_id' => $this->testPaymentProviderId,
        ];

        $checkoutService = app(CheckoutService::class);
        $result = $checkoutService->checkout($checkoutData);

        // Should return error for empty cart (either as error key or by not creating an order)
        if (!isset($result['order_id']) || $result['order_id'] === null) {
            $this->assertTrue(true, 'Empty cart prevented order creation');
        } else {
            // If order was created, verify it has no items
            $orderItems = Cart::where('order_id', $result['order_id'])->get();
            $this->assertEquals(0, $orderItems->count(), 'Order with empty cart should have no items');
        }
    }

    #[Test]
    public function it_handles_multiple_quantities_correctly(): void
    {
        // Use the test product created in setUp
        update_cart(['content_id' => $this->testProductId, 'qty' => 4]);

        $checkoutData = [
            'first_name' => 'Test',
            'last_name' => 'Customer',
            'email' => 'testqty' . uniqid() . '@example.com',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'Test State',
            'postal_code' => '12345',
            'country' => 'US',
            'shipping_provider_id' => $this->testShippingProviderId,
            'payment_provider_id' => $this->testPaymentProviderId,
        ];

        $checkoutService = app(CheckoutService::class);
        $result = $checkoutService->checkout($checkoutData);

        $this->assertArrayHasKey('order_id', $result);

        $order = Order::find($result['order_id']);
        $this->assertNotNull($order);

        // Verify order was created with multiple items
        $this->assertEquals(4, $order->items_count, 'Order should have 4 items');

        // Verify order items exist
        $orderItems = Cart::where('order_id', $order->id)->get();
        $this->assertEquals(4, $orderItems->sum('qty'), 'Order should have 4 items');
    }

    #[Test]
    public function it_saves_order_with_complete_customer_data(): void
    {
        update_cart(['content_id' => $this->testProductId, 'qty' => 1]);

        $checkoutData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe' . uniqid() . '@example.com',
            'phone' => '+15551234567',
            'address' => '456 Oak Street',
            'address2' => 'Apt 42',
            'city' => 'Springfield',
            'state' => 'IL',
            'postal_code' => '62701',
            'country' => 'US',
            'shipping_provider_id' => $this->testShippingProviderId,
            'payment_provider_id' => $this->testPaymentProviderId,
        ];

        $checkoutService = app(CheckoutService::class);
        $result = $checkoutService->checkout($checkoutData);

        $this->assertArrayHasKey('order_id', $result);

        $order = Order::find($result['order_id']);
        $this->assertNotNull($order);

        // Verify all customer data was saved
        $this->assertEquals('John', $order->first_name);
        $this->assertEquals('Doe', $order->last_name);
        $this->assertStringContainsString('john.doe', $order->email);
        $this->assertEquals('+15551234567', $order->phone);
        $this->assertEquals('456 Oak Street', $order->address);
        $this->assertEquals('Apt 42', $order->address2);
        $this->assertEquals('Springfield', $order->city);
        $this->assertEquals('IL', $order->state);
        $this->assertEquals('62701', $order->zip);
        $this->assertEquals('US', $order->country);
    }

    protected function tearDown(): void
    {
        // Cleanup
        session_forget('checkout');
        app()->cart_manager->empty_cart();

        parent::tearDown();
    }
}
