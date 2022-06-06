<?php

namespace Tests\Browser\SlowTests;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Assert as PHPUnit;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\TakeFullpageScreenshot;
use Tests\DuskTestCase;

class BrowseMarketplaceDemosTest extends DuskTestCase
{
    public function testTemplateDemosLinksAreOpenning()
    {


        $demos = [];
        $this->browse(function (Browser $browser) use (&$demos) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->waitForText('Marketplace');
            $browser->clickLink('Marketplace');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(3000);

            $browser->waitForText('Template');
            $browser->waitForText('Module');

            $linkElements = $browser->driver->findElements(WebDriverBy::cssSelector('#template a.js-package-demo-link'));
            foreach($linkElements as $element){
                $href = $element->getAttribute('href');

//                if(stripos($href,'landing') !== false){
//                    continue;
//                }
//
//                if(stripos($href,'flor') !== false){
//                     continue;
//                }

                $demos[]= $href;
            }

        });


        foreach ($demos as $demo) {
            $this->browse(function (Browser $browser) use ($demo) {
                $headers = get_headers($demo, 1);
                $status = intval(substr($headers[0], 9, 3));
                print $demo . ' ' . $status . PHP_EOL;
                PHPUnit::assertEquals(200, $status);
                $browser->visit($demo);

                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });

            });
        }
    }

}
