<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\Api\CartApiController;
use Modules\Cart\Models\Cart;
use Modules\Checkout\Http\Controllers\Api\CheckoutApiController;
use Modules\Content\Models\Content;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Tests\TestCase;

class EcommerceApiTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    }

    /**
     * Test product listing API.
     */
    public function test_can_list_products(): void
    {
        // Create test products
        $this->createTestProduct('Product 1', 100);
        $this->createTestProduct('Product 2', 200);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonFragment(['title' => 'Product 1'])
            ->assertJsonFragment(['title' => 'Product 2']);
    }

    /**
     * Test product search API.
     */
    public function test_can_search_products(): void
    {
        $this->createTestProduct('Test Product', 100);
        $this->createTestProduct('Another Item', 200);

        $response = $this->getJson('/api/products?search=Test');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('data.data.0.title', 'Test Product');
    }

    /**
     * Test product show API.
     */
    public function test_can_show_product(): void
    {
        $product = $this->createTestProduct('Test Product', 100);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'title' => 'Test Product',
                ],
            ]);
    }

    /**
     * Test product show API returns 404 for non-existent product.
     */
    public function test_show_product_returns_404_for_non_existent(): void
    {
        $response = $this->getJson('/api/products/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Product not found',
            ]);
    }

    /**
     * Test product by slug API.
     */
    public function test_can_get_product_by_slug(): void
    {
        $slug = 'test-product-slug-' . uniqid();
        $product = $this->createTestProduct('Test Product Slug', 100, ['url' => $slug]);

        $response = $this->getJson('/api/products/slug/' . $slug);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Test Product Slug',
                ],
            ]);
    }

    /**
     * Test featured products API.
     */
    public function test_can_get_featured_products(): void
    {
        $this->createTestProduct('Featured 1', 100, ['is_featured' => 1]);
        $this->createTestProduct('Featured 2', 200, ['is_featured' => 1]);
        $this->createTestProduct('Regular', 300);

        $response = $this->getJson('/api/products/featured');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonFragment(['title' => 'Featured 1'])
            ->assertJsonFragment(['title' => 'Featured 2']);
    }

    /**
     * Test cart get API.
     */
    public function test_can_get_cart(): void
    {
        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'items' => [],
                ],
            ]);
    }

    /**
     * Test add item to cart API.
     */
    public function test_can_add_item_to_cart(): void
    {
        $product = $this->createTestProduct('Test Product', 100);

        $response = $this->postJson('/api/cart', [
            'content_id' => $product->id,
            'qty' => 2,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('data.product.price', 100);

        // Verify cart was updated
        $this->assertGreaterThan(0, cart_sum());
    }

    /**
     * Test add item to cart with invalid product.
     */
    public function test_cannot_add_invalid_product_to_cart(): void
    {
        $response = $this->postJson('/api/cart', [
            'content_id' => 99999,
            'qty' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    /**
     * Test update cart item quantity.
     */
    public function test_can_update_cart_item_quantity(): void
    {
        // First add item to cart
        $product = $this->createTestProduct('Test Product', 100);
        $cartData = update_cart(['content_id' => $product->id, 'qty' => 1]);
        $cartItemId = $cartData['product']['cart_item_id'] ?? null;

        $this->assertNotNull($cartItemId, 'Cart item should have an ID');

        // Drive the controller via an internal request so it shares the same
        // in-process session the cart was seeded under. A through-the-kernel
        // HTTP request regenerates the session id per call (cart is session-
        // scoped), so cross-request cart state can't be exercised that way.
        $request = Request::create("/api/cart/{$cartItemId}", 'PUT', ['qty' => 3]);
        $response = app(CartApiController::class)->update($request, (int) $cartItemId);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    /**
     * Test remove item from cart.
     */
    public function test_can_remove_cart_item(): void
    {
        // First add item to cart
        $product = $this->createTestProduct('Test Product', 100);
        $cartData = update_cart(['content_id' => $product->id, 'qty' => 1]);
        $cartItemId = $cartData['product']['cart_item_id'] ?? null;

        $this->assertNotNull($cartItemId);

        // Internal request: share the in-process cart session (see above).
        $response = app(CartApiController::class)->destroy((int) $cartItemId);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    /**
     * Test empty cart.
     */
    public function test_can_empty_cart(): void
    {
        // Add item first
        $product = $this->createTestProduct('Test Product', 100);
        update_cart(['content_id' => $product->id, 'qty' => 1]);

        $this->assertGreaterThan(0, cart_sum());

        $response = $this->deleteJson('/api/cart/empty');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test get cart totals.
     */
    public function test_can_get_cart_totals(): void
    {
        $product = $this->createTestProduct('Test Product', 100);
        update_cart(['content_id' => $product->id, 'qty' => 2]);

        $response = $this->getJson('/api/cart/totals');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'subtotal',
                    'tax',
                    'discount',
                    'shipping',
                    'total',
                ],
            ]);
    }

    /**
     * Test checkout get API.
     */
    public function test_can_get_checkout_data(): void
    {
        $product = $this->createTestProduct('Test Product', 100);
        update_cart(['content_id' => $product->id, 'qty' => 1]);

        // Internal request: share the in-process cart session (see above).
        $request = Request::create('/api/checkout', 'GET');
        $response = app(CheckoutApiController::class)->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertTrue($data['success']);
        $this->assertArrayHasKey('cart', $data['data']);
        $this->assertArrayHasKey('customer', $data['data']);
        $this->assertArrayHasKey('shipping_methods', $data['data']);
        $this->assertArrayHasKey('payment_methods', $data['data']);
    }

    /**
     * Test checkout returns error for empty cart.
     */
    public function test_checkout_returns_error_for_empty_cart(): void
    {
        empty_cart();

        $response = $this->getJson('/api/checkout');

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Cart is empty',
            ]);
    }

    /**
     * Test checkout process.
     */
    public function test_can_process_checkout(): void
    {
        $product = $this->createTestProduct('Test Product', 100);
        update_cart(['content_id' => $product->id, 'qty' => 1]);

        $response = $this->postJson('/api/checkout', [
            'email' => 'test@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '+1234567890',
            'address' => '123 Test St',
            'city' => 'Test City',
            'state' => 'Test State',
            'zip' => '12345',
            'country' => 'US',
            'terms' => true,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'order_id',
                ],
            ]);
    }

    /**
     * Test checkout validation.
     */
    public function test_checkout_validates_required_fields(): void
    {
        $product = $this->createTestProduct('Test Product', 100);
        update_cart(['content_id' => $product->id, 'qty' => 1]);

        $response = $this->postJson('/api/checkout', [
            'email' => '',
            'first_name' => '',
            'last_name' => '',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    /**
     * Test checkout validate endpoint.
     */
    public function test_can_validate_checkout_data(): void
    {
        $product = $this->createTestProduct('Test Product', 100);
        update_cart(['content_id' => $product->id, 'qty' => 1]);

        // Internal request: share the in-process cart session (see above).
        $request = Request::create('/api/checkout/validate', 'POST', [
            'email' => 'test@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        $response = app(CheckoutApiController::class)->validate($request);

        $data = $response->getData(true);
        $this->assertEquals(200, $response->getStatusCode(), 'validate should pass with a seeded cart: ' . json_encode($data));
        $this->assertTrue($data['success']);
        $this->assertEquals('Checkout data is valid', $data['message']);
    }

    /**
     * Test shipping methods endpoint.
     */
    public function test_can_get_shipping_methods(): void
    {
        $response = $this->getJson('/api/checkout/shipping-methods');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data',
            ]);
    }

    /**
     * Test payment methods endpoint.
     */
    public function test_can_get_payment_methods(): void
    {
        $response = $this->getJson('/api/checkout/payment-methods');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data',
            ]);
    }

    /**
     * Test order status endpoint.
     */
    public function test_can_get_order_status(): void
    {
        // Create a test order
        $order = Order::create([
            'order_reference_id' => 'ORD-TEST-123',
            'email' => 'test@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'amount' => 100,
            'currency' => 'USD',
            'order_status' => 'completed',
            'is_paid' => 1,
            'payment_status' => 'completed',
        ]);

        $response = $this->getJson("/api/checkout/order/{$order->order_reference_id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'order_reference_id' => 'ORD-TEST-123',
                    'status' => 'completed',
                ],
            ]);
    }

    /**
     * Test order status returns 404 for non-existent order.
     */
    public function test_order_status_returns_404_for_non_existent(): void
    {
        $response = $this->getJson('/api/checkout/order/NON-EXISTENT');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Order not found',
            ]);
    }

    /**
     * Test checkout update endpoint.
     */
    public function test_can_update_checkout_session(): void
    {
        $response = $this->putJson('/api/checkout', [
            'email' => 'updated@example.com',
            'first_name' => 'Updated',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Checkout data updated',
            ]);
    }

    /**
     * Create a test product.
     */
    private function createTestProduct(string $title, float $price, array $extra = []): Content
    {
        app()->database_manager->extended_save_set_permission(true);

        $data = [
            'title' => $title,
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'price', 'value' => $price],
            ],
        ];

        if (isset($extra['url'])) {
            $data['url'] = $extra['url'];
        }

        // Use save_content to create product (handles price custom field)
        $id = save_content($data);

        $product = Content::find($id);

        // Directly update is_featured since save_content doesn't pass it through
        if (isset($extra['is_featured'])) {
            \Illuminate\Support\Facades\DB::table('content')
                ->where('id', $id)
                ->update(['is_featured' => (int) $extra['is_featured']]);
            $product = Content::find($id);
        }

        return $product;
    }
}
