<?php

namespace Microweber\tests;

use Microweber\App\User;
use Microweber\Providers\UserManager;
use Microweber\Utils\MailSender;

/**
 * Run test
 * @author Bobi Slaveykvo Microweber
 * @command php phpunit.phar --filter UserTest
 */
class UserTest extends TestCase
{
    private static $_username = false;
    private static $_password = false;
    private static $_email = false;

    private function _disableCaptcha()
    {
        $data['option_value'] = 'y';
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

    private function _disableRegistrationApproval()
    {
        $data['option_value'] = 'n';
        $data['option_key'] = 'registration_approval_required';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _enableRegistrationApproval()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'registration_approval_required';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    private function _enableRegisterEmail()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'register_email_enabled';
        $data['option_group'] = 'users';
        $save = save_option($data);
    }

    public function testRegistration()
    {
        $this->_disableCaptcha();
        $this->_enableUserRegistration();
        $this->_disableRegistrationApproval();

        $randomInt = rand(1111, 9999);
        $password = md5($randomInt);

        // Test simple user registration
        $newUser = array();
        $newUser['username'] = 'bobi_' . $randomInt;
        $newUser['email'] = $newUser['username'] . '@microweber.com';
        $newUser['password'] = $password;
        $newUser['password_confirm'] = $password;

        $userManager = new UserManager();
        $registerStatus = $userManager->register($newUser);
        $this->assertArrayHasKey('success', $registerStatus);

        self::$_username = $newUser['username'];
        self::$_password = $newUser['password'];
        self::$_email = $newUser['email'];

    }

    /*
        public function testEmailWrongRegistration()
        {
            $data['option_value'] = 'y';
            $data['option_key'] = 'captcha_disabled';
            $data['option_group'] = 'users';
            $save = save_option($data);

            $randomInt = rand(1111, 9999);
            $password = md5($randomInt);

            // Test wrong email user registration
            $newUser = array();
            // $newUser['username'] = 'bobi_'.$randomInt;
            $newUser['email'] = 'wrong-email';
            $newUser['password'] = $password;
            $newUser['password_confirm'] = $password;

            $userManager = new UserManager();
            $registerStatus = $userManager->register($newUser);

            $this->assertArrayHasKey('success', $registerStatus);
        }
    */

    public function testLogin()
    {
        $this->_disableCaptcha();
        $this->_disableRegistrationApproval();

        $loginDetails = array();
        $loginDetails['username'] = self::$_username;
        $loginDetails['password'] = self::$_password;

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('success', $loginStatus);

    }

    public function testWrongPasswordLogin()
    {
        $this->_disableCaptcha();
        $this->_disableRegistrationApproval();

        $loginDetails = array();
        $loginDetails['username'] = self::$_username;
        $loginDetails['password'] = 'microweber-best';

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('error', $loginStatus);

    }

    public function testWrongUsernameLogin()
    {
        $this->_disableCaptcha();
        $this->_disableRegistrationApproval();

        $loginDetails = array();
        $loginDetails['username'] = 'microweber-make-money';
        $loginDetails['password'] = 'microweber-is-the-best';

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('error', $loginStatus);

    }

    public function testWrongEmailLogin()
    {
        $this->_disableCaptcha();
        $this->_disableRegistrationApproval();

        $loginDetails = array();
        $loginDetails['email'] = 'microweber-make-happy';
        $loginDetails['password'] = 'microweber-is-the-best';

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('error', $loginStatus);

    }

    public function testForgotPassword()
    {
        $this->_disableCaptcha();

        $userDetails = array();
        $userDetails['username'] = self::$_username;
        $userDetails['email'] = self::$_email;

        $userManager = new UserManager();
        $requestStatus = $userManager->send_forgot_password($userDetails);
        $this->assertArrayHasKey('success', $requestStatus);

        $checkEmailContent = MailSender::$last_send['content'];

        $findPasswordResetLink = false;
        if (strpos($checkEmailContent, 'reset_password_link=') !== false) {
            $findPasswordResetLink = true;
        }
        $findUsername = false;
        if (strpos($checkEmailContent, $userDetails['username']) !== false) {
            $findUsername = true;
        }
        $findIpAddress = false;
        if (strpos($checkEmailContent, MW_USER_IP) !== false) {
            $findIpAddress = true;
        }

        $this->assertEquals(true, $findPasswordResetLink);
        $this->assertEquals(true, $findUsername);
        $this->assertEquals(true, $findIpAddress);

    }

    public function testResetPassword()
    {
        $password_reset_hash = '[like]';

        $check = mw()->user_manager->get_all('exclude_shorthand=password_reset_hash&single=true&password_reset_hash=[not_null]&password_reset_hash=' . $password_reset_hash . '&username=' . self::$_username);

        $this->assertEquals(false, $check);

    }


    public function testDisableUserRegistration()
    {
        $this->_disableUserRegistration();

        $randomInt = rand(1111, 9999);
        $password = md5($randomInt);

        // Test simple user registration
        $newUser = array();
        $newUser['username'] = 'bobi_' . $randomInt;
        $newUser['email'] = $newUser['username'] . '@microweber.com';
        $newUser['password'] = $password;
        $newUser['password_confirm'] = $password;

        $userManager = new UserManager();
        $registerStatus = $userManager->register($newUser);

        $this->assertArrayHasKey('error', $registerStatus);
    }

    public function testUserApprovalRegistration()
    {
        $this->_enableUserRegistration();
        $this->_enableRegistrationApproval();
        $this->_enableRegisterEmail();

        $randomInt = rand(1111, 9999);
        $password = md5($randomInt);

        // Test simple user registration
        $newUser = array();
        $newUser['username'] = 'bobi_' . $randomInt;
        $newUser['email'] = $newUser['username'] . '@microweber.com';
        $newUser['password'] = $password;
        $newUser['password_confirm'] = $password;

        $userManager = new UserManager();
        $registerStatus = $userManager->register($newUser);

        $this->assertArrayHasKey('success', $registerStatus);

        $loginDetails = array();
        $loginDetails['username'] = $newUser['username'];
        $loginDetails['password'] = $newUser['password'];

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('error', $loginStatus);

        if (strpos($loginStatus['error'], 'awaiting approval') !== false) {
            $this->assertEquals(true, true);
        } else {
            $this->assertEquals(true, false);
        }

        $checkEmailContent = MailSender::$last_send['content'];

        $findVerifyEmailLink = false;
        if (strpos($checkEmailContent, 'verify_email_link?key=') !== false) {
            $findVerifyEmailLink = true;
        }

        $findUsername = false;
        if (strpos($checkEmailContent, $loginDetails['username']) !== false) {
            $findUsername = true;
        }

        $this->assertEquals(true, $findVerifyEmailLink);
        $this->assertEquals(true, $findUsername);
    }

}