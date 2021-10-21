<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CheckoutCartTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;


        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->visit($siteUrl . 'shop');
            $browser->pause('2000');
            //$browser->scrollTo('.name-of-product-shop');
           // $browser->pause('2000');
            $browser->script('$(".name-of-product-shop").first().click()');

            $browser->waitForText('Proceed to Checkout');
            $browser->assertSee('Proceed to Checkout');
            $browser->pause('1000');
            $browser->seeLink('Proceed to Checkout');
            $browser->clickLink('Proceed to Checkout');
            $browser->pause(1000);

            $browser->waitForLocation(  '/checkout/contact-information');

            $browser->type('first_name', 'Bozhidar');
            $browser->type('last_name', 'Slaveykov');
            $browser->type('email', 'bobi@microweber.com');
            $browser->type('phone', '088123456');
            $browser->click('@checkout-continue');

            $browser->waitForLocation('/checkout/shipping-method');
            $browser->waitForText('Address for delivery');
            $browser->assertSee('Address for delivery');
            $browser->select('country', 'Bulgaria');
            $browser->type('Address[city]', 'Sofia');
            $browser->type('Address[zip]', '1000');
            $browser->type('Address[state]', 'Sofia');
            $browser->type('Address[address]', 'Vitosha 143');
            $browser->type('other_info', 'I want my order soon as posible.');

            $browser->scrollTo('@checkout-continue');
            $browser->pause(1000);

            $browser->click('@checkout-continue');

            $browser->waitForText('Payment method');
            $browser->assertSee('Payment method');
            $browser->pause(1000);

            $browser->radio('payment_gw', 'shop/payments/gateways/bank_transfer');
            $browser->pause(1000);

            $browser->click('@checkout-continue');
            $browser->pause(1000);

            $browser->waitForText('Your order is completed');
            $browser->assertSee('Your order is completed');

        });
    }
}
