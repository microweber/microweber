<?php
namespace MicroweberPackages\Order\tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class OrderApiControllerTest extends TestCase
{
    public function testStore()
    {
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        $first_name = 'Iphone and spire 4ever! - '. rand();
        $first_name2 = 'Iphone and spire 4ever! - '. rand();

        $response = $this->call(
            'POST',
            route('api.order.store'),
            [
                'first_name' => $first_name,
            ]
        );

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->first_name, $first_name);


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $contentDataSaved->id,
                'first_name' => $first_name2,
            ])

        );

        $this->assertEquals(200, $response->status());
        $contentDataSaved = $response->getData()->data;

        $this->assertEquals($contentDataSaved->first_name, $first_name2);


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $contentDataSaved->id,
                'first_name' => 'new first_name',
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->first_name, 'new first_name');


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $contentDataSaved->id,
                'first_name' => '0',
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->first_name, 0);


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $contentDataSaved->id,
            ])

        );
        $this->assertEquals(200, $response->status());

    }

    public function testUpdate()
    {
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        $first_name = 'Test add content from api ' . rand();
        $first_name2 = 'Test update content from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.order.store'),
            [
                'first_name' => $first_name,
            ]
        );


        $this->assertEquals(201, $response->status());
        $contentData = $response->getData();
        $this->assertEquals($contentData->data->first_name, $first_name);

        $content_id = $contentData->data->id;


        $response = $this->call(
            'GET',
            route('api.order.show',
                [
                    'order' => $content_id,
                ])
        );

        $contentData = $response->getData();


        $this->assertEquals($contentData->data->first_name, $first_name);


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $content_id,
                'first_name' => $first_name2,
            ])

        );

        $this->assertEquals(200, $response->status());

        $response = $this->call(
            'GET',
            route('api.order.show',
                [
                    'order' => $content_id,
                ])
        );

        $contentData = $response->getData();

        $this->assertEquals($contentData->data->title, $first_name2);



        $response = $this->call(
            'GET',
            route('api.order.index',
                [
                ])
        );

        $contentData = $response->getData();
        $this->assertEquals(true,!empty($contentData->data));

    }

    public function testDelete()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $first_name = 'Test add menu from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.order.store'),
            [
                'first_name' => $first_name,
            ]
        );


        $response = $this->call(
            'DELETE',
            route('api.order.destroy', [
                'order' => $response->getData()->data->id,
            ])
        );

        $this->assertEquals(200, $response->status());
        $contentData = $response->getData()->data->id;

        $this->assertNotEmpty($contentData);
    }
}
