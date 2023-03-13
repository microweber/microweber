<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Utils\Mail\MailSender;


class UserLoginControllerTest extends TestCase
{
    use UserTestHelperTrait;

    public function testUserLoginWithUsername()
    {

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

    public function testUserLoginWithEmail()
    {
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

    public function testUserLoginWithEmailInUsernameField()
    {
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


    public function testUserLoginWithRedirect()
    {
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


    }

    public function testUserLoginRequiresCaptcha()
    {
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

    public function testUserIsLoggedAfterChangeOfIsActive()
    {
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
