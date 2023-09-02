<?php

namespace Tests\Browser\Example;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {
            $browser->visit($siteUrl)->waitForText('Microweber');
        });
    }
}
