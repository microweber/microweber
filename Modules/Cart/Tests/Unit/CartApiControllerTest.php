<?php

namespace Modules\Cart\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
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

    public function testUpdateCart()
    {
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

    public function testRemoveCartItem()
    {
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

    public function testUpdateCartItemQty()
    {
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

    public function testUpdateCartWithInvalidProduct()
    {
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

    public function testRemoveNonExistentCartItem()
    {
        empty_cart();

        $requestData = [
            'id' => 99999
        ];

        $request = new Request();
        $request->merge($requestData);

        $response = $this->controller->removeCartItem($request);

        $this->assertTrue(isset($response['error']));
    }

    public function testUpdateCartItemWithInvalidQty()
    {
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
}
