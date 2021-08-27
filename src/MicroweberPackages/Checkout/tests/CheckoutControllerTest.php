<?php

namespace MicroweberPackages\Checkout\tests;

use MicroweberPackages\Checkout\Http\Controllers\CheckoutController;
use MicroweberPackages\Core\tests\TestCase;


class CheckoutControllerTest extends TestCase
{
    public static $content_id = 1;
    public $session_cookie;
    public $session_id;

    public function setUp(): void
    {
        parent::setUp();

        $this->session_id = session()->getId();
        $this->session_cookie = [session()->getName() => $this->session_id];
    }

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
            'is_active' => 1,'is_deleted'=>0);


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
 //app()->user_manager->logout();

        $rand = uniqid();
        $this->_addProductToCart('some product');
        $params = [];
        $params['id'] = 'shop-checkout';


        $this->assertEquals(1, cart_get_items_count());




        $request = new \Illuminate\Http\Request();
        $request->merge($params);
        $request->merge($_REQUEST);

        $controller = app()->make(CheckoutController::class);

        $response = $controller->index($request);
        $this->assertEquals(302, $response->status());
        $this->assertEquals($response->getTargetUrl(), route('checkout.contact_information'));

        $this->_addProductToCart('some product2');
        $this->assertEquals(2, cart_get_items_count());

        $this->_addProductToCart('some product3');
        $this->assertEquals(3, cart_get_items_count());

        $this->_addProductToCart('some product4');
        $this->assertEquals(4, cart_get_items_count());



        $cookies = array_merge($_COOKIE, $this->session_cookie);


        $response = $this->withCookies($cookies)->call(
            'GET',
            route('checkout.contact_information'),$parameters = [], $cookies  , $files = [], $server =$_SERVER, $content = null
        );


        $this->assertEquals(200, $response->status());


        $params = [];
        $params['first_name'] = 'Name' . $rand;
        $params['last_name'] = 'LastName' . $rand;
        $params['email'] = 'LastName' . $rand . '@example.com';
        $params['phone'] = '123456789' . $rand;
        $response = $this->withCookies($cookies)->call(
            'POST',
            route('checkout.contact_information_save', $params),$parameters =$params, $cookies
        );
        $this->assertEquals(302, $response->status());
        $this->assertEquals($response->getTargetUrl(), route('checkout.shipping_method'));


        $user_info = app()->checkout_manager->getUserInfo();
        $this->assertEquals($user_info['first_name'], $params['first_name']);
        $this->assertEquals($user_info['last_name'], $params['last_name']);
        $this->assertEquals($user_info['email'], $params['email']);
        $this->assertEquals($user_info['phone'], $params['phone']);




        $response = $this->withCookies($cookies)->call(
            'GET',
            route('checkout.shipping_method'),$parameters = [], $cookies  , $files = [], $server =$_SERVER, $content = null
        );
        $this->assertEquals(200, $response->status());







    }

}
