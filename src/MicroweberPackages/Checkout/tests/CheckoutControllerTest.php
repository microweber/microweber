<?php

namespace MicroweberPackages\Checkout\tests;

use MicroweberPackages\Checkout\Http\Controllers\CheckoutController;
use MicroweberPackages\Core\tests\TestCase;


class CheckoutControllerTest extends TestCase
{
    public static $content_id = 1;

    private function _addProductToCart($title)
    {

        $productPrice = rand(1, 4444);

        $params = array(
            'title' => $title,
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Purple', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => '9.99'),

            ),
            'is_active' => 1,);


        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);

        $this->assertEquals($saved_id, ($get['id']));
        self::$content_id = $saved_id;

        $add_to_cart = array(
            'content_id' => self::$content_id,
            'price' => $productPrice,
        );
        $cart_add = update_cart($add_to_cart);

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(isset($cart_add['product']), true);
        $this->assertEquals($cart_add['product']['price'], $productPrice);
    }


    public function testCheckoutController()
    {
        $rand = uniqid();
        $this->_addProductToCart('some product');
        $params = [];
        $params['id'] = 'shop-checkout';

        $request = new \Illuminate\Http\Request();
        $request->merge($params);

        $controller = app()->make(CheckoutController::class);

        $response = $controller->index($request);
        $this->assertEquals(302, $response->status());
        $this->assertEquals($response->getTargetUrl(), route('checkout.contact_information'));


        $response = $this->call(
            'GET',
            route('checkout.contact_information')
        );
        $this->assertEquals(200, $response->status());


        $params = [];
        $params['first_name'] = 'Name' . $rand;
        $params['last_name'] = 'LastName' .$rand;
        $params['email'] = 'LastName' . $rand. '@example.com';
        $params['phone'] = '123456789';
        $response = $this->call(
            'POST',
            route('checkout.contact_information_save', $params)
        );
        $this->assertEquals(302, $response->status());
        $this->assertEquals($response->getTargetUrl(), route('checkout.shipping_method'));

        $response = $this->call(
            'GET',
            route('checkout.shipping_method')
        );
        $this->assertEquals(200, $response->status());

        $shipping_modules = app()->checkout_manager->getShippingModules();


        return;



    }

}
