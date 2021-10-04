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
        $websiteUrl = site_url();

        $this->browse(function (Browser $browser) use($websiteUrl) {
            $browser->visit($websiteUrl)->dump();
        });
    }
}
