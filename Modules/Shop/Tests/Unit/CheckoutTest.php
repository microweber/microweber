<?php

namespace Modules\Shop\Tests\Unit;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Cart\Models\Cart;
use Modules\Checkout\Repositories\CheckoutManager;
use Modules\MailTemplate\Models\MailTemplate;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;

/**
 * Run test
 * @author Bobi Microweber
 * @command php phpunit.phar --filter CheckoutTest
 */
class CheckoutTest extends TestCase
{
    use ShopTestHelperTrait;

    public function setUp(): void
    {
        parent::setUp();
        MailTemplate::where('type', 'new_order')->delete();
        Config::set('mail.driver', 'array');
        Config::set('queue.driver', 'sync');
        Config::set('mail.transport', 'array');
        Product::truncate();
        Order::truncate();
        Cart::truncate();

//create mail templste


        $mailTemplate = new MailTemplate();
        $mailTemplate->name = 'New Order';
        $mailTemplate->type = 'new_order';
        $mailTemplate->from_name = 'Microweber';
        $mailTemplate->from_email = 'test@example.com';


        $mailTemplate->subject = 'New Order';
        $mailTemplate->message = 'Order email test,
        Hello {first_name} {last_name} {email} {phone},
        Your order is placed.
        Your address is {address}, {city}, {state}, {country}, {zip}';
        $mailTemplate->is_active = 1;
        $mailTemplate->save();


        $data = [];
        $data['option_value'] = $mailTemplate->id;
        $data['option_key'] = 'new_order_mail_template';
        $data['option_group'] = 'orders';
        $save = save_option($data);


        $data = [];
        $data['option_value'] = 'y';
        $data['option_key'] = 'order_email_enabled';
        $data['option_group'] = 'orders';
        $save = save_option($data);


    }

    public function testCheckout()
    {


        empty_cart();

        Config::set('mail.transport', 'array');

        $this->_addProductToCart('Product 1');
        $this->_addProductToCart('Product 2');
        $this->_addProductToCart('Product 3');
        $this->_addProductToCart('Product 4');


        $checkoutDetails = array();
        $checkoutDetails['email'] = 'client@microweber.com';
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

        $checkEmailContent = '';
        $emails = app()->make('mailer')->getSymfonyTransport()->messages();
        foreach ($emails as $email) {

            $emailAsArray = $this->getEmailDataAsArrayFromObject($email);
            $body = $emailAsArray['body'];

            if (strpos($body, 'Order') !== false) {
                $checkEmailContent = $body;
            }
        }
        $findFirstName = false;
        if (strpos($checkEmailContent, $checkoutDetails['first_name']) !== false) {
            $findFirstName = true;
        }

        $findLastName = false;
        if (strpos($checkEmailContent, $checkoutDetails['last_name']) !== false) {
            $findLastName = true;
        }

        $findEmail = false;
        if (strpos($checkEmailContent, $checkoutDetails['email']) !== false) {
            $findEmail = true;
        }

        $findPhone = false;
        if (strpos($checkEmailContent, $checkoutDetails['phone']) !== false) {
            $findPhone = true;
        }

        $findCity = false;
        if (strpos($checkEmailContent, $checkoutDetails['city']) !== false) {
            $findCity = true;
        }

        $findZip = false;
        if (strpos($checkEmailContent, $checkoutDetails['zip']) !== false) {
            $findZip = true;
        }

        $findState = false;
        if (strpos($checkEmailContent, $checkoutDetails['state']) !== false) {
            $findState = true;
        }

        $findCountry = false;
        if (strpos($checkEmailContent, $checkoutDetails['country']) !== false) {
            $findCountry = true;
        }

        $findAddress = false;
        if (strpos($checkEmailContent, $checkoutDetails['address']) !== false) {
            $findAddress = true;
        }

        $this->assertEquals(true, $findFirstName);
        $this->assertEquals(true, $findLastName);
        $this->assertEquals(true, $findEmail);
        $this->assertEquals(true, $findPhone);
        $this->assertEquals(true, $findCity);
        $this->assertEquals(true, $findZip);
        $this->assertEquals(true, $findState);
        $this->assertEquals(true, $findCountry);
        $this->assertEquals(true, $findAddress);

    }

    public function testCheckoutQtyUpdate()
    {
        mw()->database_manager->extended_save_set_permission(true);

        $productPrice = rand(1, 9999);
        $title = 'test QTY prod ' . $productPrice;
        $params = array(
            'title' => $title,
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Purple', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => '9.99'),

            ),
            'data_qty' => 1,
            'is_active' => 1,);


