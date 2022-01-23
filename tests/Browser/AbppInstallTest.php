<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
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

            $browser->visit($siteUrl . 'admin/login')->assertSee('Login');

            // Login to admin panel
            $browser->type('username', '1');
            $browser->type('password', '1');

            $browser->click('@login-button');

            // Wait for redirect after login
            $browser->waitForText('Dashboard');

            $browser->assertSee('Statistics');
            $browser->assertSee('Live Edit');
            $browser->assertSee('Website');
            $browser->assertSee('Shop');
            $browser->assertSee('Modules');
            $browser->assertSee('Marketplace');
            $browser->assertSee('Settings');
            $browser->assertSee('Users');
            $browser->assertSee('Log out');

            $browser->pause(1500);

        });

    }

}
