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
            ['title' => 'Test Product 1']
        );


        $this->assertEquals(200, $response->status());
        $product_id = $response->getContent();


        $response = $this->call(
            'GET',
            route('admin.products.show', $product_id)
        );



dd('aaaaaaaaaaaaaa',$response->getContent());
//dd($product_id);

//        $response
//            ->assertStatus(200);

//
//        dd($response->status());
//        dd($response->getContent());

       // $this->assertEquals(200, $response->status());

//
//dd($this);
//dd($this);
//
//        dd($response);
        //dump($response);

        //dump($this->response->getContent());

    //    $this->assertResponseOk();

       // $this->assertEquals('', $this->response->getContent());

        //dump($response->getData());
    }
}