<?php

namespace Tests\Browser\SlowTests;

use Laravel\Dusk\Browser;
use MicroweberPackages\App\Http\Controllers\SitemapController;
use PHPUnit\Framework\Assert as PHPUnit;
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

                $elements = $browser->elements('.module');

                foreach ($elements as $key => $elem) {
                    $output = $browser->script("return $('.module').eq(" . $key . ").hasClass('edit')");
                    PHPUnit::assertFalse($output[0]);
                }
            }

        });
    }
}
