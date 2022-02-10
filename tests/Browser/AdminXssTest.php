<?php

namespace Tests\Browser;


use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;

class AdminXssTest extends DuskTestCase
{

    public function testPages()
    {
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });


            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });



            

        });

    }
}
