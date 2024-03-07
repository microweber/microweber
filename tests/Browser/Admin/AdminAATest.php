<?php

namespace Tests\Browser\Admin;

use Illuminate\Support\Facades\Auth;
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
}
