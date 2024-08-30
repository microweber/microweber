<?php

namespace MicroweberPackages\Modules\ContactForm\tests\Browser;

use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use MicroweberPackages\Form\Models\FormData;
use MicroweberPackages\Form\Models\FormDataValue;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class ContactFormTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;

        $moduleIdRand = 'testcontactus'.time().rand(1, 1000);

        $fields = 'Your Name[type=text,field_size=6,show_placeholder=true],E-mail Address[type=email,field_size=6,show_placeholder=true],Phone[type=phone, field_size=6,show_placeholder=true],Company[type=text,field_size=6,show_placeholder=true],Message[type=textarea,field_size=12,show_placeholder=true]';

        $params = array(
            'title' => 'My new contact form '.time(),
            'content_type' => 'page',
            'subtype' => 'static',
            'content'=> '<div class="container">
<h1>Contact Us</h1>
<module type="contact_form" id="'.$moduleIdRand.'" template="default" default-fields="'.$fields.'" button-text="Send me some forms" /></div>',

            'is_active' => 1,);


        $saved_id = save_content($params);

        $siteUrl = content_link($saved_id);

        // Disable captcha
        save_option(array(
            'option_group' => 'contact_form_default',
            'module' => 'contact_form',
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));
       save_option(array(
            'option_group' => $moduleIdRand,
            'module' => 'contact_form',
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        $this->browse(function (Browser $browser) use ($siteUrl,$moduleIdRand) {

            $uniqueId = time();

            $browser->visit($siteUrl);


            $browser->waitForText('Contact Us');



            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause('2000');
            $browser->scrollTo($moduleIdRand);
            $browser->pause('3000');

            $browser->assertSee('Your Name');
            $browser->assertSee('E-mail Address');
            $browser->assertSee('Phone');
            $browser->assertSee('Company');
            $browser->assertSee('Message');
            $browser->assertSee('Send me some forms');

            $browser->type('your-name', 'Bozhidar Slaveykov' . $uniqueId);
            $browser->type('e-mail-address', 'bobi' . $uniqueId . '@microweber.com');
            $browser->type('phone', $uniqueId);
            $browser->type('company', 'Microweber ORG' . $uniqueId);
            $browser->type('message', 'Hello, i\'m very happy to use this software.' . $uniqueId);

            //   $browser->attach('file-upload', userfiles_path() . '/templates/default/img/contact_icons.png');
            //  $browser->attach('file-upload2', userfiles_path() .  '/templates/default/img/contact_icons2.png');

            //$browser->script('$("#mw_contact_form_'.$moduleIdRand.'").submit()');

           // $browser->waitForText('Your message successfully sent');
         //   $browser->assertSee('Your message successfully sent');
            $browser->scrollTo('#'.$moduleIdRand.' button[type="submit"]');
            $browser->pause(300);
            $browser->click('#'.$moduleIdRand.' button[type="submit"]');

            $browser->pause(3000);

            $findFormDataId = false;
            $findFormDataValues = FormDataValue::where('field_value', 'bobi' . $uniqueId . '@microweber.com')->get();
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

            $this->assertEquals($formDataValuesMap['message']['field_value'], 'Hello, i\'m very happy to use this software.' . $uniqueId);
            $this->assertEquals($formDataValuesMap['company']['field_value'], 'Microweber ORG' . $uniqueId);
            $this->assertEquals($formDataValuesMap['phone']['field_value'], $uniqueId);
            $this->assertEquals($formDataValuesMap['your-name']['field_value'], 'Bozhidar Slaveykov' . $uniqueId);
            $this->assertEquals($formDataValuesMap['e-mail-address']['field_value'], 'bobi' . $uniqueId . '@microweber.com');

        });
    }

    public function testSubmitWithCheckBoxesAndDropdowns()
    {



        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        $title=  'Contact-Us' . time();
        $contactformid=  'testcontactus' .md5($title);
        $newCleanPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'content' => '<div class="container"><module type="contact_form" id="'.$contactformid.'" template="default" /></div>',
            'title' => $title,
            'preview_layout_file' => 'clean.php',
            'active_site_template' => 'default',
            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanPage)->first();
        $this->assertEquals($findPage->id, $newCleanPage);




        $newPageLink = content_link($findPage->id);

        // Disable captcha
           save_option(array(
            'option_group' => $contactformid,
            'module' => 'contact_form',
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        // make custom fields for the form

        $rel = 'module';
        $fields_csv_str = '';
        $rel_id = $contactformid;
        $fields_csv_str .= 'test-email-field[type=email,field_size=6,show_placeholder=true,required=true,value=test-email-field],';
        $fields_csv_str .= 'test-text-field[type=text,field_size=6,show_placeholder=true,required=true,value=test-text-field],';
        $fields_csv_str .= 'test-phone-field[type=phone,field_size=6,show_placeholder=true,required=true,value=test-phone-field]],';
        $fields_csv_str .= 'test-textarea-field[type=textarea,field_size=12,show_placeholder=true,required=true,value=test-textarea-field],';
        $fields_csv_str .= 'test-radio-field[type=radio,field_size=12,show_placeholder=true,required=true,value=one|two|three],';
        $fields_csv_str .= 'test-dropdown-field[type=dropdown,field_size=12,show_placeholder=true,required=true,value=select_one|select_two|select_three],';
        $fields_csv_str .= 'test-none-field[type=text,field_size=12,show_placeholder=true,required=false]';

        $fields = mw()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);

        $this->browse(function (Browser $browser) use ($newPageLink,$contactformid) {
            $browser->visit($newPageLink);

            $browser->pause('2000');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            $browser->scrollTo('#'.$contactformid);

            $browser->scrollTo('[name="test-email-field"]');
            $browser->pause(1000);
            $browser->type('[name="test-email-field"]', 'email@example.com');

            $browser->scrollTo('[name="test-text-field"]');
            $browser->pause(1000);
            $browser->type('[name="test-text-field"]', 'test-text-field');

            $browser->scrollTo('[name="test-phone-field"]');
            $browser->pause(1000);
            $browser->type('[name="test-phone-field"]', 'test-phone-field');


            $browser->scrollTo('[name="test-textarea-field"]');
            $browser->pause(1000);
            $browser->type('[name="test-textarea-field"]', 'test-textarea-field');


            $browser->scrollTo('[name="test-radio-field"]');
            $browser->pause(1000);
            $browser->radio('[name="test-radio-field"][value="three"]' , 'three');


            $browser->scrollTo('[name="test-dropdown-field"]');
            $browser->pause(1000);
            $browser->select('[name="test-dropdown-field"]', 'select_three');


            $browser->click('#btn-' . $contactformid . '-btn');
            $browser->waitUntilMissing('.mw_form.has-mw-spinner',30);
            $browser->pause(2000);

            $response = mw()->forms_manager->get_entires('rel_id=' . $contactformid);

            $fields = $response[0]["custom_fields"];
            $id = $response[0]["id"];

            $this->assertEquals($fields['test-text-field'], 'test-text-field');
            $this->assertEquals($fields['test-phone-field'], 'test-phone-field');
            $this->assertEquals($fields['test-textarea-field'], 'test-textarea-field');
            $this->assertEquals($fields['test-radio-field'], 'three');
            $this->assertEquals($fields['test-dropdown-field'], 'select_three');
            $this->assertEquals($fields['test-none-field'], null);


//
//            $fakeNotify = Notification::fake();
//
//            $formModel = FormData::with('formDataValues')->find($id);
//
//            $fakeNotify->sendNow(new NewFormEntryToMail($formModel));
//
//            $notifications = $fakeNotify->sentNotifications();
//            dd($formModel);
        });


    }
}
