<?php

namespace MicroweberPackages\User\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\UserManager;
use MicroweberPackages\Utils\Mail\MailSender;


class UserControllerTest extends TestCase
{
    use UserTestHelperTrait;


    public function testUserRegisterWithUsername()
    {
        $this->_enableUserRegistration();

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

        $this->assertEquals($username, $userData->username);
        $this->assertNotEmpty($userData->id);

        $this->assertTrue(($userData->id > 0));

        $this->assertEquals(201, $response->status());

    }

    public function testUserRegisterWithEmail()
    {
        $this->_enableUserRegistration();

        $email = 'testuser_' . uniqid().'@mail.test';

        $response = $this->json(
            'POST',
            route('api.user.register'),
            [
                'email' => $email,
                'password' => $email,
            ]
        );

        $userData = $response->getData();

        $this->assertEquals($email, $userData->email);
        $this->assertNotEmpty($userData->id);

        $this->assertTrue(($userData->id > 0));

        $this->assertEquals(201, $response->status());

    }


    public function testUserRegisterWithUserAndEmail()
    {
        $this->_enableUserRegistration();

        $username = 'testuser_' . uniqid();
        $email = 'testuser_' . uniqid().'@mail.test';

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

        $this->assertEquals($username, $userData->username);
        $this->assertEquals($email, $userData->email);
        $this->assertNotEmpty($userData->id);

        $this->assertTrue(($userData->id > 0));

        $this->assertEquals(201, $response->status());

    }

    public function testUserRegisterWithMissingRequiredParams()
    {
        $this->_enableUserRegistration();

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

        $username = 'testuser_' . uniqid();
        $email = 'testuser_' . uniqid().'@mail.test';

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

}
