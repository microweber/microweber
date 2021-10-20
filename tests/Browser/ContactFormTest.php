<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ContactFormTest extends DuskTestCase
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

            $browser->visit($siteUrl . 'contact-us');
            $browser->pause('2000');
            $browser->scrollTo('#contactform');
            $browser->pause('3000');
            $browser->type('your-name', 'Bozhidar Slaveykov');
            $browser->type('e-mail-address', 'bobi@microweber.com');
            $browser->type('phone', '088123456');
            $browser->type('company', 'Microweber ORG');
            $browser->type('message', 'Hello, i\'m very happy to use this software.');
            $browser->script('$("#contactform").submit()');

            $browser->pause('6000');

            $browser->assertSee('Your message successfully sent');
        });
    }
}
