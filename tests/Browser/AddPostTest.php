<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AddPostTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testAddPost()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {
            $browser->visit($siteUrl . 'admin/login')->assertSee('Login');

            // Login to admin panel
            $browser->type('username', '1');
            $browser->type('password', '1');

            $browser->click('@login-button');

            // Wait for redirect after login
            $browser->waitForLocation('/admin/', 120);

            $browser->pause(100);

            $browser->visit($siteUrl.'admin/view:content#action=new:post?dusk=1');

            $browser->pause(3000);
            $browser->value('#slug-field-holder input', 'This is the post title');

            $browser->pause(1000);
            $browser->click('#js-admin-save-content-main-btn');


            $browser->pause(1000);
            $browser->assertSee('Content saved');


        });

    }
}
