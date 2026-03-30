<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Utils\Mail\MailSender;
use PHPUnit\Framework\Attributes\Test;


class UserLoginControllerTest extends TestCase
{
    use UserTestHelperTrait;

    #[Test]

    public function it_user_login_with_username(): void {
        $this->loginAsAdmin();
        $data['option_value'] = 'y';
        $data['option_key'] = 'enable_user_microweber_registration';
        $data['option_group'] = 'users';
        $save = save_option($data);

        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();
        $this->_disableLoginCaptcha();
        $this->_disableRegistrationApprovalByAdmin();

        $username = 'testuser_' . uniqid();
        $password = 'pass__' . uniqid();

        logout();


        $user = $this->_registerUserWithUsername($username, $password);



        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'username' => $username,
                'password' => $password,
            ]
        );

        $userData = $response->getData();

        $this->assertEquals($username, $userData->data->username);
        $this->assertNotEmpty($userData->data->id);

        $this->assertTrue(($userData->data->id > 0));

        $this->assertEquals(200, $response->status());

    }

    #[Test]

    public function it_user_login_with_email(): void {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableLoginCaptcha();

        $email = 'testusexXr_' . uniqid() . '@aa.bb';
        $password = 'pass__' . uniqid();

        $user = $this->_registerUserWithEmail($email, $password);



        $this->assertEquals($email, $user->data->email);


        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'email' => $email,
                'password' => $password,
            ]
        );

        $userData = $response->getData();

        $this->assertEquals($email, $userData->data->email);
        $this->assertNotEmpty($userData->data->id);

        $this->assertTrue(($userData->data->id > 0));

        $this->assertEquals(200, $response->status());

    }

    #[Test]

    public function it_user_login_with_email_in_username_field(): void {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();
        $this->_disableLoginCaptcha();

        $email = 'testusexXr_' . uniqid() . '@aa.bb';
        $password = 'pass__' . uniqid();

        $user = $this->_registerUserWithEmail($email, $password);

        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'username' => $email,
                'password' => $password,
            ]
        );

        $userData = $response->getData();

        $this->assertEquals($email, $userData->data->email);
        $this->assertNotEmpty($userData->data->id);

        $this->assertTrue(($userData->data->id > 0));

        $this->assertEquals(200, $response->status());

    }


    #[Test]


    public function it_user_login_with_redirect(): void {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();
        $this->_disableLoginCaptcha();


        $email = 'testusexXr_' . uniqid() . '@aa.bb';
        $password = 'pass__' . uniqid();

        $user = $this->_registerUserWithEmail($email, $password);

        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'username' => $email,
                'password' => $password,
                'redirect' => 'home',
            ]
        );

        $userData = $response->getData(true);

        $this->assertArrayHasKey("redirect", $userData);
        $this->assertArrayHasKey("success", $userData);


    }

    #[Test]

    public function it_user_login_requires_captcha(): void {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();
        $this->_enableLoginCaptcha();


        $email = 'testusexXr_' . uniqid() . '@aa.bb';
        $password = 'pass__' . uniqid();

        $user = $this->_registerUserWithEmail($email, $password);

        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'username' => $email,
                'password' => $password,
                'where_to' => 'home',
            ]
        );

        $userData = $response->getData(true);

        $this->assertArrayHasKey("errors", $userData);
        $this->assertNotEmpty($userData['errors']['captcha']);


    }

    #[Test]

    public function it_user_is_logged_after_change_of_is_active(): void {
        $this->_enableUserRegistration();
        $this->_disableCaptcha();
        $this->_disableEmailVerify();
        $this->_disableLoginCaptcha();


        $email = 'testusexXr_' . uniqid() . '@aa.bb';
        $password = 'pass__' . uniqid();

        $user = $this->_registerUserWithEmail($email, $password);

        $response = $this->json(
            'POST',
            route('api.user.login'),
            [
                'username' => $email,
                'password' => $password,
                'where_to' => 'home',
            ]
        );

        $userData = $response->getData(true);

        $this->assertArrayHasKey("redirect", $userData);
        $this->assertArrayHasKey("success", $userData);

        $is_logged = is_logged();
        $this->assertTrue($is_logged);
        $loginData = [
            'username' => $email,
            'password' => $password,
            'where_to' => 'home'
        ];

        $user = User::find($userData['data']['id']);
        $user->is_active = 0;
        $user->save();
        Auth::logout();
        $response = $this->json(
            'POST',
            route('api.user.login'),
            $loginData
        );
        $userData = $response->getData(true);

        $this->assertEquals($userData['error'], "Your account is disabled");

        Auth::logout();
        $response = $this->json(
            'POST',
            route('api.user.login'),
            $loginData
        );
        $userData = $response->getData(true);

        $this->assertEquals($userData['error'], "Your account is disabled");
    }


}
