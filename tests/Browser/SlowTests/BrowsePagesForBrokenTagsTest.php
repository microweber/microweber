<?php

namespace Tests\Browser\SlowTests;

use Laravel\Dusk\Browser;
use MicroweberPackages\App\Http\Controllers\SitemapController;
use PHPUnit\Framework\Assert as PHPUnit;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class BrowsePagesForBrokenTagsTest extends DuskTestCase
{
    public function testPages()
    {
        $this->browse(function (Browser $browser) {

            $sitemapController = app()->make(SitemapController::class);
            $visitLinks = $sitemapController->getSlugsWithGroups()['all'];

            foreach ($visitLinks as $link) {

                $browser->visit($link);
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
