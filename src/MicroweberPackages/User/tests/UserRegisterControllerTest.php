<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;


class UserRegisterControllerTest extends TestCase
{
    use UserTestHelperTrait;


    public function testUserRegisterWithUsername()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();

        $username = 'testuser_' . uniqid();

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
        $captchaWrongAnswer = $captchaAnswer . uniqid();

        $userData = $response->getData();
        $this->assertEquals(422, $response->status());

        $fakeCaptcha = new \MicroweberPackages\Utils\Captcha\tests\Fakers\FakeCaptcha();
        $fakeCaptcha->setAnswer($captchaAnswer);
        app()->captcha_manager->setAdapter($fakeCaptcha);

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'captcha' => $captchaWrongAnswer,
                'username' => $username,
                'password' => $email,
            ]
        );


        $this->assertEquals(422, $response->status());

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'captcha' => $captchaAnswer,
                'username' => $username,
                'password' => $email,
            ]
        );
        $this->assertEquals(201, $response->status());

    }

    public function testUserRegisterEmailSend()
    {
        \Config::set('mail.transport', 'array');


        $this->_enableRegisterWelcomeEmail();
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();

        $username = 'testuser_email_send_' . uniqid();
        $user_email = 'testuser_email_send_' . uniqid() . '@mail.test';

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'email' => $user_email,
                'username' => $username,
                'password' => $user_email,
            ]
        );

        $userData = $response->getData();
        $emails = app()->make('mailer')->getSymfonyTransport()->messages();
        $findEmail = false;

        foreach ($emails as $email) {

            $subject = $email->getSubject();
            $body = $email->getBody();
            if (str_contains($body, $user_email)) {
                $findEmail = true;
            }


        }


        $this->assertEquals(true, $findEmail);


    }

    public function testUserRegisterValidationMessages()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();



        $user_email = 'testuser_invalid_email_' . uniqid() . '.invalid.email';

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'email' => $user_email,
                'password' => $user_email,
            ]
        );

        $userData = $response->getData(true);

        $this->assertEquals(true, isset($userData['errors']['email']));
        $this->assertEquals(422, $response->status());
        $this->assertEquals('The email must be a valid email address.', $userData['errors']['email'][0]);

    }
    public function testUserAdminCreateAndUpdate()
    {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);




        $username = 'testuser_testUserAdminCreateAndUpdate_' . uniqid();
        $user_email = 'testuser_testUserAdminCreateAndUpdate_' . uniqid() . '@mail.test';

        $username2 = 'testuse2_testUserAdminCreateAndUpdate_' . uniqid();
        $user_email2 = 'testuser2_testUserAdminCreateAndUpdate_' . uniqid() . '@mail.test';

        $username3 = 'testuse3_testUserAdminCreateAndUpdate_' . uniqid();

        $response = $this->json(
            'POST',
            route('api.user.store'),
            [
                'email' => $user_email,
                'username' => $username,
                'password' => $user_email,
            ]
        );

        $userData = $response->getData();

        $this->assertNotEmpty($userData->data->id);
        $user_id = $userData->data->id;

        $this->assertEquals(201, $response->status());
        $this->assertEquals($username, $userData->data->username);
        $this->assertEquals($user_email, $userData->data->email);



        //again the same user
        $response = $this->json(
            'POST',
            route('api.user.store'),
            [
                'email' => $user_email,
                'username' => $username,
                'password' => $user_email,
            ]
        );


        $this->assertEquals(422, $response->status());
        $userData = $response->getData();
        $this->assertNotEmpty($userData->message);
        $this->assertNotEmpty($userData->errors);

        //again the same user with email only
        $response = $this->json(
            'POST',
            route('api.user.store'),
            [
                'username' => $username.uniqid(),
                'email' => $user_email,
                'password' => $user_email,
            ]
        );


        $this->assertEquals(422, $response->status());
        $userData = $response->getData();
        $this->assertNotEmpty($userData->message);
        $this->assertNotEmpty($userData->errors);


        $response = $this->json(
            'POST',
            route('api.user.store'),
            [
                'username' => $username2,
                'email' => $user_email2,
                'password' => $user_email2,
            ]
        );
        $userData = $response->getData();

        $this->assertNotEmpty($userData->data->id);
        $this->assertEquals(201, $response->status());
        $this->assertEquals($username2, $userData->data->username);


        $response = $this->json(
            'PATCH',
            route('api.user.update',$user_id),
            [
                'username' => $username2,
                'email' => $user_email2,
                'password' => $user_email2,
            ]
        );

        $userData = $response->getContent();

        $this->assertEquals(422, $response->status());
        $userData = $response->getData();
        $this->assertNotEmpty($userData->message);
        $this->assertNotEmpty($userData->errors);

        $response = $this->json(
            'PATCH',
            route('api.user.update',$user_id),
            [
                'username' => $username3

            ]
        );

        $userData = $response->getData();
        $this->assertEquals(200, $response->status());
        $this->assertEquals($username3, $userData->data->username);


        $response = $this->json(
            'PATCH',
            route('api.user.update',$user_id),
            [
                'email' => $user_email2,

            ]
        );

        $userData = $response->getData();

        $this->assertEquals(422, $response->status());
        $userData = $response->getData();
        $this->assertNotEmpty($userData->message);
        $this->assertNotEmpty($userData->errors);

    }





    public function testIfLoginRouteIsDefined(){
        $route =  route('login');
        $this->assertEquals(true,!empty($route));

    }


}
