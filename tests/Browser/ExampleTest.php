<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testInstallation()
    {
        $siteUrl = $this->siteUrl;

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
            $browser->assertSee('Login');

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
            $browser->waitForLocation('admin/', 120);

            $browser->assertSee('Statistics');
            $browser->assertSee('Live Edit');
            $browser->assertSee('Website');
            $browser->assertSee('Shop');
            $browser->assertSee('Modules');
            $browser->assertSee('Marketplace');
            $browser->assertSee('Settings');
            $browser->assertSee('Users');
            $browser->assertSee('Log out');

        });

    }

}
