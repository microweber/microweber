<?php
namespace MicroweberPackages\Shop\tests;

use MicroweberPackages\Core\tests\TestCase;

class CartTest extends TestCase
{
    public static $content_id = 1;

    public function testAddToCart()
    {
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);

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
        $get = get_content_by_id($saved_id);

        $this->assertEquals($saved_id, ($get['id']));
        self::$content_id = $saved_id;

        $add_to_cart = array(
           'content_id' => self::$content_id,
           'color' => 'Purple',
           'non_existing' => 'must_not_be_added'
           // 'price' => 30,
        );
        $cart_add = update_cart($add_to_cart);
        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(isset($cart_add['product']), true);
        $this->assertEquals($cart_add['product']['price'], 30);
        $this->assertEquals($cart_add['product']['custom_fields_data']['color'], 'Purple');
        $this->assertEquals(isset($cart_add['product']['custom_fields_data']['non_existing']), false);

        $cart_items = get_cart();
        $this->assertEquals($cart_items[0]['qty'], 1);

        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();
        $this->assertEquals($cart_items[0]['qty'], 2);
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
            'price' => 1300, // wrong price on purpose
        );
        $cart_add = update_cart($add_to_cart);
        $cart_items = get_cart();

        $sum = cart_sum();
        $this->assertEquals($sum, 90);

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(!empty($cart_items), true);
    }

    public function testPaymentMethodsGet()
    {
        $get = payment_options();

        if (is_module('shop')) {
            $this->assertEquals(!empty($get), true);
            $this->assertEquals(isset($get[0]['id']), true);
            $this->assertEquals(isset($get[0]['gw_file']), true);
        }
    }
}
