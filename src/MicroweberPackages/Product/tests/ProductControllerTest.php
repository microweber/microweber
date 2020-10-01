<?php


namespace MicroweberPackages\Product\tests;


use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\User;
use Illuminate\Support\Facades\Auth;

class ProductControllerTest extends TestCase
{
    public function testAAAA()
    {

        $user = User::firstOrCreate(array('username' => 'admin', 'password' => 'admin', 'is_admin' => '1'));
        Auth::login($user);


        $response = $this->call(
            'POST',
            route('admin.products.store'),
            ['title' => 'Test Product']
        );
        dump($response);
    }
}