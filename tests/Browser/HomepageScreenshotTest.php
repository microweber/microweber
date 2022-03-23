<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomepageScreenshotTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
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
}
