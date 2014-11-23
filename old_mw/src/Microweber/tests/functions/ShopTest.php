<?php

namespace FunctionsTest;


class ShopTest extends \PHPUnit_Framework_TestCase
{
    function __construct()
    {
        // session_provider for unit tests,
        // does not persist values between requests
        mw('user')->session_provider = 'array';

        // empty existing cart for the test
        empty_cart();
    }

    public function testAddProducts()
    {
        $params = array(
            'title' => 'this-is-my-test-shop',
            'content_type' => 'page',
            'is_shop' => 'y',
            // 'debug' => 1,
            'is_active' => 'y');

        //creating our shop page
        $my_shop = save_content($params);


        $params = array(
            'title' => 'this-is-my-test-product',
            'content_type' => 'post',
            'subtype' => 'product',
            'parent' => $my_shop,
            // 'debug' => 1,
            'is_active' => 'y');
        //adding a product to our shop page
        $my_product = save_content($params);
        $product = get_content_by_id($my_product);

        $price = rand();

        $add_price_field = array(
            'field_name' => 'My price',
            'field_value' => $price,
            'field_type' => 'price',
            // 'debug' => 1,
            'content_id' => $my_product);
        //adding a custom field "price" to product
        $field = save_custom_field($add_price_field);
        $this->assertEquals(true, intval($field) > 0);


        $custom_fields = get_custom_fields('content', $my_product);

        //PHPUnit
        $this->assertEquals(true, is_array($product));
        $this->assertEquals(true, is_array($custom_fields));
        $this->assertEquals(true, isset($custom_fields['my price']));
        $this->assertEquals(true, intval($custom_fields['my price']) == $price);
        $this->assertEquals(true, intval($my_product) > 0);
        $this->assertEquals(true, intval($field) > 0);
        $this->assertEquals(true, intval($my_shop) > 0);

        $this->delete_content[] = $my_shop;
        $this->delete_content[] = $my_product;
    }

    public function testAddToCart()
    {

        $params = array(
            'title' => 'this-is-my-other-test-shop',
            'content_type' => 'page',
            'is_shop' => 'y',
            // 'debug' => 1,
            'is_active' => 'y');

        //creating our shop page
        $my_shop = save_content($params);


        $params = array(
            'title' => 'this-is-my-other-test-product',
            'content_type' => 'post',
            'subtype' => 'product',
            'parent' => $my_shop,
            // 'debug' => 1,
            'is_active' => 'y');
        //adding a product to our shop page
        $my_product = save_content($params);

        $product = get_content_by_id($my_product);
        $add_price_field = array(
            'field_name' => 'My price',
            'field_value' => '10',
            'field_type' => 'price',
            // 'debug' => 1,
            'content_id' => $my_product);
        //adding a custom field "price" to product
        $field = save_custom_field($add_price_field);

        $custom_fields = get_custom_fields('content', $my_product);


        $add_to_cart = array(
            'content_id' => $my_product
        );
        $cart_add = update_cart($add_to_cart);

        $cart = get_cart();

        empty_cart();
        $cart_emptied = get_cart();


        //PHPUnit
        $this->assertEquals(true, is_array($product));
        $this->assertEquals(true, is_array($custom_fields));
        $this->assertEquals(true, isset($custom_fields['my price']));
        $this->assertEquals(true, intval($custom_fields['my price']) == 10);
        $this->assertEquals(true, intval($my_product) > 0);
        $this->assertEquals(true, intval($field) > 0);
        $this->assertEquals(true, intval($my_shop) > 0);

        $this->assertEquals(true, intval($cart_add) > 0);
        $this->assertEquals(true, !empty($cart));
        $this->assertEquals(true, is_array($cart));
        $this->assertEquals(true, !is_array($cart_emptied));

        // delete test content on exit
        $this->delete_content[] = $my_shop;
        $this->delete_content[] = $my_product;


    }

    public function testCheckout()
    {

        $params = array(
            'title' => 'this-is-another-test-product',
            'content_type' => 'post',
            'subtype' => 'product',

            // 'debug' => 1,
            'is_active' => 'y');
        //adding a product to our shop page
        $my_product = save_content($params);


        //get products
        $products = get_content($params);

        foreach ($products as $product) {
            $add_to_cart = array(
                'content_id' => $product['id'],
                'price' => 35
            );

            $cart_add = update_cart($add_to_cart);

            $this->assertEquals('this-is-another-test-product', $product['title']);
         }
        // cart must be array with items
        $cart = get_cart();


        $checkout_params = array(
            'first_name' => 'John',
            'last_name' => 'The Tester',
            // 'debug' => 1,
            'email' => 'email@example.com');

        // completing the checkout process
        $checkout = checkout($checkout_params);

        // get the new order
        $order = get_order_by_id($checkout['id']);


        //PHPUnit
        $this->assertEquals(true, is_array($products));
        $this->assertEquals(true, is_array($cart));
        $this->assertEquals(true, !isset($checkout['error']));
        $this->assertEquals(true, isset($checkout['success']));
        $this->assertEquals(true, intval($checkout['id']) > 0);
        $this->assertEquals(true, intval($order['id']) > 0);
        $this->delete_content[] = $my_product;

    }

    public function testGetOrders()
    {
        $orders_params = array(
            'first_name' => 'John',
            'last_name' => 'The Tester',
            'order_completed' => 'y',
            // 'debug' => 1,
            'email' => 'email@example.com');


        // Get completed orders
        $orders = get_orders($orders_params);


        //Modify and save order
        foreach ($orders as $order) {

            if ($order['order_status'] != 'completed') {
                $modify_order_params = array(
                    'id' => $order['id'],
                    //  'debug' => 1,
                    'order_status' => 'completed'
                );


                $order_id = update_order($modify_order_params);
                $check_status = get_order_by_id($order['id']);

                //PHPUnit
                $this->assertEquals($check_status['order_status'], 'completed');
                $this->assertEquals($order_id, $order['id']);

            }


        }

        //PHPUnit
        $this->assertEquals(true, is_array($orders));


    }

    public function testDeleteOrders()
    {
        $orders_params = array(
            'first_name' => 'John',
            'last_name' => 'The Tester',
            'order_completed' => 'y',
            // 'debug' => 1,
            'email' => 'email@example.com');


        // Get completed orders
        $orders = get_orders($orders_params);
        foreach ($orders as $order) {

            //delete order
            $delete_order = delete_order($order['id']);
            $check_deleted = get_order_by_id($order['id']);


            //PHPUnit
            $this->assertEquals(true, intval($delete_order) > 0);
            $this->assertEquals(true, !is_array($check_deleted));

        }


        //PHPUnit
        $this->assertEquals(true, is_array($orders));
    }

    protected function setUp()
    {

    }

    protected function tearDown()
    {
        if (isset($this->delete_content) and is_array($this->delete_content)) {
            foreach ($this->delete_content as $item) {
                $delete_content = delete_content($item);
                $check_deleted = get_content_by_id($item);

                //PHPUnit
                $this->assertEquals(true, is_array($delete_content));
                $this->assertEquals(true, !is_array($check_deleted));
            }
        }
    }


}