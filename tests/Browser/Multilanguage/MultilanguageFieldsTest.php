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

    public function testSwitchLanguageFields()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {


            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->pause(1000);
            $browser->visit($siteUrl.'admin/module/view?type=multilanguage&dusk=1');
            $browser->pause(1000);

            // Test bulgarian switch
            $browser->select('#js-field-box-4 .input-group-append select', 'bg_BG');
            $browser->pause(1000);

            $browser->script("
                var jsFieldBox4 = $('#js-field-box-4 .input-group input:visible');
                alert(jsFieldBox4.val());
            ");
            $browser->pause(1000);
            $browser->assertDialogOpened('Текст на български');
            $browser->acceptDialog();
            $browser->pause(1000);

            // Test arabian switch
            $browser->select('#js-field-box-4 .input-group-append select', 'ar_SA');
            $browser->pause(1000);

            $browser->script("
                var jsFieldBox4 = $('#js-field-box-4 .input-group input:visible');
                alert(jsFieldBox4.val());
            ");
            $browser->pause(1000);
            $browser->assertDialogOpened('ARABSKI BRAT');
            $browser->acceptDialog();
            $browser->pause(1000);


            // Test english switch
            $browser->select('#js-field-box-4 .input-group-append select', 'en_US');
            $browser->pause(1000);

            $browser->script("
                var jsFieldBox4 = $('#js-field-box-4 .input-group input:visible');
                alert(jsFieldBox4.val());
            ");
            $browser->pause(1000);
            $browser->assertDialogOpened('Text on English');
            $browser->acceptDialog();
            $browser->pause(1000);

        });

    }
}
