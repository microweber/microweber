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

class CheckoutCartTest extends ShopDuskTestCase
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
            $browser->script("$('html, body').animate({ scrollTop: $('[value=\"shop/payments/gateways/bank_transfer\"]').first().offset().top - 60 }, 0);");
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
        $this->markTestSkipped('Paypal test is not available at the moment');
        $siteUrl = $this->siteUrl;


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


            $this->markTestSkipped('Paypal test is not available at the moment');

            try {
                $browser->script("$('html, body').animate({ scrollTop: $('.js-finish-your-order').first().offset().top - 10 }, 0);");

                $browser->click('.js-finish-your-order')->waitForText('Please wait', 15);
            } catch (\Facebook\WebDriver\Exception\WebDriverCurlException $e) {

                $this->markTestSkipped('Paypal is not available');
                return;
            } catch (\Facebook\WebDriver\Exception\TimeoutException $e) {

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



}
