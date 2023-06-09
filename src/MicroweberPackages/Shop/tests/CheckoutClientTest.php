<?php
namespace MicroweberPackages\Shop\tests;

use MicroweberPackages\Checkout\CheckoutManager;
use MicroweberPackages\Core\tests\TestCase;


class CheckoutClientTest extends TestCase
{
    public static $content_id = 1;

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

    public function testCheckoutClientNames()
    {
        empty_cart();


        $this->_addProductToCart('CheckoutClientTestProduct 1');
        $this->_addProductToCart('CheckoutClientTestProduct 2');
        $this->_addProductToCart('CheckoutClientTestProduct 3');
        $this->_addProductToCart('CheckoutClientTestProduct 4');

        $email = 'client+'.uniqid('testCheckoutClientNames').'test@microweber.com';
        $checkoutDetails = array();
        $checkoutDetails['email'] = $email;
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';
        $checkoutDetails['phone'] = '08812345678';
        $checkoutDetails['address'] = 'Business Park, Mladost 4';
        $checkoutDetails['city'] = 'Sofia';
        $checkoutDetails['state'] = 'Sofia City';
        $checkoutDetails['country'] = 'Bulgaria';
        $checkoutDetails['zip'] = '1000';

        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);

        $this->assertArrayHasKey('success', $checkoutStatus);
        $this->assertArrayHasKey('id', $checkoutStatus);
        $this->assertArrayHasKey('order_completed', $checkoutStatus);
        $this->assertArrayHasKey('amount', $checkoutStatus);
        $this->assertArrayHasKey('currency', $checkoutStatus);
        $this->assertArrayHasKey('order_status', $checkoutStatus);

        $this->assertEquals($checkoutStatus['order_completed'], 1);
        $this->assertEquals($checkoutStatus['first_name'],  $checkoutDetails['first_name']);
        $this->assertEquals($checkoutStatus['last_name'],  $checkoutDetails['last_name']);
        $this->assertEquals($checkoutStatus['email'],  $checkoutDetails['email']);
        $this->assertEquals($checkoutStatus['country'],  $checkoutDetails['country']);
        $this->assertEquals($checkoutStatus['city'],  $checkoutDetails['city']);
        $this->assertEquals($checkoutStatus['state'],  $checkoutDetails['state']);
        $this->assertEquals($checkoutStatus['zip'],  $checkoutDetails['zip']);
        $this->assertEquals($checkoutStatus['address'],  $checkoutDetails['address']);




    }





}
