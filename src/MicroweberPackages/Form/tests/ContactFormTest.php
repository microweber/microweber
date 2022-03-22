<?php

namespace MicroweberPackages\Form\tests;

use MicroweberPackages\Core\tests\TestCase;
use Symfony\Component\Mime\Part\Multipart\MixedPart;
use Symfony\Component\Mime\Part\TextPart;

class ContactFormTest extends TestCase
{

    public function testFormSubmit()
    {

        $params = array();
        $params['for_id'] = '1234';
        $params['for'] = 'test';


        // Disable captcha
        save_option(array(
            'option_group' => $params['for_id'],
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        $response = mw()->forms_manager->post($params);

        $this->assertArrayHasKey('errors', $response);
        $this->assertEquals('Fields data is empty', $response['errors']);

    }

    public function testContactFormWithGlobalSettingsSubmit()
    {
        $optionGroup = md5(time().'mw'.rand(1111,9999));
        $formName = md5(time().'mw'.rand(1111,9999));

        \Config::set('mail.transport', 'array');

        // Save global options
        $customReceivers = ['GlobalContactFormEmailTo1@UnitTest.com','GlobalContactFormEmailTo2@UnitTest.com','GlobalContactFormEmailTo3@UnitTest.com','GlobalContactFormEmailTo4@UnitTest.com'];
        save_option(array(
            'option_group' => 'contact_form_default',
            'option_key' => 'email_to',
            'option_value' => implode(',', $customReceivers)
        ));

        save_option(array(
            'option_group' => 'contact_form_default',
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        /**
         * GLOBAL SENDER
         */
        save_option(array(
            'option_group' => 'contact_form_default',
            'option_key' => 'email_custom_sender',
            'option_value' => 'y'
        ));
        save_option(array(
            'option_group' => 'contact_form_default',
            'option_key' => 'email_from',
            'option_value' => 'global-sender-email-from@unittest.bg'
        ));
        save_option(array(
            'option_group' => 'contact_form_default',
            'option_key' => 'email_from_name',
            'option_value' => 'Global Sender Test Email Name'
        ));

        /**
         * ENABLE AUTORESPOND
         */
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_enable',
            'option_value' => 1
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond',
            'option_value' => 'This is the autorespond text - global'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_subject',
            'option_value' => 'This is the autorespond subject - global'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_append_files',
            'option_value' => modules_path() . 'contact_form/contact_form.png'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_reply_to',
            'option_value' => 'AutoRespondEmailReply1Global@UnitTest.com'
        ));

        // Current form settings
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'form_name',
            'option_value' => $formName
        ));

        $params = array();
        $params['for_id'] = $optionGroup;
        $params['for'] = 'contact-form-global-settings-test-module';
        $params['message'] = 'HELLO CONTACT FORM GLBOAL! THIS IS MY GLOBAL MESSAGE';
        $params['email'] = 'unit.b.slaveykov@unittest-global.com';
        $params['Company'] = 'CloudVisionLtd-Global';
        $params['Phone'] = '0885451012-Global';
        $params['Your Name'] = 'Bozhidar Veselinov Slaveykov';
        $params['module_name'] = 'contact_form_global_settings';

        $response = mw()->forms_manager->post($params);
        $this->assertArrayHasKey('success', $response);

        $mailToUser = [];
        $mailToReceivers = [];

        $emails = app()->make('mailer')->getSymfonyTransport()->messages();

        foreach ($emails as $email) {

            $emailAsArray = $this->_getEmailDataAsArray($email);

            if (strpos($emailAsArray['subject'], 'This is the autorespond subject') !== false) {
                // Mail to user
                $mailToUser[] = $emailAsArray;
            }

            if (strpos($emailAsArray['subject'], $formName) !== false) {
                // Mail to receivers
                $mailToReceivers[] = $emailAsArray;
            }
        }

        // The User must receive auto respond data
        $this->assertEquals(count($mailToUser), 1); //  1 user autorespond
        $this->assertTrue(str_contains($mailToUser[0]['body'],'This is the autorespond text - global'));
        $this->assertSame($mailToUser[0]['subject'], 'This is the autorespond subject - global');
        $this->assertSame($mailToUser[0]['replyTo'], 'AutoRespondEmailReply1Global@UnitTest.com');
        $this->assertSame($mailToUser[0]['to'], 'unit.b.slaveykov@unittest-global.com');
        $this->assertSame($mailToUser[0]['from'], 'global-sender-email-from@unittest.bg');


        // Receivers must receive the contact form data
        $this->assertEquals(count($mailToReceivers), 4); // 4 custom receivers

        foreach ($mailToReceivers as $email) {
            $body = $email['body'];
            $this->assertTrue(str_contains($body,'unit.b.slaveykov@unittest-global.com'));
            $this->assertTrue(str_contains($body,'0885451012-Global'));
            $this->assertTrue(str_contains($body,'CloudVisionLtd-Global'));
            $this->assertTrue(str_contains($body,'Bozhidar Veselinov Slaveykov'));
            $this->assertTrue(str_contains($body,'HELLO CONTACT FORM GLBOAL! THIS IS MY GLOBAL MESSAGE'));

        }

        // test the export
        $export = app()->forms_manager->export_to_excel(['id'=>0]);
        $this->assertTrue(isset($export['success']));
        $this->assertTrue(isset($export['download']));
    }

