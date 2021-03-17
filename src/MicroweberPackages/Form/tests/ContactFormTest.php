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
            'option_key' => 'append_files',
            'option_value' => 'file1.jpg,file2.jpg'
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
        $params['module_name'] = 'contact_form';

        $response = mw()->forms_manager->post($params);

        $this->assertArrayHasKey('success', $response);

        $findBody = false;
        $findSubject = false;

        $emails = app()->make('mailer')->getSwiftMailer()->getTransport()->messages();
        foreach ($emails as $email) {

            $subject = $email->getSubject();
            $body = $email->getBody();

            if (str_contains($body, 'HELLO CONTACT FORM THIS IS MY MESSAGE')) {
                $findBody = true;
            }

            if (str_contains($subject, 'New form entry')) {
                $findSubject = true;
            }
        }

        $this->assertTrue($findBody);
        $this->assertTrue($findSubject);
    }

}
