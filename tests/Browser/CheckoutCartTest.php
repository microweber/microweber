<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\Order\Models\Order;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class CheckoutCartTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {

            $uniqueId = time();

            $browser->visit($siteUrl);

            $this->_browserToShopAndAddTocart($browser);

            $this->_browserToCheckoutAndFillShippingInfo($browser, $uniqueId);


            $browser->pause(1000);

            $browser->radio('payment_gw', 'shop/payments/gateways/bank_transfer');
            $browser->pause(1000);

            $browser->click('@checkout-continue');
            $browser->pause(1000);


            $browser->waitForText('Your order is completed');
            $browser->assertSee('Your order is completed');

            $orderNumber = $browser->text('@order-number');

            $findOrder = Order::where('id', $orderNumber)->first();

            $this->assertNotEmpty($findOrder);

            $this->assertEquals($findOrder->first_name, 'Bozhidar' . $uniqueId);
            $this->assertEquals($findOrder->last_name, 'Slaveykov' . $uniqueId);
            $this->assertEquals($findOrder->email, 'bobi' . $uniqueId . '@microweber.com');
            $this->assertEquals($findOrder->phone, $uniqueId);

            $this->assertEquals($findOrder->other_info, 'I want my order soon as posible.' . $uniqueId);

            $this->assertEquals($findOrder->country, 'Bulgaria');
            $this->assertEquals($findOrder->city, 'Sofia' . $uniqueId);
            $this->assertEquals($findOrder->state, 'Sofia' . $uniqueId);
            $this->assertEquals($findOrder->zip, '1000' . $uniqueId);
            $this->assertEquals($findOrder->address, 'Vitosha 143' . $uniqueId);
        });
    }

    public function testCheckoutWithPaypal()
    {
        $siteUrl = $this->siteUrl;

        // enable paypal
        $option = array();
        $option['option_value'] = 1;
        $option['option_key'] = 'payment_gw_shop/payments/gateways/paypal';
        $option['option_group'] = 'payments';
        save_option($option);

        $option = array();
        $option['option_value'] = 'y';
        $option['option_key'] = 'paypalexpress_testmode';
        $option['option_group'] = 'payments';
        save_option($option);


        $option = array();
        $option['option_value'] = 'info@microweber.com';
        $option['option_key'] = 'paypalexpress_username';
        $option['option_group'] = 'payments';
        save_option($option);


        $this->browse(function (Browser $browser) use ($siteUrl) {


            $uniqueId = time();

            $browser->visit($siteUrl);

            $this->_browserToShopAndAddTocart($browser);

            $this->_browserToCheckoutAndFillShippingInfo($browser, $uniqueId);


            $browser->radio('payment_gw', 'shop/payments/gateways/paypal');
            $browser->pause(1000);

            $browser->click('@checkout-continue');
            $browser->pause(200);

            $browser->assertMissing(".alert.alert-danger");

            $browser->pause(1000);

            $url = $browser->driver->getCurrentURL();

            $this->assertEquals(true, str_contains($url, 'sandbox.paypal.com'));

            $findOrder = Order::orderBy('id', 'desc')->first();
            $this->assertEquals($findOrder->payment_gw, 'shop/payments/gateways/paypal');
            $this->assertEquals($findOrder->order_completed, 1);
            $this->assertNull($findOrder->is_paid);
            $this->assertNotNull($findOrder->customer_id);
            $this->assertNotNull($findOrder->session_id);

        });


    }


    private function _browserToShopAndAddTocart($browser)
    {

        $browser->waitForText('Shop');
        $browser->clickLink('Shop');

        $browser->waitForText('Shop');

        $browser->within(new ChekForJavascriptErrors(), function ($browser) {
            $browser->validate();
        });

        $browser->script('$(".name-of-product-shop").first().click()');
        $browser->pause(5000);

        $browser->waitForText('Proceed to Checkout');
        $browser->assertSee('Proceed to Checkout');

        $browser->seeLink('Proceed to Checkout');
        $browser->clickLink('Proceed to Checkout');
        $browser->pause(3000);

    }


    private function _browserToCheckoutAndFillShippingInfo($browser, $uniqueId)
    {

        $browser->waitForText('First Name');
        $browser->type('first_name', 'Bozhidar' . $uniqueId);
        $browser->type('last_name', 'Slaveykov' . $uniqueId);
        $browser->type('email', 'bobi' . $uniqueId . '@microweber.com');
        $browser->type('phone', $uniqueId);
        $browser->click('@checkout-continue');


        $browser->pause(2000);
        $browser->waitForText('Shipping method');

        $browser->radio('shipping_gw', 'shop/shipping/gateways/country');

        $browser->pause(7000);
        $browser->waitForText('Address for delivery');
        $browser->assertSee('Address for delivery');

        $browser->select('country', 'Bulgaria');
        $browser->type('Address[city]', 'Sofia' . $uniqueId);
        $browser->type('Address[zip]', '1000' . $uniqueId);
        $browser->type('Address[state]', 'Sofia' . $uniqueId);
        $browser->type('Address[address]', 'Vitosha 143' . $uniqueId);
        $browser->type('other_info', 'I want my order soon as posible.' . $uniqueId);

        $browser->scrollTo('@checkout-continue');
        $browser->pause(1000);


        $browser->click('@checkout-continue');

        $browser->waitForText('Payment method');
        $browser->assertSee('Payment method');
        $browser->pause(1000);

    }

}
