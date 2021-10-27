<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminShopOffersTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testAddAddOffer()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->visit($siteUrl . 'admin/login')->assertSee('Login');

            $browser->waitFor('@login-button');

            // Login to admin panel
            $browser->type('username', '1');
            $browser->type('password', '1');

            $browser->click('@login-button');

            // Wait for redirect after login
            $browser->waitForLocation('/admin/', 120);

            $browser->pause(100);

            $browser->visit($siteUrl.'admin/view:shop/action:options#option_group=shop/settings');
            $browser->pause(1000);
            $browser->clickLink('Promotions');
            $browser->waitForText('Offers');
            $browser->assertSee('Add new offer');

            $browser->pause(1000);
            $browser->click('.js-add-new-offer');

            $browser->waitForText('Add new offer');
            $browser->waitForText('Offer status');

            $browser->assertSee('Add new offer');
            $browser->assertSee('Offer status');
            $browser->assertSee('Save');
            $browser->assertSee('Offer price');
            $browser->assertSee('Offer start at');
            $browser->assertSee('Offer expiry at');


        });

    }
}
