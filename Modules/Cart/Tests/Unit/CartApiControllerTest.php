<?php

namespace Modules\Cart\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\CartApiController;

class CartApiControllerTest extends TestCase
{
    protected $controller;
    protected static $content_id = 1;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new CartApiController();


        $params = array(
            'title' => 'My new product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Purple', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => '30'),

            ),
            'is_active' => 1,);


        $saved_id = save_content($params);
        self::$content_id = $saved_id;
    }

    #[Test]

    public function it_update_cart(): void {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

        // Create a test product
        $params = array(
            'title' => 'Test Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Red', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => '50'),
            ),
            'is_active' => 1,
        );

        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);
        $this->assertEquals($saved_id, ($get['id']));
        self::$content_id = $saved_id;

        // Test update cart
        $requestData = [
            'content_id' => self::$content_id,
            'color' => 'Red',
            'qty' => 2
        ];

        $request = new Request();
        $request->merge($requestData);

        $response = $this->controller->updateCart($request);

        $this->assertTrue(isset($response['success']));
        $this->assertTrue(isset($response['product']));
        $this->assertEquals($response['product']['price'], 50);
        $this->assertEquals($response['product']['custom_fields_data']['color'], 'Red');
    }

    #[Test]

    public function it_remove_cart_item(): void {
        empty_cart();

        // First add an item to cart
        $add_to_cart = array(
            'content_id' => self::$content_id,
            'qty' => 1
        );
        $cart_update = update_cart($add_to_cart);

        $cart_item_id = $cart_update['product']['cart_item_id'];
        // Now test removing it
        $requestData = [
            'id' => $cart_item_id
        ];

        $request = new Request();
        $request->merge($requestData);

        $response = $this->controller->removeCartItem($request);

        $this->assertTrue(isset($response['success']));

        // Verify cart is empty
        $cart_items = get_cart();
        $this->assertTrue(empty($cart_items));
    }

    #[Test]

    public function it_update_cart_item_qty(): void {
        empty_cart();

        // First add an item to cart
        $add_to_cart = array(
            'content_id' => self::$content_id,
            'qty' => 1
        );
        $cart_update = update_cart($add_to_cart);
        $cart_item_id = $cart_update['product']['cart_item_id'];

        // Now test updating quantity
        $requestData = [
            'id' => $cart_item_id,
            'qty' => 3
        ];

        $request = new Request();
        $request->merge($requestData);

        $response = $this->controller->updateCartItemQty($request);

        $this->assertTrue(isset($response['success']));
        // Verify quantity was updated
        $cart_items = get_cart();
        $this->assertEquals($cart_items[0]['qty'], 3);
    }

    #[Test]

    public function it_update_cart_with_invalid_product(): void {
        empty_cart();

        // Test with non-existent product
        $requestData = [
            'content_id' => 99999,
            'qty' => 1
        ];

        $request = new Request();
        $request->merge($requestData);

        $response = $this->controller->updateCart($request);

        $this->assertTrue(isset($response['error']));
    }

    #[Test]

    public function it_remove_non_existent_cart_item(): void {
        empty_cart();

        $requestData = [
            'id' => 99999
        ];

        $request = new Request();
        $request->merge($requestData);

        $response = $this->controller->removeCartItem($request);

        $this->assertTrue(isset($response['error']));
    }

    #[Test]

    public function it_update_cart_item_with_invalid_qty(): void {
        empty_cart();

        // First add an item to cart
        $add_to_cart = array(
            'content_id' => self::$content_id,
            'qty' => 1
        );
        update_cart($add_to_cart);

        // Test with invalid quantity
        $requestData = [
            'id' => self::$content_id,
            'qty' => -1
        ];

        $request = new Request();
        $request->merge($requestData);

        $response = $this->controller->updateCartItemQty($request);

        $this->assertTrue(isset($response['error']));
    }

    /*
     * Cycle-165 / wave3-c (2026-05-10) — empty-cart + missing-param
     * edge cases. PM Wave 3 brief: cart controller test gaps — 7
     * existing tests cover the happy path + invalid product / qty,
     * but the empty-cart and missing-required-field paths weren't
     * covered. The block below adds 5 tests for those gaps.
     */

    #[Test]
    public function it_sum_cart_returns_zero_on_empty_cart(): void {
        empty_cart();

        $request = new Request();
        $response = $this->controller->sumCart($request);

        // cart_sum() returns the numeric total (or formatted string
        // depending on settings). On an empty cart the numeric value
        // is 0 — accept either int 0, string '0', or any falsy
        // representation so the test isn't fragile to formatter
        // changes (e.g. "0.00" or "$0.00").
        $this->assertNotNull($response,
            'sumCart on empty cart MUST return a non-null response.');
        $stripped = is_string($response)
            ? preg_replace('/[^\d.]/', '', $response)
            : (string) $response;
        $this->assertSame(0.0, (float) $stripped,
            'sumCart on empty cart MUST report total = 0.');
    }

    #[Test]
    public function it_empty_cart_on_already_empty_does_not_error(): void {
        empty_cart();

        $request = new Request();
        $response = $this->controller->emptyCart($request);

        // empty_cart() returns a success indicator either way —
        // calling it on an already-empty cart is a no-op, NOT an
        // error. Pin: response MUST NOT be an error envelope.
        $isErrorEnvelope = is_array($response) && isset($response['error']);
        $this->assertFalse($isErrorEnvelope,
            'emptyCart on already-empty cart MUST NOT return an error '
            . 'envelope — it should be a no-op success.');
    }

    #[Test]
    public function it_empty_cart_wipes_existing_items(): void {
        empty_cart();

        // Seed two items via update_cart so the cart has state to wipe.
        update_cart(['content_id' => self::$content_id, 'qty' => 1]);
        update_cart(['content_id' => self::$content_id, 'qty' => 2]);
        $beforeItems = app()->cart_manager->get();
        $this->assertGreaterThan(0, count($beforeItems ?? []),
            'Pre-condition: cart MUST have at least 1 item before '
            . 'emptyCart is called (or this test is vacuous).');

        // Empty via the controller endpoint.
        $this->controller->emptyCart(new Request());

        $afterItems = app()->cart_manager->get();
        $this->assertSame(0, count($afterItems ?? []),
            'emptyCart MUST wipe all items — cart_manager->get() should '
            . 'return [] after the controller call.');
    }

    #[Test]
    public function it_update_cart_with_no_content_id_returns_error(): void {
        empty_cart();

        // Missing required `content_id` parameter — the cart manager
        // CANNOT add a product without one. The controller delegates
        // to update_cart() which MUST surface an error envelope.
        $request = new Request();
        $request->merge(['qty' => 1]); // intentionally no content_id
        $response = $this->controller->updateCart($request);

        $this->assertTrue(
            isset($response['error']) || empty($response['success']),
            'updateCart without content_id MUST surface an error or '
            . 'fail to register a success — silently no-op accepting '
            . 'the request would lead to confusing UX.'
        );
    }

    #[Test]
    public function it_remove_cart_item_with_no_id_returns_error(): void {
        empty_cart();

        // Pre-seed an item so there's something the controller could
        // theoretically remove — but call removeCartItem WITHOUT the
        // `id` field. Should error rather than e.g. wipe everything.
        update_cart(['content_id' => self::$content_id, 'qty' => 1]);
        $beforeCount = count(app()->cart_manager->get() ?? []);
        $this->assertGreaterThan(0, $beforeCount);

        $request = new Request(); // no `id`
        $response = $this->controller->removeCartItem($request);

        $this->assertTrue(
            isset($response['error']),
            'removeCartItem without `id` MUST return an error envelope '
            . '— never silently wipe the cart on missing param.'
        );
        $afterCount = count(app()->cart_manager->get() ?? []);
        $this->assertSame($beforeCount, $afterCount,
            'removeCartItem without `id` MUST NOT mutate the cart.');
    }
}
