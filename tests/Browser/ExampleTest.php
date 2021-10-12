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

            $browser->visit($siteUrl)->assertSee('install');

            // Fill the install fields
            $browser->type('admin_username', '1');
            $browser->type('admin_password', '1');
            $browser->type('admin_password2', '1');
            $browser->type('admin_email', '1');

            $browser->pause(100);
            $browser->click('@install-button');

            // Wait for redirect after installation
            $browser->waitForLocation('/admin/login', 120);

            // Login to admin panel
            $browser->type('username', '1');
            $browser->type('password', '1');

          //  $browser->dump();

        });
    }
}
