<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
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

        $this->browse(function (Browser $browser) use($siteUrl) {
            $browser->visit($siteUrl)
                    ->assertSee('install');
        });
    }
}
