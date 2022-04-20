<?php
namespace MicroweberPackages\Content\tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;

class ContentApiControllerTest extends TestCase
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
            route('api.content.store'),
            [
                'title' => $title,
                'category_ids'=>implode(',', $categoryIds),
                'content_body' => $contentBody,
                'content' => '',
            ]
        );

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, $title);



        $response = $this->call(
            'PUT',
            route('api.content.update', [
                'content' => $contentDataSaved->id,
                'title' => $title2,
            ])

        );

        $this->assertEquals(200, $response->status());
        $contentDataSaved = $response->getData()->data;

        $this->assertEquals($contentDataSaved->title, $title2);


        $response = $this->call(
            'PUT',
            route('api.content.update', [
                'content' => $contentDataSaved->id,
                'title' => 'new title',
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, 'new title');


        $response = $this->call(
            'PUT',
            route('api.content.update', [
                'content' => $contentDataSaved->id,
                'title' => '0',
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
        $this->assertEquals($contentDataSaved->title, 0);


        $response = $this->call(
            'PUT',
            route('api.content.update', [
                'content' => $contentDataSaved->id,
            ])

        );
        $this->assertEquals(200, $response->status());

        $contentDataSaved = $response->getData()->data;
    }

    public function testSaveContentFromController()
    {
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        $title = 'Test add content from api ' . rand();
        $title2 = 'Test update content from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.content.store'),
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
            route('api.content.show',
                [
                    'content' => $content_id,
                ])
        );

        $contentData = $response->getData();


        $this->assertEquals($contentData->data->title, $title);
        $this->assertFalse(str_contains($contentData->data->content, 'onmouseover'));
        $this->assertFalse(str_contains($contentData->data->content, 'XSS testing'));


        $response = $this->call(
            'PUT',
            route('api.content.update', [
                'content' => $content_id,
                'title' => $title2,
            ])

        );

        $this->assertEquals(200, $response->status());

        $response = $this->call(
            'GET',
            route('api.content.show',
                [
                    'content' => $content_id,
                ])
        );

        $contentData = $response->getData();

        $this->assertEquals($contentData->data->title, $title2);



        $response = $this->call(
            'GET',
            route('api.content.index',
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

        $title = 'Test add content from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.content.store'),
            [
                'title' => $title,
            ]
        );


        $response = $this->call(
            'DELETE',
            route('api.content.destroy', [
                'content' => $response->getData()->data->id,
            ])
        );

        $this->assertEquals(200, $response->status());
        $contentData = $response->getData()->data->id;

        $this->assertNotEmpty($contentData);
    }




}
