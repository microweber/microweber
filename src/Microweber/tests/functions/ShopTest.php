<?php

namespace FunctionsTest;


class ShopTest extends \PHPUnit_Framework_TestCase
{
    function __construct()
    {
        // session_provider for unit tests,
        // does not persist values between requests
        mw('user')->session_provider = 'array';
    }

    public function testAddToCart()
    {

        $params = array(
            'title' => 'this-is-my-test-shop',
            'content_type' => 'page',
            'is_shop' => 'y',
            // 'debug' => 1,
            'is_active' => 'y');

        //saving
        $my_shop = save_content($params);



        $params = array(
            'title' => 'this-is-my-test-product',
            'content_type' => 'post',
            'subtype' => 'product',
            'parent' => $my_shop,
            // 'debug' => 1,
            'is_active' => 'y');

        //saving
        $my_product = save_content($params);


        $product = get_content_by_id($my_product);
        $add_price_field = array(
            'field_name' => 'My price',
            'field_value' => '10',
            'field_type' => 'price',
            // 'debug' => 1,
            'content_id' => $my_product);
        $field = save_custom_field($add_price_field);

        $add_price_field_2 = array(
            'field_name' => 'My other price',
            'field_value' => '20',
            'field_type' => 'price',
            // 'debug' => 1,
            'content_id' => $my_product);
        $field_2 = save_custom_field($add_price_field_2);

        $check_field = get_custom_fields('content', $my_product);


        $add_to_cart = array(
            'My price' => '20',
            // 'debug' => 1,
            'content_id' => $my_product);

        $cart_add = update_cart($add_to_cart);

        $cart = get_cart();
        empty_cart();
        $cart_emptied = get_cart();



        $delete_shop = delete_content($my_shop);
        $delete_product = delete_content($my_product);
        $check_deleted_shop = get_content_by_id($my_shop);
        $check_deleted_product = get_content_by_id($my_product);



        //PHPUnit
        $this->assertEquals(true, is_array($product));
        $this->assertEquals(true, is_array($check_field));
        $this->assertEquals(true, isset($check_field['My price']));
        $this->assertEquals(true, intval($check_field['My price']) == 10);
        $this->assertEquals(true, intval($my_product) > 0);
        $this->assertEquals(true, intval($field) > 0);
        $this->assertEquals(true, intval($my_shop) > 0);

        $this->assertEquals(true, intval($cart_add) > 0);
        $this->assertEquals(true, !empty($cart));
        $this->assertEquals(true, is_array($cart));
        $this->assertEquals(true, !is_array($cart_emptied));
        // check deleted
        $this->assertEquals(true, is_array($delete_shop));
        $this->assertEquals(true, is_array($delete_product));
        $this->assertEquals(true, !is_array($check_deleted_shop));
        $this->assertEquals(true, !is_array($check_deleted_product));

    }


}