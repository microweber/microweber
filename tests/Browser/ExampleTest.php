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
        $adminUrl = '/admin';

        $this->browse(function (Browser $browser) use($adminUrl) {
            $browser->visit($adminUrl);
            $browser->script(['console.log(22)']);
            $browser->pause(10000);
        });
    }
}
