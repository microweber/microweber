<?php

namespace Tests\Browser\ShopTests;

use Laravel\Dusk\Browser;
use MicroweberPackages\Order\Models\Order;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;
use function app;
use function get_option;
use function save_option;
use function Tests\Browser\str_contains;

class CheckoutCartXssTest extends DuskTestCase
{

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {

            $uniqueId = time();

            $browser->visit($siteUrl);

            $this->_browserToShopAndAddTocart($browser);

            $this->_browserToCheckoutAndFillShippingInfo($browser, $uniqueId);


            $browser->pause(1000);

            $browser->script("$('html, body').animate({ scrollTop: $('[name=payment_gw]').first().offset().top - 160 }, 0);");
            $browser->pause(1000);

            $browser->radio('payment_gw', 'shop/payments/gateways/bank_transfer');

            $browser->pause(3000);
            $browser->script("$('html, body').animate({ scrollTop: $('.js-finish-your-order').first().offset().top - 60 }, 0);");
            $browser->pause(3000);

            try {
                $browser->click('.js-finish-your-order');
            } catch (\Facebook\WebDriver\Exception\WebDriverCurlException $e) {
                $browser->pause(10000);
            }

            $browser->pause(1000);


            $browser->waitForText('Your order is completed');
            $browser->assertSee('Your order is completed');

            $orderNumber = $browser->text('@order-number');

            $findOrder = Order::where('id', $orderNumber)->first();

            $this->assertNotEmpty($findOrder);

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
        $option['module'] = 'shop/payments';
        save_option($option);

        $payments = get_option('payment_gw_shop/payments/gateways/paypal', 'payments') == '1';
        $this->assertTrue($payments);

        $foundpaypal = false;
        $payment_options = app()->shop_manager->payment_options();
        foreach ($payment_options as $payment_option) {
            if ($payment_option['gw_file'] == 'shop/payments/gateways/paypal') {
                $foundpaypal = true;
            }
        }

        $this->assertTrue($foundpaypal);


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

            $browser->pause(4000);
            $browser->script("$('html, body').animate({ scrollTop: $('.js-finish-your-order').first().offset().top - 60 }, 0);");
            $browser->pause(3000);

            $browser->script("$('html, body').animate({ scrollTop: $('[name=payment_gw]').first().offset().top - 60 }, 0);");

            $browser->radio('payment_gw', 'shop/payments/gateways/paypal');
            $browser->pause(1000);

            try {
                $browser->script("$('html, body').animate({ scrollTop: $('.js-finish-your-order').first().offset().top - 10 }, 0);");

                $browser->click('.js-finish-your-order')->waitForText('Please wait', 15);
            } catch (\Facebook\WebDriver\Exception\WebDriverCurlException $e) {

                $this->markTestSkipped('Paypal is not available');
                return;
            }catch (\Facebook\WebDriver\Exception\TimeoutException $e) {

                $this->markTestSkipped('Paypal is not available with timeout');
                return;
            }

            $browser->pause(2000);

            $browser->assertMissing(".alert.alert-danger");

            $browser->pause(10000);

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

        $xss = '"><img src=x onerror=confirm(document.domain)>';

        $browser->waitForText('First Name');
        $browser->type('first_name', 'Bozhidar' . $uniqueId . $xss);
        $browser->type('last_name', 'Slaveykov' . $uniqueId. $xss);
        $browser->type('email', 'bobi' . $uniqueId . '@microweber.com');
        $browser->type('phone', $uniqueId. $xss);
        $browser->click('.js-checkout-continue');


        $browser->pause(2000);
        $browser->waitForText('Shipping method');

        $browser->radio('shipping_gw', 'shop/shipping/gateways/country');

        $browser->pause(7000);
        $browser->waitForText('Address for delivery');
        $browser->assertSee('Address for delivery');

        $browser->select('country', 'Bulgaria');
        $browser->type('Address[city]', 'Sofia' . $uniqueId. $xss);
        $browser->type('Address[zip]', '1000' . $uniqueId. $xss);
        $browser->type('Address[state]', 'Sofia' . $uniqueId. $xss);
        $browser->type('Address[address]', 'Vitosha 143' . $uniqueId. $xss);
        $browser->type('other_info', 'I want my order soon as posible.' . $uniqueId. $xss);

        $browser->scrollTo('.js-checkout-continue');

        $browser->pause(1000);

        $browser->click('.js-checkout-continue');

        $browser->waitForText('Payment method');
        $browser->assertSee('Payment method');
        $browser->pause(1000);

    }

}
