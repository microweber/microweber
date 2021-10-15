<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class MultilanguageFieldsTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testAddPost()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {
            $browser->visit($siteUrl . 'admin/login')->assertSee('Login');

            // Login to admin panel
            $browser->type('username', '1');
            $browser->type('password', '1');

            $browser->click('@login-button');

            // Wait for redirect after login
            $browser->waitForLocation('/admin/', 120);


            $browser->pause(1000);
            $browser->visit($siteUrl.'admin/view:modules/load_module:multilanguage?dusk=1');
            $browser->pause(1000);

            // Test input dropdown
            $browser->assertAttribute('#js-field-box-4 .input-group input','value','Текст на български');
            $browser->assertAttribute('#js-field-box-4 .input-group input','name','multilanguage[bojkata][bg_BG]');
            $browser->assertAttribute('#js-field-box-4 .input-group input','lang','bg_BG');


            $browser->pause(8000);

        });

    }
}
