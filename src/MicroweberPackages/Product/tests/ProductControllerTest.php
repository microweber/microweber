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
            ['title' => 'Test 1112', 'content_body' => '<b>Bold text</b>', 'content' => '<b onmouseover=alert(‘XSS testing!‘)>XSS</b>   <IMG SRC=j&#X41vascript:alert(\'test2\')>']
        );
//dd($response->status(), $response->getContent());

        $this->assertEquals(200, $response->status());
        $product_id = $response->getContent();

        // dd($product_id);

        $response = $this->call(
            'GET',
            route('admin.products.show', $product_id)
        );


        //dd('aaaaaaaaaaaaaa', json_decode($response->getContent(), 1));
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