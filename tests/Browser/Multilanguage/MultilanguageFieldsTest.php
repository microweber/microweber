<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;
use Tests\DuskTestCaseMultilanguage;

class MultilanguageFieldsTest extends DuskTestCaseMultilanguage
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testLiveEdit()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->pause(1000);
            $browser->visit($siteUrl.'admin/view:modules/load_module:multilanguage?dusk=1');
            $browser->pause(1000);




        });

    }
}
