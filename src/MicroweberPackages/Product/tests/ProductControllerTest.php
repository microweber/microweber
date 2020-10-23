<?php


namespace MicroweberPackages\Product\tests;


use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use Illuminate\Support\Facades\Auth;

class ProductControllerTest extends TestCase
{
    public function testSaveProductFromController()
    {

        $user = User::firstOrCreate(array('username' => 'admin', 'password' => 'admin', 'is_admin' => '1'));
        Auth::login($user);

        $title = 'Test add product from api ' . rand();
        $title2 = 'Test update product from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.product.store'),
            [
                'title' => $title,
                'content_body' => '<b>Bold text</b>',
                'content' => '<b onmouseover=alert(‘XSS testing!‘)>XSS</b>   <IMG SRC=j&#X41vascript:alert(\'test2\')>'
            ]
        );


        $this->assertEquals(201, $response->status());
        $product_data = $response->getData();
        $this->assertEquals($product_data->data->title, $title);

        $product_id = $product_data->data->id;


        $response = $this->call(
            'GET',
            route('api.product.show',
                [
                    'product' => $product_id,
                ])
        );

        $product_data = $response->getData();


        $this->assertEquals($product_data->data->title, $title);


        $response = $this->call(
            'PUT',
            route('api.product.update', [
                'product' => $product_id,
                'title' => $title2,
            ])

        );

        $this->assertEquals(200, $response->status());

        $response = $this->call(
            'GET',
            route('api.product.show',
                [
                    'product' => $product_id,
                ])
        );

        $product_data = $response->getData();

        $this->assertEquals($product_data->data->title, $title2);



        $response = $this->call(
            'GET',
            route('api.product.index',
                [
                ])
        );

        $product_data = $response->getData();
        $this->assertEquals(true,!empty($product_data->data));

    }
}