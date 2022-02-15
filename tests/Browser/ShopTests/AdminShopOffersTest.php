<?php

namespace Tests\Browser\ShopTests;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminShopOffersTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testAddAddOffer()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });



            $browser->pause(100);

            $browser->visit($siteUrl.'admin/view:shop/action:options#option_group=shop/settings');
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(1000);
            $browser->clickLink('Promotions');
            $browser->waitForText('Offers');

            $browser->assertSee('Add new offer');
            $browser->pause(1000);
            $browser->click('.js-add-new-offer');

            $browser->waitForText('Add new offer', 30);
            $browser->waitForText('Offer status');
            $browser->waitForText('Save',30);

            $browser->assertSee('Add new offer');
            $browser->assertSee('Offer status');
            $browser->assertSee('Save');
            $browser->assertSee('Offer price');
            $browser->assertSee('Offer start at');
            $browser->assertSee('Offer expiry at');


        });

    }
}
