<?php
namespace MicroweberPackages\Order\tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class OrderApiControllerTest extends TestCase
{
    public function testAddContentFull()
    {
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        $title = 'Iphone and spire 4ever! - '. rand();
        $title2 = 'Iphone and spire 4ever! - '. rand();

        $response = $this->call(
            'POST',
            route('api.order.store'),
            [
                'title' => $title,
                'content' => '',
            ]
        );

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, $title);



        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $contentDataSaved->id,
                'title' => $title2,
            ])

        );

        $this->assertEquals(200, $response->status());
        $contentDataSaved = $response->getData()->data;

        $this->assertEquals($contentDataSaved->title, $title2);


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $contentDataSaved->id,
                'title' => 'new title',
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, 'new title');


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $contentDataSaved->id,
                'title' => '0',
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, 0);


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $contentDataSaved->id,
            ])

        );
        $this->assertEquals(302, $response->status());

    }

    public function testSaveContentFromController()
    {
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        $title = 'Test add content from api ' . rand();
        $title2 = 'Test update content from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.order.store'),
            [
                'title' => $title,
            ]
        );


        $this->assertEquals(201, $response->status());
        $contentData = $response->getData();
        $this->assertEquals($contentData->data->title, $title);

        $content_id = $contentData->data->id;


        $response = $this->call(
            'GET',
            route('api.order.show',
                [
                    'order' => $content_id,
                ])
        );

        $contentData = $response->getData();


        $this->assertEquals($contentData->data->title, $title);


        $response = $this->call(
            'PUT',
            route('api.order.update', [
                'order' => $content_id,
                'title' => $title2,
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

        $this->assertEquals($contentData->data->title, $title2);



        $response = $this->call(
            'GET',
            route('api.order.index',
                [
                ])
        );

        $contentData = $response->getData();
        $this->assertEquals(true,!empty($contentData->data));

    }

    public function testDeleteContentFromController()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $title = 'Test add menu from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.order.store'),
            [
                'title' => $title,
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
