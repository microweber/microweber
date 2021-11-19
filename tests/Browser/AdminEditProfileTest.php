<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminEditProfileTest extends DuskTestCase
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

            $browser->pause(3000);

            $browser->waitForText('Users');
            $browser->clickLink('Users');

            $browser->pause(2000);
            $browser->waitForText('Edit profile');
            $browser->clickLink('Edit profile');

            $browser->pause(3000);
            $browser->waitForText('First Name');
            $browser->pause(3000);
            $browser->scrollTo('#advanced-settings');
            
            $browser->type('first_name', 'Visual');
            $browser->type('last_name', 'Test');
            $browser->type('phone', '08812345678');
            $browser->type('email', 'visualtest@microweber.com');

            $browser->press('Save');
        });
    }
}
