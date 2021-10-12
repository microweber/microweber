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
        define('MW_SITE_URL', 'http://localhost/microweber');

        $siteUrl = site_url();

        $this->browse(function (Browser $browser) use($siteUrl) {
            $browser->visit($siteUrl);
         //   dump($browser->dump());
            $browser->assertSee('Installation is completed');
        });
    }
}
