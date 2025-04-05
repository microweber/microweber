<?php

namespace Modules\Category\Tests\Unit;


use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use Modules\Category\Models\Category;

class CategoryApiControllerTest extends TestCase
{
    public function testAddCategoriesFromController()
    {

      $this->loginAsAdmin();

        $title = 'category controller test ! - ' . rand();
        $title2 = 'category controller test2 ! - ' . rand();
        $random = rand();

        $contentData = [
            'label' => 'test',
            'random' => $random,
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
        $this->assertEquals($find->contentData['label'], 'test');
        $this->assertEquals($find->contentData['random'], $random);

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
