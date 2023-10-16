<?php

namespace Tests\Browser\SlowTests;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\AdminMakeInstall;
use Tests\DuskTestCase;

class AdminViewDashboardTest extends DuskTestCase
{

    public function testViewDashboard()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminMakeInstall(), function ($browser) {
                $browser->makeInstallation();
            });

            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            $browser->visit(admin_url());


            $browser->assertSee('Dashboard');
            $browser->assertSee('Marketplace');
            $browser->assertSee('Statistics');
            $browser->assertSee('Website');
            $browser->assertSee('Modules');
            $browser->assertSee('Marketplace');
            $browser->assertSee('Settings');
            $browser->assertSee('Users');
            $browser->assertSee('Log out');

            $browser->pause(1000);

        });

    }

}
