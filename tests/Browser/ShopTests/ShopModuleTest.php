<?php
namespace Tests\Browser\ShopTests;

use Laravel\Dusk\Browser;
use MicroweberPackages\Content\tests\TestHelpers;
use MicroweberPackages\Product\Models\Product;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;
use function app;
use function content_link;
use function get_option;
use function save_content;
use function save_option;

class ShopModuleTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    use TestHelpers;

    public function testProductsModuleIsDidplayingLatestsPosts()
    {
        $siteUrl = $this->siteUrl;

        $productsAll = Product::all();
        foreach ($productsAll as $product) {
            $product->delete();
        }
        clearcache();

        $pageId = $this->_generatePage('my-page-for-products-module-test', 'My page for products module test');
        $products = [];
        $products[] = $this->_generateProduct('my-first-product', 'My first product', $pageId,[]);
        $products[] = $this->_generateProduct('my-second-product', 'My second product', $pageId,[]);
        $products[] = $this->_generateProduct('my-third-product', 'My third product', $pageId,[]);


        $moduleIdRand = 'testproductsmodule' . time() . uniqid();

        $title = 'My page for products module test ' . time();
        $params = array(
            'id' => $pageId,
            'title' => $title,
            'content_type' => 'page',
            'subtype' => 'static',
            'content' => '<div class="container">
<h1>Products for test</h1>
<module type="shop" id="'.$moduleIdRand.'" /></div>',

            'is_active' => 1
        );


        $saved_id = save_content($params);

        $siteUrl = content_link($saved_id);

        $this->browse(function (Browser $browser) use ($siteUrl, $products, $moduleIdRand) {
            $browser->visit($siteUrl)
                ->waitForText('My first product')
                ->assertSee('My first product')
                ->assertSee('My second product')
                ->assertSee('My third product')
                ->assertVisible('#' . $moduleIdRand);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

        });


        $date_formats = app()->format->get_supported_date_formats();

        foreach ($date_formats as $date_format) {
            $setDateFormat = save_option('date_format', $date_format, 'website');
            $getDateFormat = get_option('date_format', 'website');
            $this->assertEquals($date_format, $getDateFormat);


            $this->browse(function (Browser $browser) use ($siteUrl, $products, $moduleIdRand, $title) {

                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });

                $browser->visit($siteUrl)
                    ->assertSee('My first product')
                    ->assertSee('My second product')
                    ->assertSee('My third product')
                    ->assertVisible('#' . $moduleIdRand);
            });
        }


    }
}
