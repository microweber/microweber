<?php

namespace Tests\Browser\ShopTests;

use Laravel\Dusk\Browser;
use MicroweberPackages\Product\Models\Product;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminShopOffersTest extends DuskTestCase
{

    public function testAddAddOffer()
    {
        $siteUrl = site_url();

        $this->browse(function (Browser $browser) use ($siteUrl) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });
            $title = 'offer-' . uniqid();
            $newProduct = new Product();
            $newProduct->title = $title;
            $newProduct->setCustomField(
                [
                    'type' => 'price',
                    'name' => 'price',
                    'value' => '10000',
                ]
            );
            $newProduct->save();


            $browser->pause(100);

            $browser->visit($siteUrl . 'admin/settings?group=shop/offers/admin_block');
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $offerRand = rand(10, 20);
            $browser->assertSee('Add new offer');
            $browser->pause(1000);
            $browser->click('.js-add-new-offer');
            $browser->pause(2000);

            $browser->waitForText('Add new offer', 30);
            $browser->waitForText('Offer status');
            $browser->waitForText('Save', 30);

            $browser->assertSee('Add new offer');
            $browser->assertSee('Offer status');
            $browser->assertSee('Save');
            $browser->assertSee('Offer price');
            $browser->assertSee('Offer start at');
            $browser->assertSee('Offer expiration date');

            $browser->click('#mw-admin-search-for-products');

            $browser->typeSlowly('#mw-admin-search-for-products input.mw-ui-invisible-field', $title);
            $browser->pause(1000);

            $browser->typeSlowly('offer_price', $offerRand);
            $browser->click('.mw-autocomplete-list > li:first-child');
            $browser->pause(1300);
            $browser->click('.js-save-offer');
            $browser->pause(3000);

            $browser->visit($siteUrl . 'admin/settings?group=shop/offers/admin_block');
            $browser->waitForText('Offers');
            $browser->pause(1000);
            $browser->assertSee($title);


        });

    }
}
