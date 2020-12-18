<?php

namespace Microweber\tests;

class CartTest extends TestCase
{
    public static $content_id = 1;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
    }

    public function testAddToCart()
    {
        $params = array(
            'title' => 'My new product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields' => array(
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
            'price' => 35,
        );
        $cart_add = update_cart($add_to_cart);

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(isset($cart_add['product']), true);
        $this->assertEquals($cart_add['product']['price'], 35);
    }

    public function testGetCart()
    {
        empty_cart();
        $add_to_cart = array(
            'content_id' => self::$content_id,
            'qty' => 2,
            'price' => 350,
        );
        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();
 
        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(!empty($cart_items), true);
    }

    public function testSumCart()
    {
        empty_cart();
        $add_to_cart = array(
            'content_id' => self::$content_id,
            'qty' => 3,
            'price' => 300,
        );
        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();

        $sum = cart_sum();
        $this->assertEquals($sum, 900);

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(!empty($cart_items), true);
    }

    public function testPaymentMethodsGet()
    {
        $get = payment_options();
        $this->assertEquals(!empty($get), true);
        $this->assertEquals(isset($get[0]['id']), true);
        $this->assertEquals(isset($get[0]['gw_file']), true);
    }
}
