<?php


namespace Microweber\tests;

class CartTest extends TestCase {

    public function testAddToCart() {

        $add_to_cart = array(
            'content_id' => 1,
            'price'      => 35
        );
        $cart_add = update_cart($add_to_cart);

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(isset($cart_add['product']), true);
        $this->assertEquals($cart_add['product']['price'], 35);
    }


    public function testGetCart() {

        empty_cart();
        $add_to_cart = array(
            'content_id' => 1,
            'qty'        => 2,
            'price'      => 350
        );
        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(!empty($cart_items), true);

    }

    public function testSumCart() {
        empty_cart();
        $add_to_cart = array(
            'content_id' => 1,
            'qty'        => 3,
            'price'      => 300
        );
        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();

        $sum = cart_sum();
        $this->assertEquals($sum, 900);


        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(!empty($cart_items), true);

    }

    public function testPaymentMethodsGet() {
        $get = payment_options();
        $this->assertEquals(!empty($get), true);
        $this->assertEquals(isset($get[0]['id']), true);
        $this->assertEquals(isset($get[0]['gw_file']), true);
    }
}