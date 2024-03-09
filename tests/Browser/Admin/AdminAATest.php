<?php

namespace Tests\Browser\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Components\AdminMakeInstall;
use Tests\DuskTestCase;

class AdminAATest extends DuskTestCase
{

    public function testAdminInstall()
    {
        $this->browse(function (Browser $browser)  {

            if (!mw_is_installed()) {
                $browser->within(new AdminMakeInstall(), function ($browser) {
                    $browser->makeInstallation();
                });


            }

            $user = User::where('is_admin', 1)->first();
            $this->assertNotNull($user);
            Auth::login($user);
        });

    }

    public function testAdminInstallAfter()
    {
        $this->browse(function (Browser $browser)  {

            $user = User::where('is_admin', 1)->first();
            $this->assertNotNull($user);
            Auth::login($user);


            $browser->visit(admin_url());
            $this->assertTrue(mw_is_installed());

            //check if locale is en_US
            $this->assertEquals('en_US', app()->getLocale());
            $this->assertEquals('en_US', Config::get('app.locale'));

        });

    }
}
