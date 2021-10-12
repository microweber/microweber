<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        define('MW_SITE_URL', 'http://127.0.0.1:8000/');

        $siteUrl = site_url();

        $this->browse(function (Browser $browser) use($siteUrl) {
            $browser->visit($siteUrl);
            $browser->pause(2000);

            dump($browser->dump());
        });
    }
}
