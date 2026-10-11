<?php

namespace Modules\Category\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;


use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;
use Modules\Category\Models\Category;

class CategoryApiControllerTest extends TestCase
{
    #[Test]
    public function it_add_categories_from_controller(): void {

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

    #[Test]
    public function it_prevents_moving_a_category_into_itself_or_a_descendant(): void
    {
        /** @var \Modules\Category\Repositories\CategoryRepositoryApi $repo */
        $repo = app(\Modules\Category\Repositories\CategoryRepositoryApi::class);

        // Build a chain A -> B -> C
        $a = Category::create(['title' => 'A move ' . rand(), 'parent_id' => 0]);
        $b = Category::create(['title' => 'B move ' . rand(), 'parent_id' => $a->id]);
        $c = Category::create(['title' => 'C move ' . rand(), 'parent_id' => $b->id]);

        // Into itself -> blocked, parent unchanged
        $repo->moveBulk([$a->id], [$a->id], false);
        $this->assertEquals(0, (int) $a->fresh()->parent_id, 'A must not become its own child');

        // Into a descendant (C) -> blocked, and the subtree is not reset
        $result = $repo->moveBulk([$a->id], [$c->id], false);
        $this->assertContains($a->id, $result['blocked']);
        $this->assertEquals(0, $result['moved']);
        $this->assertEquals(0, (int) $a->fresh()->parent_id, 'A must not move under its descendant');
        $this->assertEquals($b->id, (int) $c->fresh()->parent_id, 'C parent must be untouched');

        // A valid move (C under A) still works, without disturbing B
        $valid = $repo->moveBulk([$c->id], [$a->id], false);
        $this->assertEquals(1, $valid['moved']);
        $this->assertEquals($a->id, (int) $c->fresh()->parent_id);
        $this->assertEquals($a->id, (int) $b->fresh()->parent_id, 'B parent must be untouched');

        $c->delete();
        $b->delete();
        $a->delete();
    }

}
