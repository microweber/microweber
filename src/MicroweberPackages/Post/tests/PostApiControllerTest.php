<?php
namespace MicroweberPackages\Post\tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class PostApiControllerTest extends TestCase
{
    public function testAddContentFull()
    {
        $categoryIds = [];

        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);


        $category = new Category();
        $category->title = 'New cat for my custom model'. rand();
        $category->save();
        $categoryIds[] = $category->id;

        $category = new Category();
        $category->title = 'New cat for my custom model'. rand();
        $category->save();
        $categoryIds[] = $category->id;

        $title = 'Iphone and spire 4ever! - '. rand();
        $title2 = 'Iphone and spire 4ever! 2 - '. rand();
        $contentBody = 'This is my cool content description.';


        $response = $this->call(
            'POST',
            route('api.post.store'),
            [
                'title' => '       '.$title .'       ', //test if the space is removed
                'category_ids'=>implode(',', $categoryIds),
                'content_body' => $contentBody,
            ]
        );

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, $title);



        $response = $this->call(
            'PUT',
            route('api.post.update', [
                'post' => $contentDataSaved->id,
                'title' => '       '.$title2 .'       ', //test if the space is removed
            ])

        );

        $this->assertEquals(200, $response->status());
        $contentDataSaved = $response->getData()->data;

        $this->assertEquals($contentDataSaved->title, $title2);


        $response = $this->call(
            'PUT',
            route('api.post.update', [
                'post' => $contentDataSaved->id,
                'title' => 'new title',
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, 'new title');


        $response = $this->call(
            'PUT',
            route('api.post.update', [
                'post' => $contentDataSaved->id,
                'title' => 'your post title',
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, 'your post title');


        $response = $this->call(
            'PUT',
            route('api.post.update', [
                'post' => $contentDataSaved->id,
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
            route('api.post.store'),
            [
                'title' => $title,
                'content_body' => '<b>Bold text</b>',
                'content' => '<b onmouseover=alert(‘XSS testing!‘)>XSS</b>   <IMG SRC=j&#X41vascript:alert(\'test2\')>'
            ]
        );


        $this->assertEquals(201, $response->status());
        $contentData = $response->getData();
        $this->assertEquals($contentData->data->title, $title);

        $content_id = $contentData->data->id;


        $response = $this->call(
            'GET',
            route('api.post.show',
                [
                    'post' => $content_id,
                ])
        );

        $contentData = $response->getData();


        $this->assertEquals($contentData->data->title, $title);


        $response = $this->call(
            'PUT',
            route('api.post.update', [
                'post' => $content_id,
                'title' => $title2,
            ])

        );

        $this->assertEquals(200, $response->status());

        $response = $this->call(
            'GET',
            route('api.post.show',
                [
                    'post' => $content_id,
                ])
        );

        $contentData = $response->getData();

        $this->assertEquals($contentData->data->title, $title2);



        $response = $this->call(
            'GET',
            route('api.post.index',
                [
                ])
        );

        $contentData = $response->getData();
        $this->assertEquals(true,!empty($contentData->data));

    }

    public function testDestroyContentFromController()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $title = 'Test add content from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.post.store'),
            [
                'title' => $title,
            ]
        );


        $response = $this->call(
            'DELETE',
            route('api.post.destroy', [
                'post' => $response->getData()->data->id,
            ])
        );

        $this->assertEquals(200, $response->status());
        $contentData = $response->getData()->data->id;

        $this->assertNotEmpty($contentData);
    }
}
