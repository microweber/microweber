<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AbppInstallTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testInstallation()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            if (mw_is_installed()) {
                $this->assertTrue(true);
                return true;
            }

           /* $deleteDbFiles = [];
            $deleteDbFiles[] = dirname(dirname(__DIR__)) . DS . 'config/microweber.php';
            $deleteDbFiles[] = dirname(dirname(__DIR__)) . DS . 'storage/127_0_0_1.sqlite';
            foreach ($deleteDbFiles as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }*/

            $browser->visit($siteUrl)->assertSee('install');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            // Fill the install fields
            $browser->type('admin_username', '1');
            $browser->type('admin_password', '1');
            $browser->type('admin_password2', '1');
            $browser->type('admin_email', 'bobi@microweber.com');

            $browser->pause(300);
            $browser->select('#default_template', 'new-world');

            $browser->pause(100);
            $browser->click('@install-button');

            $browser->pause(20000);

        });
    }

    public function testViewDashboard()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {


            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });


            $browser->visit($siteUrl . 'admin');
            $browser->pause(5000);

            // Wait for redirect after login
            $browser->waitForText('Dashboard', 30);

            $browser->waitForText('Statistics', 30);
            $browser->waitForText('Live Edit', 30);
            $browser->waitForText('Website', 30);
            $browser->waitForText('Shop', 30);
            $browser->waitForText('Modules', 30);
            $browser->waitForText('Marketplace', 30);
            $browser->waitForText('Settings', 30);
            $browser->waitForText('Users', 30);
            $browser->waitForText('Log out', 30);

            $browser->pause(1500);

        });

    }

}
