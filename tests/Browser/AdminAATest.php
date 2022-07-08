<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Assert as PHPUnit;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\AdminMakeInstall;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\EnvCheck;
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
                $user = User::where('username', 1)->first();
                Auth::login($user);

            }
        });

    }
}
