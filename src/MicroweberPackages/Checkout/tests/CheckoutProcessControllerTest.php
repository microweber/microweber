<?php

namespace MicroweberPackages\Checkout\tests;

use MicroweberPackages\Core\tests\TestCase;

class CheckoutProcessControllerTest extends TestCase
{

    public function testProcess()
    {
        app()->database_manager->extended_save_set_permission(true);

        $params = array(
            'title' => 'My new product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Purple', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => '4444'),

            ),
            'is_active' => 1,);


        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);

        $this->assertEquals($saved_id, ($get['id']));
        self::$content_id = $saved_id;

        $add_to_cart = array(
            'content_id' => self::$content_id,
            // 'price' => 4444,
        );
        $cart_add = update_cart($add_to_cart);

        $this->assertEquals(isset($cart_add['success']), true);
        $this->assertEquals(isset($cart_add['product']), true);
        $this->assertEquals($cart_add['product']['price'], 4444);

        $response = $this->call('GET', route('checkout.cart', []));

        dd($response);


    }

}