        $saved_id = save_content($params);
        $content_data = content_data($saved_id);
        $this->assertEquals(1, $content_data['qty']);


        $get = get_content_by_id($saved_id);


        $add_to_cart = array(
            'content_id' => $saved_id,
            'price' => $productPrice,
        );
        $cart_add = update_cart($add_to_cart);


        $checkoutDetails = array();
        $checkoutDetails['email'] = 'client@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';
        $checkoutDetails['phone'] = '08812345678';
        $checkoutDetails['address'] = 'Business Park, Mladost 4';
        $checkoutDetails['city'] = 'Sofia';
        $checkoutDetails['state'] = 'Sofia City';
        $checkoutDetails['country'] = 'Bulgaria';
        $checkoutDetails['zip'] = '1000';
        $checkoutDetails['is_paid'] = 1;
        $checkoutDetails['order_completed'] = 1;

        $checkoutStatus = app()->checkout_manager->checkout($checkoutDetails);

        $content_data_after_order = content_data($saved_id);
        $this->assertEquals(0, $content_data_after_order['qty']);


        // test the productOrders relationship

        $productQuery = \Modules\Product\Models\Product::query();

        $productQuery = $productQuery->whereHas('orders');
        $products = $productQuery->get();

        $found = false;
        foreach ($products as $product) {
            if ($product->id == $saved_id) {
                $found = true;
            }
        }
        $this->assertTrue($found);

        $productQuery = \Modules\Product\Models\Product::query();

        $productQuery = $productQuery->whereDoesntHave('orders');
        $products = $productQuery->get();
        $found = false;
        foreach ($products as $product) {
            if ($product->id == $saved_id) {
                $found = true;
            }
        }
        $this->assertFalse($found);

    }


    public function testCheckoutDeletedProduct()
    {
        mw()->database_manager->extended_save_set_permission(true);

        $productPrice = rand(1, 9999);
        $title = 'test testCheckoutDeletedProduct prod ' . $productPrice;
        $params = array(
            'title' => $title,
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Purple', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => '9.99'),

            ),
            'data_fields_qty' => 10,
            'is_deleted' => 1,
            'is_active' => 1,);


        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);


        $add_to_cart = array(
            'content_id' => $saved_id,
            'price' => $productPrice,
        );
        $cart_add = update_cart($add_to_cart);


        $checkoutDetails = array();
        $checkoutDetails['email'] = 'client@microweber.com';
        $checkoutDetails['is_paid'] = 1;
        $checkoutDetails['order_completed'] = 1;


        $checkoutStatus = app()->checkout_manager->checkout($checkoutDetails);
        $content_data_after_order = content_data($saved_id);
        $this->assertEquals(10, $content_data_after_order['qty']);


        $order = get_order_by_id($checkoutStatus);
        $this->assertNotNull($order);
        $this->assertNull($order['amount']);

        $order = get_order_by_id($checkoutStatus);
        $this->assertNotNull($order);
        $this->assertNull($order['amount']);

    }


    public function testCheckoutUnpublishedProduct()
    {
        mw()->database_manager->extended_save_set_permission(true);

        $productPrice = rand(1, 9999);
        $title = 'test testCheckoutUnpublishedProduct prod ' . $productPrice;
        $params = array(
            'title' => $title,
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type' => 'dropdown', 'name' => 'Color', 'value' => array('Purple', 'Blue')),
                array('type' => 'price', 'name' => 'Price', 'value' => '9.99'),

            ),
            'data_fields_qty' => 11,
            'is_deleted' => 0,
            'is_active' => 0
        );


        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);


        $add_to_cart = array(
            'content_id' => $saved_id,
            'price' => $productPrice,
        );
        $cart_add = update_cart($add_to_cart);


        $checkoutDetails = array();
        $checkoutDetails['email'] = 'client@microweber.com';
        $checkoutDetails['is_paid'] = 1;
        $checkoutDetails['order_completed'] = 1;


        $checkoutStatus = app()->checkout_manager->checkout($checkoutDetails);
        $content_data_after_order = content_data($saved_id);
        $this->assertEquals(11, $content_data_after_order['qty']);


        $order = get_order_by_id($checkoutStatus);
        $this->assertNotNull($order);
        $this->assertNull($order['amount']);

    }


}
