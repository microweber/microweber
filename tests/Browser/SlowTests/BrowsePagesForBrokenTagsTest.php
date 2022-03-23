<?php

namespace Tests\Browser\SlowTests;

use Facebook\WebDriver\WebDriverBy;
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

            //Resize to full height for a complete screenshot
            $body = $browser->driver->findElement(WebDriverBy::tagName('body'));

            $currentSize = $body->getSize();

            //optional: scroll to bottom and back up, to trigger image lazy loading
            $browser->driver->executeScript('window.scrollTo(0, ' . $currentSize->getHeight() . ');');
            $browser->pause(1000); //wait a sec
            $browser->driver->executeScript('window.scrollTo(0, 0);'); //scroll back to top of the page

            //set window to full height
            $size = new WebDriverDimension(1920, $currentSize->getHeight()); //make browser full height for complete screenshot
            $browser->driver->manage()->window()->setSize($size);

            $browser->pause(600);
            $browser->screenshot('homepage-screenshot');


            // Take responsive
            $size = new WebDriverDimension(428, 926);
            $browser->driver->manage()->window()->setSize($size);

            $currentSize = $body->getSize();

            //optional: scroll to bottom and back up, to trigger image lazy loading
            $browser->driver->executeScript('window.scrollTo(0, ' . $currentSize->getHeight() . ');');
            $browser->pause(1000); //wait a sec
            $browser->driver->executeScript('window.scrollTo(0, 0);'); //scroll back to top of the page

            //set window to full height
            $size = new WebDriverDimension(428, $currentSize->getHeight()); //make browser full height for complete screenshot
            $browser->driver->manage()->window()->setSize($size);


            $browser->pause(600);
            $browser->screenshot('homepage-screenshot-responsive');

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
