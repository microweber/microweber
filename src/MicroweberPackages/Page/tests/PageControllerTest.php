<?php

namespace MicroweberPackages\Page\tests;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use Illuminate\Support\Facades\Auth;

class PageControllerTest extends TestCase
{

    public function testAddPageFull()
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
        $contentBody = 'This is my cool page description.';

        $price = rand(111,999);

        $contentData = [
          'fanta'=>'cocacolla',
        ];

        $customFields = [
          'fb'=>$price
        ];

        $response = $this->call(
            'POST',
            route('api.page.store'),
            [
                'title' => $title,
                'category_ids'=>implode(',', $categoryIds),
                'content_body' => $contentBody,
                'content' => '',
                'custom_fields'=>$customFields,
                'content_data'=>$contentData
            ]
        );

        $pageDataSaved = $response->getData()->data;
        $this->assertEquals($pageDataSaved->title, $title);

        $response = $this->call(
            'PUT',
            route('api.page.update', [
                'page' => $pageDataSaved->id,
                'title' => $title2,
            ])

        );

        $this->assertEquals(200, $response->status());
        $pageDataSaved = $response->getData()->data;

        $this->assertEquals($pageDataSaved->title, $title2);

    }

    public function testSavePageFromController()
    {
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        $title = 'Test add page from api ' . rand();
        $title2 = 'Test update page from api ' . rand();

        $response = $this->call(
            'POST',
            route('api.page.store'),
            [
                'title' => $title,
                'content_body' => '<b>Bold text</b>',
                'content' => '<b onmouseover=alert(‘XSS testing!‘)>XSS</b>   <IMG SRC=j&#X41vascript:alert(\'test2\')>'
            ]
        );


        $this->assertEquals(201, $response->status());
        $pageData = $response->getData();
        $this->assertEquals($pageData->data->title, $title);

        $pageId = $pageData->data->id;


        $response = $this->call(
            'GET',
            route('api.page.show',
                [
                    'page' => $pageId,
                ])
        );

        $pageData = $response->getData();

        $this->assertEquals($pageData->data->title, $title);

        $response = $this->call(
            'PUT',
            route('api.page.update', [
                'page' => $pageId,
                'title' => 'maiko maiko'
            ])

        );

        $this->assertEquals(200, $response->status());


        $response = $this->call(
            'GET',
            route('api.page.show',
                [
                    'page' => $pageId,
                ])
        );

        $pageData = $response->getData();

        $this->assertEquals($pageData->data->title, 'maiko maiko');

    }

}
