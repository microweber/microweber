<?php
namespace Microweber\tests;

use Microweber\App\User;
use Microweber\Providers\UserManager;

/**
 * Run test
 * @author Bobi Slaveykvo Microweber
 * @command php phpunit.phar --filter UserTest
 */

class UserTest extends TestCase {

    private static $_username = false;
    private static $_password = false;
    private static $_email = false;

    public function testRegistration()
    {
        $data['option_value'] = 'y';
        $data['option_key'] = 'captcha_disabled';
        $data['option_group'] = 'users';
        $save = save_option($data);

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

        $loginDetails = array();
        $loginDetails['username'] = self::$_username;
        $loginDetails['password'] =  self::$_password;

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('success', $loginStatus);

    }

    public function testWrongPasswordLogin()
    {

        $loginDetails = array();
        $loginDetails['username'] = self::$_username;
        $loginDetails['password'] =  'microweber-best';

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('error', $loginStatus);

    }

    public function testWrongUsernameLogin()
    {

        $loginDetails = array();
        $loginDetails['username'] = 'microweber-make-money';
        $loginDetails['password'] =  'microweber-is-the-best';

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('error', $loginStatus);

    }

    public function testWrongEmailLogin()
    {

        $loginDetails = array();
        $loginDetails['email'] = 'microweber-make-happy';
        $loginDetails['password'] =  'microweber-is-the-best';

        $userManager = new UserManager();
        $loginStatus = $userManager->login($loginDetails);

        $this->assertArrayHasKey('error', $loginStatus);

    }

    public function testForgotPassword()
    {
        $userDetails = array();
        $userDetails['username'] = self::$_username;
        $userDetails['email'] =  self::$_email;

        $userManager = new UserManager();
        $requestStatus = $userManager->send_forgot_password($userDetails);

        $this->assertArrayHasKey('success', $requestStatus);
    }
}