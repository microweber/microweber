<?php

namespace Tests\Browser\Multilanguage\SlowTests;

use Laravel\Dusk\Browser;
use Tests\Browser\Multilanguage\Components\AdminLogin;
use Tests\Browser\Multilanguage\Components\AdminMakeInstall;
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


            $browser->waitForText('Dashboard', 30);
            $browser->waitForText('Marketplace', 30);
            $browser->waitForText('Statistics', 30);
            $browser->waitForText('Website', 30);
            $browser->waitForText('Modules', 30);
            $browser->waitForText('Marketplace', 30);
            $browser->waitForText('Settings', 30);
            $browser->waitForText('Users', 30);
            $browser->waitForText('Log out', 30);

            $browser->pause(1000);

        });

    }

}