    public function testCustomContactFormSettingsRequiredSubmit()
    {
        $rel = 'module';
        $rel_id = 'layouts-testCustomContactFormSettingsRequiredSubmit'.rand(1111,9999).'-contact-form';
        $fields_csv_str = 'PersonNameRequired[type=text,field_size=6,show_placeholder=true,required=true],';
        $fields_csv_str .= 'PersonTelephoneRequired[type=phone,field_size=6,show_placeholder=true,required=true],';
        $fields_csv_str .= 'PersonMessageRequired[type=textarea,field_size=12,show_placeholder=true,required=true]';

        $fields = mw()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);
        // Disable captcha
        save_option(array(
            'option_group'=>$rel_id,
            'option_key'=> 'disable_captcha',
            'option_value'=> 'y'
        ));

        $fields = mw()->fields_manager->get(['rel_type'=>$rel,'rel_id'=>$rel_id]);
        $this->assertTrue(!empty($fields));

        $list_title = 'My forms list'.rand(1111,9999);
        $params = array();
        $params['for_module_id'] = $rel_id;
        $params['for_module'] = $rel;
        $params['title'] = $list_title;

        $list_response = mw()->forms_manager->save_list($params);
        $this->assertTrue(array_key_exists('success',$list_response));
        $this->assertTrue(isset($list_response['data']['id']));
        $list_id = $list_response['data']['id'];


        $params = array();
        $params['for_id'] = $rel_id;
        $params['for'] = $rel;
         // must return validation error
        $response = mw()->forms_manager->post($params);

        foreach ($fields as $field){
            $this->assertTrue(array_key_exists($field['name_key'],$response['form_errors']));
        }


        $params = array();
        $params['for_id'] = $rel_id;
        $params['for'] = $rel;
        foreach ($fields as $field){
            $params[$field['name_key']] = 'test';
         }

        $response = mw()->forms_manager->post($params);
        $this->assertTrue(array_key_exists('success',$response));
        $this->assertTrue(array_key_exists('id',$response));

        $list_get = mw()->forms_manager->get_lists('single=1&id='.$list_id);
        $this->assertSame($list_get['title'], $list_title);

        $params = array();
        $params['list_id'] = $list_id;
        $response = mw()->forms_manager->get_entires($params);

        $this->assertTrue(!empty($response[0]));
        $this->assertTrue(array_key_exists('custom_fields',$response[0]));

