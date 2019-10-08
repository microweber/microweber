<?php
namespace Microweber\tests;

/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter CheckoutTest
 */

class CheckoutTest extends TestCase
{
    public static $content_id = 1;

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


    public function testCheckout() {

        echo 1; 
        die();
    }
}
