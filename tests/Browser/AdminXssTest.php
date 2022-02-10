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

            $routeCollection = Route::getRoutes();
            foreach ($routeCollection as $value) {
                if ($value->getActionMethod() == 'GET') {
                    continue;
                }
                if (strpos($value->uri(), 'admin') !== false) {
                    $browser->visit($value->uri());

                    $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                        $browser->validate();
                    });

                 //   $browser->pause(2000);
                }
            }


        });

    }
}
