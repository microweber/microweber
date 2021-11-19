<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminMarketplaceTest extends DuskTestCase
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

            $browser->waitForText('Marketplace');
            $browser->clickLink('Marketplace');

            $browser->waitForText('Edit profile');
            $browser->clickLink('Edit profile');

            $browser->waitForText('Template');
            $browser->waitForText('Module');

            $browser->press('Module');
            $browser->waitForText('Module');

            $browser->waitForText('Browser Redirect');
            $browser->click('#js-install-package-browser_redirect');
            $browser->waitForText('Please confirm the installation');
            $browser->waitForText('browser_redirect');
            $browser->waitForText('files will be installed');
            $browser->waitForText('Confirm');
            $browser->press('Confirm');
            $browser->waitForText('Success. You have installed');
            $browser->press('OK');



        });
    }
}
