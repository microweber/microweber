<?php

namespace MicroweberPackages\User\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Utils\Mail\MailSender;


class UserRegisterControllerTest extends TestCase
{
    use UserTestHelperTrait;


    public function testUserRegisterWithUsername()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();

        $username = 'testuser_' .uniqid();

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'username' => $username,
                'password' => $username,
            ]
        );

        $userData = $response->getData();

         $this->assertEquals($username, $userData->data->username);
        $this->assertNotEmpty($userData->data->id);

        $this->assertTrue(($userData->data->id > 0));

        $this->assertEquals(201, $response->status());

    }

    public function testUserRegisterWithEmail()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();

        $email = 'testuser_' . uniqid() . '@mail.test';

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'email' => $email,
                'password' => $email,
            ]
        );

        $userData = $response->getData();

        $this->assertEquals($email, $userData->data->email);
        $this->assertNotEmpty($userData->data->id);

        $this->assertTrue(($userData->data->id > 0));

        $this->assertEquals(201, $response->status());

    }


    public function testUserRegisterWithUserAndEmail()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();

        $username = 'testuser_' . uniqid();
        $email = 'testuser_' . uniqid() . '@mail.test';

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'email' => $email,
                'username' => $username,
                'password' => $email,
            ]
        );

        $userData = $response->getData();

        $this->assertEquals($username, $userData->data->username);
        $this->assertEquals($email, $userData->data->email);
        $this->assertNotEmpty($userData->data->id);

        $this->assertTrue(($userData->data->id > 0));

        $this->assertEquals(201, $response->status());

    }

    public function testUserRegisterWithMissingRequiredParams()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'password' => 'xxx',
            ]
        );

        $userData = $response->getData();

        $this->assertEquals(422, $response->status());

    }

    public function testUserRegisteWhenDisabled()
    {
        $this->_disableUserRegistration();
        $this->_disableCaptcha();

        $username = 'testuser_' . uniqid();
        $email = 'testuser_' . uniqid() . '@mail.test';

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'email' => $email,
                'username' => $username,
                'password' => $email,
            ]
        );


        $this->assertEquals(403, $response->status());
        $this->_enableUserRegistration();

    }

    public function testUserRegisterWithCaptcha()
    {
        $this->_enableCaptcha();
        $username = 'testuser_' . uniqid();
        $email = 'testuser_' . uniqid() . '@mail.test';
        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'email' => $email,
                'username' => $username,
                'password' => $email,
            ]
        );


        $captchaAnswer = uniqid();
        $captchaWrongAnswer = $captchaAnswer.uniqid();

        $userData = $response->getData();
        $this->assertEquals(422, $response->status());

        $fakeCaptcha = new \MicroweberPackages\Utils\Captcha\tests\Fakers\FakeCaptcha();
        $fakeCaptcha->setAnswer($captchaAnswer);
        app()->captcha_manager->setAdapter($fakeCaptcha);

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'captcha' =>$captchaWrongAnswer,
                'username' => $username,
                'password' => $email,
            ]
        );


        $this->assertEquals(422, $response->status());

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'captcha' =>$captchaAnswer,
                'username' => $username,
                'password' => $email,
            ]
        );
        $this->assertEquals(201, $response->status());

    }



}
