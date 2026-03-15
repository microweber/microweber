<?php

namespace Tests\Feature\Regression;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;
use Modules\Payment\Models\PaymentProvider;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Full Regression Test Suite - Frontend Checkout Flow
 *
 * End-to-end testing of the complete checkout flow including:
 * - Add to cart
 * - Cart management
 * - Checkout process
 * - Payment integration
 *
 * @covers \Modules\Cart
 * @covers \Modules\Checkout
 * @covers \Modules\Order
 * @covers \Modules\Payment
 */
class FrontendCheckoutRegressionTest extends TestCase
{
    use LazilyRefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedPaymentProviders();
    }

    /**
     * Test complete checkout flow with bank transfer
     */
    #[Test]
    public function it_complete_checkout_flow_with_bank_transfer(): void
    {
        // Step 1: Create a product
        $product = $this->createTestProduct([
            'title' => 'Test Product',
            'price' => 99.99,
            'sku' => 'TEST-001',
        ]);

        // Step 2: Add product to cart
        $cartResponse = $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 2,
        ]);
        $cartResponse->assertStatus(200);

        // Step 3: Verify cart contains item
        $cart = Cart::first();
        $this->assertNotNull($cart);
        $this->assertEquals(2, $cart->getItemsCount());

        // Step 4: Get checkout page
        $checkoutPageResponse = $this->get('/checkout');
        $checkoutPageResponse->assertStatus(200);

        // Step 5: Submit checkout form with bank transfer
        $checkoutResponse = $this->post('/checkout/process', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'country' => 'US',
            'zip' => '12345',
            'payment_method' => 'bank_transfer',
            'terms_accepted' => true,
        ]);

        $checkoutResponse->assertStatus(200)->assertJson(['success' => true]);

        // Step 6: Verify order was created
        $order = Order::where('email', 'john@example.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->order_status);
        $this->assertEquals(199.98, $order->amount); // 2 * 99.99

        // Step 7: Verify cart was cleared
        $cart->refresh();
        $this->assertEquals(0, $cart->getItemsCount());
    }

    /**
     * Test PayPal checkout flow
     */
    #[Test]
    public function it_checkout_flow_with_paypal(): void
    {
        $product = $this->createTestProduct([
            'title' => 'PayPal Test Product',
            'price' => 149.99,
            'sku' => 'PAYPAL-001',
        ]);

        // Add to cart
        $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 1,
        ]);

        // Submit checkout with PayPal
        $checkoutResponse = $this->post('/checkout/process', [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => '+0987654321',
            'address' => '456 Payment Street',
            'city' => 'Payment City',
            'country' => 'US',
            'zip' => '54321',
            'payment_method' => 'paypal',
            'terms_accepted' => true,
        ]);

        $checkoutResponse->assertStatus(200);

        // Verify order
        $order = Order::where('email', 'jane@example.com')->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->order_status);
    }

    /**
     * Test cart persistence across sessions
     */
    #[Test]
    public function it_cart_persists_across_sessions(): void
    {
        $product = $this->createTestProduct();

        // Add to cart
        $response = $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 3,
        ]);

        $response->assertStatus(200);

        // Get cart ID from session/cookie
        $cart = Cart::first();
        $this->assertNotNull($cart);

        // Verify cart data is accessible
        $cartData = $cart->getItems();
        $this->assertCount(1, $cartData);
        $this->assertEquals(3, $cartData[0]['qty']);
    }

    /**
     * Test cart item quantity update
     */
    #[Test]
    public function it_cart_quantity_update(): void
    {
        $product = $this->createTestProduct();

        // Add to cart
        $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 1,
        ]);

        $cart = Cart::first();

        // Update quantity
        $updateResponse = $this->post('/api/cart/update', [
            'product_id' => $product->id,
            'qty' => 5,
        ]);

        $updateResponse->assertStatus(200);

        $cart->refresh();
        $items = $cart->getItems();
        $this->assertEquals(5, $items[0]['qty']);
    }

    /**
     * Test cart item removal
     */
    #[Test]
    public function it_cart_item_removal(): void
    {
        $product1 = $this->createTestProduct(['sku' => 'PROD-001']);
        $product2 = $this->createTestProduct(['sku' => 'PROD-002']);

        // Add both products
        $this->post('/api/cart/add', [
            'product_id' => $product1->id,
            'qty' => 1,
        ]);
        $this->post('/api/cart/add', [
            'product_id' => $product2->id,
            'qty' => 1,
        ]);

        $cart = Cart::first();
        $this->assertEquals(2, $cart->getItemsCount());

        // Remove first product
        $removeResponse = $this->post('/api/cart/remove', [
            'product_id' => $product1->id,
        ]);

        $removeResponse->assertStatus(200);

        $cart->refresh();
        $this->assertEquals(1, $cart->getItemsCount());
    }

    /**
     * Test checkout validation
     */
    #[Test]
    public function it_checkout_validates_required_fields(): void
    {
        $product = $this->createTestProduct();

        $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 1,
        ]);

        // Submit with missing required fields
        $response = $this->post('/checkout/process', [
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid-email',
            'payment_method' => '',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test checkout with empty cart
     */
    #[Test]
    public function it_checkout_fails_with_empty_cart(): void
    {
        $response = $this->post('/checkout/process', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'payment_method' => 'bank_transfer',
        ]);

        $response->assertStatus(400)->assertJson([
            'error' => 'Cart is empty',
        ]);
    }

    /**
     * Test stock validation during checkout
     */
    #[Test]
    public function it_checkout_validates_stock(): void
    {
        $product = $this->createTestProduct([
            'qty' => 5,
            'track_quantity' => true,
        ]);

        // Try to add more than available stock
        $response = $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 10,
        ]);

        $response->assertStatus(400)->assertJson([
            'error' => 'Not enough stock',
        ]);
    }

    /**
     * Test coupon code application
     */
    #[Test]
    public function it_coupon_code_application(): void
    {
        $product = $this->createTestProduct(['price' => 100]);

        // Create a coupon
        $coupon = \Modules\Coupons\Models\Coupon::factory()->create([
            'coupon_code' => 'TEST20',
            'discount_amount' => 20,
            'discount_type' => 'fixed',
            'is_active' => true,
        ]);

        // Add product to cart
        $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 1,
        ]);

        // Apply coupon
        $couponResponse = $this->post('/api/cart/apply-coupon', [
            'coupon_code' => 'TEST20',
        ]);

        $couponResponse->assertStatus(200);

        $cart = Cart::first();
        $this->assertEquals(80, $cart->getTotal()); // 100 - 20 = 80
    }

    /**
     * Test shipping calculation
     */
    #[Test]
    public function it_shipping_calculation(): void
    {
        $product = $this->createTestProduct([
            'price' => 50,
            'weight' => 2.5,
        ]);

        $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 2,
        ]);

        // Calculate shipping
        $shippingResponse = $this->post('/api/shipping/calculate', [
            'country' => 'US',
            'state' => 'CA',
            'zip' => '90210',
        ]);

        $shippingResponse->assertStatus(200);
        $shippingResponse->assertJsonStructure([
            'shipping_methods',
        ]);
    }

    /**
     * Test order confirmation email
     */
    #[Test]
    public function it_order_confirmation_email_sent(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $product = $this->createTestProduct();

        $this->post('/api/cart/add', [
            'product_id' => $product->id,
            'qty' => 1,
        ]);

        $this->post('/checkout/process', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'country' => 'US',
            'zip' => '12345',
            'payment_method' => 'bank_transfer',
            'terms_accepted' => true,
        ]);

        \Illuminate\Support\Facades\Mail::assertSent(\Modules\Order\Mails\OrderConfirmation::class);
    }

    /**
     * Create a test product
     */
    private function createTestProduct(array $attributes = []): Product
    {
        $defaults = [
            'title' => 'Test Product',
            'price' => 99.99,
            'sku' => 'TEST-' . time(),
            'content_type' => 'product',
            'is_active' => true,
        ];

        return Product::factory()->create(array_merge($defaults, $attributes));
    }

    /**
     * Seed payment providers
     */
    private function seedPaymentProviders(): void
    {
        PaymentProvider::firstOrCreate(
            ['provider' => 'bank_transfer'],
            [
                'name' => 'Bank Transfer',
                'is_active' => true,
                'settings' => [],
            ]
        );

        PaymentProvider::firstOrCreate(
            ['provider' => 'paypal'],
            [
                'name' => 'PayPal',
                'is_active' => true,
                'settings' => [],
            ]
        );
    }
}
