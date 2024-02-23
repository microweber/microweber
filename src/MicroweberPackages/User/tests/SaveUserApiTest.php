<?php

namespace MicroweberPackages\User\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Utils\Mail\MailSender;


class SaveUserApiTest extends TestCase
{
    use UserTestHelperTrait;

    public function testSaveUserFunction()
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

        $getUserBeforeSave = get_user_by_id($userData->data->id);

        $saveUserId = $this->post(
            route('api.save_user'),
            [
                'id' => $userData->data->id,
                'is_active' => 1,
                'is_admin' => 1,
                'is_verified' => 1,
                'phone' => '123123123',
                'profile_url' => 'test',
            ]
        );
        $this->assertSame(intval($saveUserId->getContent()), intval($userData->data->id));

        $getUser = get_user_by_id($userData->data->id);

        $this->assertSame($getUser['is_admin'], $getUserBeforeSave['is_admin']);
        $this->assertSame($getUser['is_active'], $getUserBeforeSave['is_active']);
        $this->assertSame($getUser['is_verified'], $getUserBeforeSave['is_verified']);
        $this->assertSame($getUser['phone'], '123123123');
        $this->assertSame($getUser['profile_url'], 'test');

        // NOW LOGIN AS ADMIN
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $saveUserId = $this->post(
            route('api.save_user'),
            [
                'id' => $userData->data->id,
                'is_active' => 1,
                'is_admin' => 1,
                'is_verified' => 1,
                'phone' => 'phoneEditedFromAdmin',
                'profile_url' => 'profileUrlEditedFromAdmin',
            ]
        );
        $this->assertSame(intval($saveUserId->getContent()), intval($userData->data->id));

        $getUser = get_user_by_id($userData->data->id);

        $this->assertSame($getUser['is_admin'], 1);
        $this->assertSame($getUser['is_active'], 1);
        $this->assertSame($getUser['is_verified'], 1);
        $this->assertSame($getUser['phone'], 'phoneEditedFromAdmin');
        $this->assertSame($getUser['profile_url'], 'profileUrlEditedFromAdmin');


    }

}
