<?php

namespace MicroweberPackages\Checkout\tests;

use MicroweberPackages\Checkout\Http\Controllers\CheckoutController;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;


class CheckoutControllerTest extends TestCase
{
    public static $content_id = 1;
    public static $productPrice = 0;
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
        app()->database_manager->extended_save_set_permission(true);


        $productPrice = rand(1, 4444);

        $params = array(
            'title' => $title,
            'content_type' => 'product',
            'subtype' => 'product',

            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Purple', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => $productPrice),

            ),
            'is_active' => 1, 'is_deleted' => 0);


        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);
        $prices_data = app()->shop_manager->get_product_prices($saved_id, false);

        $this->assertEquals($prices_data['Price'],$productPrice);

        $this->assertEquals($saved_id, ($get['id']));
        self::$content_id = $saved_id;
        self::$productPrice = $productPrice;

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
        empty_cart();

        $controller = app()->make(CheckoutController::class);
        $cookies = array_merge($_COOKIE, $this->session_cookie);


        $newShopPage = new Page();
        $newShopPage->title = 'my-new-shop-page-'.uniqid();
        $newShopPage->is_shop = 1;
        $newShopPage->content_type = 'page';
        $newShopPage->url = 'my-new-shop-page';
        $newShopPage->subtype = 'dynamic';
        $newShopPage->save();

        $request = new \Illuminate\Http\Request();
        $request->merge($_REQUEST);


        $response = $this->withCookies($cookies)->call(
            'GET',
            route('checkout.contact_information'), $parameters = [], $cookies, $files = [], $server = $_SERVER, $content = null
        );

        $this->assertEquals(302, $response->status());
        $this->assertEquals(true, str_contains($response->getTargetUrl(), 'my-new-shop-page'));



        $rand = uniqid();
        $this->_addProductToCart('some product');



        // should not add product with non exisintg price
        $productPrice2 = rand(5555, 6677);

        $add_to_cart = array(
            'content_id' => self::$content_id,
            'price' => $productPrice2,
        );
        $cart_add = update_cart($add_to_cart);

        $this->assertEquals($cart_add["cart_sum"], self::$productPrice);




        $params = [];
        $params['id'] = 'shop-checkout';


        $this->assertEquals(1, cart_get_items_count());


        $request = new \Illuminate\Http\Request();
        $request->merge($params);
        $request->merge($_REQUEST);


        $response = $controller->index($request);
        $this->assertEquals(302, $response->status());
        $this->assertEquals($response->getTargetUrl(), route('checkout.contact_information'));

        $this->_addProductToCart('some product2');
        $this->assertEquals(2, cart_get_items_count());

        $this->_addProductToCart('some product3');
        $this->assertEquals(3, cart_get_items_count());

        $this->_addProductToCart('some product4');
        $this->assertEquals(4, cart_get_items_count());




        $response = $this->withCookies($cookies)->call(
            'GET',
            route('checkout.contact_information'), $parameters = [], $cookies, $files = [], $server = $_SERVER, $content = null
        );


        $this->assertEquals(200, $response->status());

        $params = [];
        $params['first_name'] = 'Name' . $rand;
        $params['last_name'] = 'LastName' . $rand;
        $params['email'] = 'LastName' . $rand . '@example.com';
        $params['phone'] = '123456789' . $rand;
        $response = $this->withCookies($cookies)->call(
            'POST',
            route('checkout.contact_information_save', $params), $parameters = $params, $cookies
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
            route('checkout.shipping_method'), $parameters = [], $cookies, $files = [], $server = $_SERVER, $content = null
        );
        $this->assertEquals(200, $response->status());

        app()->shipping_manager->driver('shop/shipping/gateways/pickup')->enable();
        $this->assertEquals(true, app()->shipping_manager->driver('shop/shipping/gateways/pickup')->isEnabled());


        $shipping_modules = app()->checkout_manager->getShippingModules();

        $this->assertEquals(true, !empty($shipping_modules));
//        $this->assertEquals(true, str_contains($response->getContent(), $shipping_modules[0]['module']));

    }

}
