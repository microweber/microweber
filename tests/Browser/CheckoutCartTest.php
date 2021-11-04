<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\Order\Models\Order;
use Tests\DuskTestCase;

class CheckoutCartTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $uniqueId = time();

            $browser->visit($siteUrl . 'shop');
            $browser->pause('5000');

            $browser->script('$(".name-of-product-shop").first().click()');
            $browser->pause('4000');

            $browser->waitForText('Proceed to Checkout');
            $browser->assertSee('Proceed to Checkout');

            $browser->pause('4000');

            $browser->seeLink('Proceed to Checkout');
            $browser->clickLink('Proceed to Checkout');

            $browser->pause(1000);

            $browser->waitForLocation(  '/checkout/contact-information');

            $browser->type('first_name', 'Bozhidar' . $uniqueId);
            $browser->type('last_name', 'Slaveykov' . $uniqueId);
            $browser->type('email', 'bobi'.$uniqueId.'@microweber.com');
            $browser->type('phone', $uniqueId);
            $browser->click('@checkout-continue');

            $browser->waitForLocation('/checkout/shipping-method');
            $browser->waitForText('Address for delivery');
            $browser->assertSee('Address for delivery');
            $browser->select('country', 'Bulgaria');
            $browser->type('Address[city]', 'Sofia'.$uniqueId);
            $browser->type('Address[zip]', '1000'.$uniqueId);
            $browser->type('Address[state]', 'Sofia'.$uniqueId);
            $browser->type('Address[address]', 'Vitosha 143'.$uniqueId);
            $browser->type('other_info', 'I want my order soon as posible.'.$uniqueId);

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

            $orderNumber = $browser->text('@order-number');

            $findOrder = Order::where('id', $orderNumber)->first();

            $this->assertNotEmpty($findOrder);

            $this->assertEquals($findOrder->first_name, 'Bozhidar'.$uniqueId);
            $this->assertEquals($findOrder->last_name, 'Slaveykov'.$uniqueId);
            $this->assertEquals($findOrder->email, 'bobi'.$uniqueId.'@microweber.com');
            $this->assertEquals($findOrder->phone, $uniqueId);

            $this->assertEquals($findOrder->other_info, 'I want my order soon as posible.'.$uniqueId);

            $this->assertEquals($findOrder->country, 'Bulgaria');
            $this->assertEquals($findOrder->city, 'Sofia'.$uniqueId);
            $this->assertEquals($findOrder->state, 'Sofia'.$uniqueId);
            $this->assertEquals($findOrder->zip, '1000'.$uniqueId);
            $this->assertEquals($findOrder->address, 'Vitosha 143'.$uniqueId);
        });
    }
}
