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
        $siteUrl = 'http://127.0.0.1:8000/';

        $this->browse(function (Browser $browser) use ($siteUrl) {

            $browser->visit($siteUrl);
            $browser->pause(2000);
            $browser->assertSee('login');

        //    dump($browser->dump());

        });
    }
}
