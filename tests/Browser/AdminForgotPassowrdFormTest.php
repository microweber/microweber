<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminForgotPassowrdFormTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;

        // Disable captcha
        save_option(array(
            'option_group' => 'module-layouts-5--3-contact-form',
            'module' => 'contact_form',
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        $this->browse(function (Browser $browser) use($siteUrl) {

            $browser->visit($siteUrl . 'admin/login');
            $browser->pause('2000');

            $browser->click('@forgot-password-link');
            $browser->pause('2000');

            $browser->type('username', 'bobi@microweber.com');
            $browser->click('@reset-password-button');
            $browser->pause('2000');

            $browser->waitForText('We can\'t find a user with that email address');
            $browser->assertSee('We can\'t find a user with that email address');
        });
    }
}
