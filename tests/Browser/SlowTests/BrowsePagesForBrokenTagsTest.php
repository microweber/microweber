<?php

namespace Tests\Browser\SlowTests;

use Facebook\WebDriver\WebDriverDimension;
use Laravel\Dusk\Browser;
use MicroweberPackages\App\Http\Controllers\SitemapController;
use PHPUnit\Framework\Assert as PHPUnit;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class BrowsePagesForBrokenTagsTest extends DuskTestCase
{
    public function testHomepageScreenshot()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->visit($siteUrl);
            $browser->pause(4000);

            $size = new WebDriverDimension(1920, 9000);
            $browser->driver->manage()->window()->setSize($size);

            $browser->screenshot('homepage-screenshot');

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

                $browser->pause(100);

              //  break;
            }

        });
    }
}
