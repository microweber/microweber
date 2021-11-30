<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\Form\Models\Form;
use MicroweberPackages\Form\Models\FormData;
use MicroweberPackages\Form\Models\FormDataValue;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class ContactFormTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;

        // Disable captcha
        save_option(array(
            'option_group' => 'contact_form_default',
            'module' => 'contact_form',
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        $this->browse(function (Browser $browser) use($siteUrl) {

            $uniqueId = time();

            $browser->visit($siteUrl . 'contact-us');
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause('2000');
            $browser->scrollTo('#contactform');
            $browser->pause('3000');

            $browser->assertSee('Your Name');
            $browser->assertSee('E-mail Address');
            $browser->assertSee('Phone');
            $browser->assertSee('Company');
            $browser->assertSee('Message');
            $browser->assertSee('Send message');

            $browser->type('your-name', 'Bozhidar Slaveykov' . $uniqueId);
            $browser->type('e-mail-address', 'bobi'.$uniqueId.'@microweber.com');
            $browser->type('phone', $uniqueId);
            $browser->type('company', 'Microweber ORG'.$uniqueId);
            $browser->type('message', 'Hello, i\'m very happy to use this software.'.$uniqueId);

         //   $browser->attach('file-upload', userfiles_path() . '/templates/default/img/contact_icons.png');
          //  $browser->attach('file-upload2', userfiles_path() .  '/templates/default/img/contact_icons2.png');

            $browser->script('$("#contactform").submit()');

            $browser->waitForText('Your message successfully sent');
            $browser->assertSee('Your message successfully sent');

            $findFormDataId = false;
            $findFormDataValues = FormDataValue::where('field_value', 'bobi'.$uniqueId.'@microweber.com')->get();
            foreach ($findFormDataValues as $formDataValue) {
                if ($formDataValue->form_data_id) {
                    $findFormDataId = $formDataValue->form_data_id;
                }
            }

            $findFrom = FormData::where('id', $findFormDataId)->with('formDataValues')->first();

            $formDataValuesMap = [];
            foreach ($findFrom->formDataValues as $formDataValue) {
                $formDataValuesMap[$formDataValue->field_key] = $formDataValue->toArray();
            }

            $this->assertEquals($formDataValuesMap['your-name']['field_name'], 'Your Name');
            $this->assertEquals($formDataValuesMap['your-name']['field_type'], 'text');

            $this->assertEquals($formDataValuesMap['e-mail-address']['field_name'], 'E-mail Address');
            $this->assertEquals($formDataValuesMap['e-mail-address']['field_type'], 'email');

            $this->assertEquals($formDataValuesMap['phone']['field_name'], 'Phone');
            $this->assertEquals($formDataValuesMap['phone']['field_type'], 'phone');

            $this->assertEquals($formDataValuesMap['company']['field_name'], 'Company');
            $this->assertEquals($formDataValuesMap['company']['field_type'], 'text');

            $this->assertEquals($formDataValuesMap['message']['field_name'], 'Message');
            $this->assertEquals($formDataValuesMap['message']['field_type'], 'text');

            $this->assertEquals($formDataValuesMap['message']['field_value'], 'Hello, i\'m very happy to use this software.'.$uniqueId);
            $this->assertEquals($formDataValuesMap['company']['field_value'], 'Microweber ORG'.$uniqueId);
            $this->assertEquals($formDataValuesMap['phone']['field_value'], $uniqueId);
            $this->assertEquals($formDataValuesMap['your-name']['field_value'], 'Bozhidar Slaveykov' . $uniqueId);
            $this->assertEquals($formDataValuesMap['e-mail-address']['field_value'], 'bobi'.$uniqueId.'@microweber.com');

        });
    }
}
