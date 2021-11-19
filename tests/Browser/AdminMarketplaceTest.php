<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminMarketplaceTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testModuleInstall()
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

            $browser->waitForText('Marketplace');
            $browser->clickLink('Marketplace');

            $browser->pause(3000);

            $browser->waitForText('Template');
            $browser->waitForText('Module');

            $browser->click('#js-packages-tab-module');
            $browser->pause(3000);

            $browser->waitForText('Module');

            $browser->waitForText('Browser Redirect');
            $browser->click('#js-install-package-browser_redirect');
            $browser->pause(3000);

            $browser->waitForText('Please confirm the installation');
            $browser->waitForText('browser_redirect');
            $browser->waitForText('files will be installed');

            
            $browser->waitForText('Confirm');
            $browser->press('Confirm');
            $browser->pause(3000);

            $browser->waitForText('Success. You have installed');
            $browser->press('OK');



        });
    }
}
