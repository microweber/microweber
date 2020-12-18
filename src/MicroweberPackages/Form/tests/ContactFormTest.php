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

        /* var_dump($response);


        $response = mw()->forms_manager->get_entires();

        var_dump($response); */

    }

    public function testFormSubmitWithConfirm()
    {
        \Config::set('mail.transport', 'array');
        save_option(array(
            'option_group' => '1234',
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        save_option(array(
            'option_group' => 'email',
            'option_key' => 'email_from',
            'option_value' => 'test@email.test'
        ));

        save_option(array(
            'option_group' => 'email',
            'option_key' => 'append_files',
            'option_value' => 'file1.jpg,file2.jpg'
        ));
        save_option(array(
            'option_group' => 'email',
            'option_key' => 'email_autorespond',
            'option_value' => 'thank you'
        ));
        save_option(array(
            'option_group' => 'email',
            'option_key' => 'email_autorespond_subject',
            'option_value' => 'email_autorespond_subject test 1134'
        ));


        $params = array();
        $params['for_id'] = '1234';
        $params['for'] = 'test';
        $params['message'] = 'test';
        $params['module_name'] = 'contact_form';

        $response = mw()->forms_manager->post($params);

        $this->assertArrayHasKey('success', $response);
        $emails = app()->make('mailer')->getSwiftMailer()->getTransport()->messages();
        foreach ($emails as $email) {

            $subject = $email->getSubject();
            $body = $email->getBody();
            dump(__FILE__);
            dump($subject);
// dump($body);
        }


    }
    public function testFormSubmitWithUpload()
    {





    }
}
