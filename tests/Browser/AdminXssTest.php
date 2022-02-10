<?php

namespace Tests\Browser;


use Illuminate\Support\Facades\Route;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminXssTest extends DuskTestCase
{
    public function testPages()
    {
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            $adminUrls = [];
            $routeCollection = Route::getRoutes();
            foreach ($routeCollection as $value) {
                if (strpos($value->uri(), 'admin') !== false) {
                    $adminUrls[] = $value->uri();
                }
            }

            dd($adminUrls); 

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


        });

    }
}
