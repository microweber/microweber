<?php

namespace Tests\Browser\Multilanguage\SlowTests;

use Laravel\Dusk\Browser;
use MicroweberPackages\App\Http\Controllers\SitemapController;
use Tests\Browser\Multilanguage\Components\AdminLogin;
use Tests\Browser\Multilanguage\Components\ChekForJavascriptErrors;
use Tests\Browser\Multilanguage\Components\TakeFullpageScreenshot;
use Tests\DuskTestCase;

class BrowsePagesForBrokenTagsTest extends DuskTestCase
{



    public function testHomepageScreenshot()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->visit($siteUrl);



            $browser->with(new TakeFullpageScreenshot(), function ($browser) {
                $browser->generateScreenshot('homepage-screenshot');
            });

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


        });
    }


    public function testPages()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            $sitemapController = app()->make(SitemapController::class);
            $visitLinks = $sitemapController->getSlugsWithGroups()['all'];

            foreach ($visitLinks as $link) {
                $browser->visit(site_url($link));
                $browser->pause(600);

                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });

                $browser->pause(1000);

              //  break;
            }

        });
    }
}
