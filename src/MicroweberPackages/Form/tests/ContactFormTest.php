<?php

namespace MicroweberPackages\Form\tests;

use MicroweberPackages\Core\tests\TestCase;

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

        $this->assertArrayHasKey('success', $response);

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
            'option_value' => 'global1.jpg,global2.jpg'
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

        $emails = app()->make('mailer')->getSwiftMailer()->getTransport()->messages();
        foreach ($emails as $email) {

            $subject = $email->getSubject();

            if (strpos($subject, 'This is the autorespond subject') !== false) {
                // Mail to user
                $mailToUser[] = $email;
            }

            if (strpos($subject, $formName) !== false) {
                // Mail to receivers
                $mailToReceivers[] = $email;
            }
        }

        // The User must receive auto respond data
        $this->assertEquals(count($mailToUser), 1); //  1 user autorespond
        foreach ($mailToUser as $email) {

            $subject = $email->getSubject();
            $body = $email->getBody();
            $to = key($email->getTo());
            $from = key($email->getFrom());
            $replyTo = key($email->getReplyTo());

            $this->assertTrue(str_contains($body,'This is the autorespond text - global'));
            $this->assertSame($subject, 'This is the autorespond subject - global');
            $this->assertSame($replyTo, 'AutoRespondEmailReply1Global@UnitTest.com');
            $this->assertSame($to, 'unit.b.slaveykov@unittest-global.com');
            $this->assertSame($from, 'global-sender-email-from@unittest.bg');
            $this->assertSame($email->getFrom()[$from], 'Global Sender Test Email Name');

        }

        // Receivers must receive the contact form data
        $this->assertEquals(count($mailToReceivers), 5); // 4 custom receivers + 1 admin
        foreach ($mailToReceivers as $email) {

            $to = key($email->getTo());
            $body = $email->getBody();
            $replyTo = key($email->getReplyTo()); // Reply to must be the user email

            $this->assertEquals($replyTo, 'unit.b.slaveykov@unittest-global.com');
            $this->assertTrue(str_contains($body,'unit.b.slaveykov@unittest-global.com'));
            $this->assertTrue(str_contains($body,'0885451012-Global'));
            $this->assertTrue(str_contains($body,'CloudVisionLtd-Global'));
            $this->assertTrue(str_contains($body,'Bozhidar Veselinov Slaveykov'));
            $this->assertTrue(str_contains($body,'HELLO CONTACT FORM GLBOAL! THIS IS MY GLOBAL MESSAGE'));

          //  $this->assertTrue(in_array($to, $customReceivers));
        }

        // test the export
        $export = app()->forms_manager->export_to_excel(['id'=>0]);
        $this->assertTrue(isset($export['success']));
        $this->assertTrue(isset($export['download']));
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
            'option_value' => 'file1.jpg,file2.jpg'
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

        $emails = app()->make('mailer')->getSwiftMailer()->getTransport()->messages();
        foreach ($emails as $email) {

            $subject = $email->getSubject();

            if (strpos($subject, $formName) !== false) {
                // Mail to receivers
                $mailToReceivers[] = $email;
            }

            if (strpos($subject, 'This is the autorespond subject') !== false) {
                // Mail to user
                $mailToUser[] = $email;
            }
        }

        // Receivers must receive the contact form data
        $this->assertEquals(count($mailToReceivers), 5); // 4 custom receivers
        foreach ($mailToReceivers as $email) {

            $to = key($email->getTo());
            $body = $email->getBody();
            $replyTo = key($email->getReplyTo()); // Reply to must be the user email

            $this->assertEquals($replyTo, 'unit.b.slaveykov@unittest.com');


            $this->assertTrue(str_contains($body,'unit.b.slaveykov@unittest.com'));
            $this->assertTrue(str_contains($body,'0885451012'));
            $this->assertTrue(str_contains($body,'CloudVisionLtd'));
            $this->assertTrue(str_contains($body,'Bozhidar Slaveykov'));
            $this->assertTrue(str_contains($body,'HELLO CONTACT FORM! THIS IS MY MESSAGE'));
        }

        // The User must receive auto respond data
        $this->assertEquals(count($mailToUser), 1); //  1 user autorespond
        foreach ($mailToUser as $email) {

            $subject = $email->getSubject();
            $body = $email->getBody();
            $to = key($email->getTo());
            $from = key($email->getFrom());
            $replyTo = key($email->getReplyTo());


            $this->assertTrue(str_contains($body,'This is the autorespond text'));

            $this->assertSame($replyTo, 'AutoRespondEmailReply1@UnitTest.com');
            $this->assertSame($subject, 'This is the autorespond subject');
            $this->assertSame($from, 'AutoRespondEmailFrom@UnitTest.com');
            $this->assertSame($to, 'unit.b.slaveykov@unittest.com');
        }
    }

}
