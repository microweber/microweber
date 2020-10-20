<?php

namespace MicroweberPackages\Users\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\UserManager;
use MicroweberPackages\Utils\Mail\MailSender;


class UserControllerTest extends TestCase
{


    public function testUserTos()
    {


        $uname = 'testuser_' . uniqid();

        $response = $this->call(
            'POST',
            route('api.user.register'),
            [
                'username' => $uname,
                'password' => $uname,
            ]
        );

        $product_data = $response->getData();


        var_dump($product_data);

        $this->assertEquals(201, $response->status());


        //    $this->assertEquals($product_data->data->title, $title);


    }

}
