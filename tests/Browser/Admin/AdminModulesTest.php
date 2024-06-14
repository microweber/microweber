<?php

namespace Tests\Browser\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminModulesTest extends DuskTestCase
{
    public function testModuleList()
    {

        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->waitForText('Modules');
            $browser->clickLink('Modules');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(5000);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            $elems = $browser
                ->pause(1000)
                ->elements('.mw-admin-module-list-item');

            foreach ($elems as $key=> $elem) {
                $html = $browser->script("return $('.mw-admin-module-list-item').eq(".$key.").find('.module-admin-modules-edit-module').first().attr('id')");
                $id_attr = $html[0];
                if($key == 0) {
                    $id_is_ok = 'modules-admin-mw-main-module-backend-admin-modules-edit-module';
                } else {
                    $id_is_ok = 'modules-admin-mw-main-module-backend-admin-modules-edit-module--'.$key;

                }
                 $this->assertEquals($id_attr, $id_is_ok);
            }

        });
    }
}