        //must be in the order of custom fields
        $custom_fields_order = array_keys($response[0]['custom_fields']);
        $this->assertSame($custom_fields_order[0], 'personnamerequired');
        $this->assertSame($custom_fields_order[1], 'persontelephonerequired');
        $this->assertSame($custom_fields_order[2], 'personmessagerequired');


    }
    public function testCustomContactFormSettingsSubmit()
    {

        $optionGroup = md5(time().'mw'.rand(1111,9999));
        $formName = md5(time().'mw'.rand(1111,9999));

        \Config::set('mail.transport', 'array');

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'form_name',
            'option_value' => $formName
        ));

        /**
         * ENABLE AUTORESPOND CUSTOM SENDER
         */
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_enable',
            'option_value' => 1
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond',
            'option_value' => 'This is the autorespond text'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_subject',
            'option_value' => 'This is the autorespond subject'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_append_files',
            'option_value' => modules_path() . 'contact_form/contact_form.png'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_reply_to',
            'option_value' => 'AutoRespondEmailReply1@UnitTest.com'
        ));
        // ENABLE CUSTOM SENDER
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_custom_sender',
            'option_value' => '1'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_from',
            'option_value' => 'AutoRespondEmailFrom@UnitTest.com'
        ));

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_autorespond_from_name',
            'option_value' => 'Auto Respond Email From Name'
        ));
        // END OF CUSTOM SENDER AUTORESPOND


        /**
         * ENABLE CUSTOM RECEIVERS
         */
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_custom_receivers',
            'option_value' => '1'
        ));
        $customReceivers = ['EmailTo1@UnitTest.com','EmailTo2@UnitTest.com','EmailTo3@UnitTest.com','EmailTo4@UnitTest.com'];
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_to',
            'option_value' => implode(',', $customReceivers)
        ));

        $params = array();
        $params['for_id'] = $optionGroup;
        $params['for'] = 'contact-form-test-module';
        $params['message'] = 'HELLO CONTACT FORM! THIS IS MY MESSAGE';
        $params['email'] = 'unit.b.slaveykov@unittest.com';
        $params['Company'] = 'CloudVisionLtd';
        $params['Phone'] = '0885451012';
        $params['Your Name'] = 'Bozhidar Slaveykov';
        $params['module_name'] = 'contact_form';

        $response = mw()->forms_manager->post($params);

        $this->assertArrayHasKey('success', $response);

        $mailToUser = [];
        $mailToReceivers = [];

        $emails = app()->make('mailer')->getSymfonyTransport()->messages();
        foreach ($emails as $email) {

            $emailAsArray = $this->_getEmailDataAsArray($email);

            if (strpos($emailAsArray['subject'], $formName) !== false) {
                // Mail to receivers
                $mailToReceivers[] = $email;
            }

            if (strpos($emailAsArray['subject'], 'This is the autorespond subject') !== false) {
                // Mail to user
                $mailToUser[] = $email;
            }
        }

        // Receivers must receive the contact form data
        $this->assertEquals(count($mailToReceivers), 4); // 4 custom receivers
        foreach ($mailToReceivers as $email) {

            $emailAsArray = $this->_getEmailDataAsArray($email);

            $this->assertEquals($emailAsArray['replyTo'], 'unit.b.slaveykov@unittest.com');

            $this->assertTrue(str_contains($emailAsArray['body'],'unit.b.slaveykov@unittest.com'));
            $this->assertTrue(str_contains($emailAsArray['body'],'0885451012'));
            $this->assertTrue(str_contains($emailAsArray['body'],'CloudVisionLtd'));
            $this->assertTrue(str_contains($emailAsArray['body'],'Bozhidar Slaveykov'));
            $this->assertTrue(str_contains($emailAsArray['body'],'HELLO CONTACT FORM! THIS IS MY MESSAGE'));
        }

        // The User must receive auto respond data
        $this->assertEquals(count($mailToUser), 1); //  1 user autorespond
        foreach ($mailToUser as $email) {

            $emailAsArray = $this->_getEmailDataAsArray($email);

            $this->assertTrue(str_contains($emailAsArray['body'],'This is the autorespond text'));

            $this->assertSame($emailAsArray['replyTo'], 'AutoRespondEmailReply1@UnitTest.com');
            $this->assertSame($emailAsArray['subject'], 'This is the autorespond subject');
            $this->assertSame($emailAsArray['from'], 'AutoRespondEmailFrom@UnitTest.com');
            $this->assertSame($emailAsArray['to'], 'unit.b.slaveykov@unittest.com');

        }
    }



    private function _getEmailDataAsArray($email) {

        $emailOriginal = $email->getOriginalMessage();
        $body = $emailOriginal->getBody();

        $emailAsArray = [];

        $emailAsArray['body'] = '';
        if ($body instanceof TextPart) {
            $emailAsArray['body'] = $body->getBody();
        }

        if ($body instanceof MixedPart) {
            $emailAsArray['body'] = $body->bodyToString();
        }

        $emailAsArray['subject'] = $emailOriginal->getSubject();
        $emailAsArray['to'] = $emailOriginal->getTo()[0]->getAddress();
        $emailAsArray['from'] = $emailOriginal->getFrom()[0]->getAddress();

        $emailAsArray['replyTo'] = false;
        if (!empty($emailOriginal->getReplyTo()[0]->getAddress())) {
            $emailAsArray['replyTo'] = $emailOriginal->getReplyTo()[0]->getAddress();
        }

        return $emailAsArray;
    }

}
