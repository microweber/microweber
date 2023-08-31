<?php

namespace MicroweberPackages\Media\tests;


use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Core\tests\TestCase;

use MicroweberPackages\User\Models\User;
use Illuminate\Support\Facades\Auth;

class CategoryApiControllerTest extends TestCase
{
    public function testAddCategoriesFromController()
    {

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $title = 'category controller test ! - ' . rand();
        $title2 = 'category controller test2 ! - ' . rand();


        $contentData = [
            'label' => 'test',
        ];


        $response = $this->postJson(
            route('api.category.store'),
            [
                'title' => $title,
                'description' => $title,
                'content_data' => $contentData
            ]
        );

        $this->assertEquals(201, $response->status());

        $categorySaved = $response->getData()->data;

        $this->assertEquals($categorySaved->title, $title);
        $this->assertEquals($categorySaved->description, $title);

        $categorySaved = Category::where('id', $categorySaved->id)->first();
        $this->assertEquals($categorySaved->title, $title);
        $this->assertEquals($categorySaved->description, $title);

        $find = Category::find($categorySaved->id);
        $this->assertEquals($find->contentData[0]->field_name, 'label');
        $this->assertEquals($find->contentData[0]->field_value, 'test');

        $response = $this->call(
            'PUT',
            route('api.category.update', [
                'category' => $categorySaved->id,
                'title' => $title2,
            ])
        );

        $this->assertEquals(200, $response->status());
        $categorySaved = $response->getData()->data;

        $this->assertEquals($categorySaved->title, $title2);

        $response = $this->call(
            'GET',
            route('api.category.show', [
                'category' => $categorySaved->id,
            ])
        );
        $this->assertEquals(200, $response->status());

        $categorySaved = $response->getData()->data;
        $this->assertEquals($categorySaved->title, $title2);

        $response = $this->call(
            'DELETE',
            route('api.category.destroy', [
                'category' => $categorySaved->id,
            ])

        );

        $this->assertEquals(200, $response->status());

    }

}
