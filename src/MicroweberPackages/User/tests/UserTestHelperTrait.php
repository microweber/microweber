<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/20/2020
 * Time: 3:40 PM
 */

namespace MicroweberPackages\User\tests;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use MicroweberPackages\User\Models\User;

trait UserTestHelperTrait
{

    private static $_username = false;
    private static $_password = false;
    private static $_email = false;


    public function actingAsAdmin()
    {
        $user = User::where('is_admin', '=', '1')->first();
        if(!$user){
            $user = $this->_createAdminUser();
        }
        Auth::login($user);
        $this->actingAs($user);
    }

    public function actingAsUser()
    {
        $user = User::where('is_admin', '=', '0')->first();
        if(!$user){
            $user = $this->_createUser();
        }
        Auth::login($user);
        $this->actingAs($user);
    }
    private function _createAdminUser()
    {
        $email = 'admin_' . Str::random(10) . '@example.com';
        $password = bcrypt('password'); // Change 'password' to a secure default password

        $user = new User();
        $user->email = $email;
        $user->password = $password;
        $user->is_admin = 1; // Set the 'is_admin' flag to 1 for admin user

        $user->save();

        return $user;
    }
    private function _createUser()
    {
        $email = 'user_' . Str::random(10) . '@example.com';
        $password = bcrypt('password'); // Change 'password' to a secure default password

        $user = new User();
        $user->email = $email;
        $user->password = $password;
        $user->is_admin = 0;

        $user->save();

        return $user;
    }
    private function _disableCaptcha()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'captcha_disabled';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }


    private function _disableLoginCaptcha()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'login_captcha_enabled';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }
    private function _enableLoginCaptcha()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'login_captcha_enabled';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }


    private function _disableEmailVerify()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'register_email_verify';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }

    private function _enableEmailVerify()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'register_email_verify';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }

    private function _enableCaptcha()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'captcha_disabled';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }

    private function _enableUserRegistration()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'enable_user_registration';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _disableUserRegistration()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'enable_user_registration';
        $data['option_group'] = 'users';
        $save = save_option($data);

    }

    private function _disableUserRegistrationWithDisposableEmail()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'disable_registration_with_temporary_email';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _disableRegistrationApprovalByAdmin()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'registration_approval_required';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _enableRegistrationApprovalByAdmin()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'registration_approval_required';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _enableRegisterWelcomeEmail()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'register_email_enabled';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _registerUserWithEmail($email, $password)
    {
        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'email' => $email,
                'password' => $password,
                'login' => false,
            ]
        );

        return $response->getData();
    }


    private function _registerUserWithUsername($username, $password)
    {
        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'username' => $username,
                'password' => $password,
                'login' => false
            ]
        );

        return $response->getData();
    }

}
