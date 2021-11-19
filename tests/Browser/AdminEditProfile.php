<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminEditProfile extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testEditProfile()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->visit($siteUrl . 'admin/login')->assertSee('Login');

            $browser->waitFor('@login-button');

            // Login to admin panel
            $browser->type('username', '1');
            $browser->type('password', '1');

            $browser->click('@login-button');

            $browser->waitForText('Users');
            $browser->clickLink('Users');

            $browser->waitForText('Edit profile');
            $browser->clickLink('Edit profile');

            $browser->waitForText('Advanced settings');
            
            $browser->type('verify_password', '1');
            $browser->type('first_name', 'Visual');
            $browser->type('last_name', 'Test');
            $browser->type('phone', '08812345678');
            $browser->type('email', 'visualtest@microweber.com');

            $browser->press('Save');
        });
    }
}
