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

    public function testFormSubmitWithConfirm()
    {

        $optionGroup = md5(time().'mw'.rand(1111,9999));

        \Config::set('mail.transport', 'array');

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_from',
            'option_value' => 'EmailFrom@UnitTest.com'
        ));

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_from_name',
            'option_value' => 'Email From Name'
        ));

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_to',
            'option_value' => 'EmailTo@UnitTest.com'
        ));

        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'append_files',
            'option_value' => 'file1.jpg,file2.jpg'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_reply',
            'option_value' => 'EmailReply1@UnitTest.com'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'email_bcc',
            'option_value' => 'Email1@UnitTest.com, Email2@UnitTest.com, Email3@UnitTest.com'
        ));
        save_option(array(
            'option_group' => $optionGroup,
            'option_key' => 'enable_auto_respond',
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

        $params = array();
        $params['for_id'] = $optionGroup;
        $params['for'] = 'contact-form-test-module';
        $params['message'] = 'HELLO CONTACT FORM THIS IS MY MESSAGE';
        $params['email'] = 'unit.b.slaveykov@unittest.com';
        $params['Company'] = 'CloudVisionLtd';
        $params['Phone'] = '0885451012';
        $params['Your Name'] = 'Bozhidar Slaveykov';
        $params['module_name'] = 'contact_form';

        $response = mw()->forms_manager->post($params);

        $this->assertArrayHasKey('success', $response);

        $findBody = false;
        $findSubject = false;

        $sendedToMails = [];
        $sendedFromMails = [];

        $emails = app()->make('mailer')->getSwiftMailer()->getTransport()->messages();
        foreach ($emails as $email) {

            $subject = $email->getSubject();
            $body = $email->getBody();
            $to = key($email->getTo());
            $from = key($email->getFrom());

            $sendedToMails[] = $to;
            $sendedFromMails[] = $from;

            // var_dump($subject);

            if (str_contains($body, 'HELLO CONTACT FORM THIS IS MY MESSAGE')) {
                $findBody = true;
            }

            if (str_contains($subject, 'New form entry')) {
                $findSubject = true;
            }
        }

        $this->assertTrue($findBody);
        $this->assertTrue($findSubject);

        $this->assertTrue(in_array('unit.b.slaveykov@unittest.com', $sendedToMails));

        /// CHECK BCCS
        $findBcc1 = false;
        $findBcc2 = false;
        $findBcc3 = false;

        if (in_array('Email1@UnitTest.com', $sendedToMails)) {
            $findBcc1 = true;
        }

        if (in_array('Email2@UnitTest.com', $sendedToMails)) {
            $findBcc2 = true;
        }

        if (in_array('Email3@UnitTest.com', $sendedToMails)) {
            $findBcc3 = true;
        }

        $this->assertTrue($findBcc1);
        $this->assertTrue($findBcc2);
        $this->assertTrue($findBcc3);

    }

}
