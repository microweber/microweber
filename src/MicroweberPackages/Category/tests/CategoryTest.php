<?php

namespace MicroweberPackages\Media\tests;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;


class ContentTestModelForCategories extends Model
{
    use CategoryTrait;

    protected $table = 'content';

}

class CategoryTest extends TestCase
{
    public function testRender()
    {
        Content::truncate();
        Category::truncate();
        CategoryItem::truncate();
        clearcache();

        $categoryLink = category_link(0);
        $this->assertFalse($categoryLink);

        $pageTitle = 'my-new-page-'.uniqid();
        $page = new Page();
        $page->title = $pageTitle;
        $page->content_type = 'page';
        $page->url = $pageTitle;
        $page->subtype = 'dynamic';
        $page->save();

        $mainCategory = new Category();
        $mainCategory->parent_id = 0;
        $mainCategory->rel_id = $page->id;
        $mainCategory->rel_type = 'content';
        $mainCategory->title = 'Category level 1' . uniqid();
        $mainCategory->save();

        $categoryTitle = category_title($mainCategory->id);
        $this->assertEquals($mainCategory->title, $categoryTitle);

        $postTitle = 'my-new-post-'.uniqid();
        $post = new Post();
        $post->title = $postTitle;
        $post->content_type = 'post';
        $post->url = $postTitle;
        $post->category_ids = $mainCategory->id;
        $post->save();

        $categoryItems = get_category_items($mainCategory->id);
        $categoryItemsCount = get_category_items_count($mainCategory->id);

        $this->assertEquals(1, $categoryItemsCount);

        $this->assertEquals(1, count($categoryItems));
        $this->assertEquals($mainCategory->id, $categoryItems[0]['parent_id']);
        $this->assertEquals('content', $categoryItems[0]['rel_type']);
        $this->assertEquals($post->id, $categoryItems[0]['rel_id']);

        $options = array();
        $options['rel_id'] = $page->id;
        $options['rel_type'] = 'content';
        $options['active_ids'] = $mainCategory->id;
        $options['use_cache'] = false;
        $options['return_data'] = 1;
        $categoryTree = category_tree($options);

        $this->assertTrue(str_contains($categoryTree,'data-category-id'));
        $this->assertTrue(str_contains($categoryTree,$mainCategory->title));


    }

    public function testAddcategoriesToModel()
    {

        $title = 'New cat for my custom model'.uniqid();

        $category = new Category();
        $category->title = $title;
        $category->save();



        $newPage = new ContentTestModelForCategories();
        $newPage->title = 'Content with cats ';

         $newPage->category_ids = $category->id;

//       $newPage->setCategories  (['kotka', 'horo']);
//
//        $newPage->setCategory([
//              'title' => 'kotka',
//              'url' => 'kotka-slug'
//        ]);

        $newPage->save();

        $cat = $newPage->categories->first();

        $this->assertNotEmpty($cat );
        $this->assertEquals($cat->parent->title,$title );

    }

}
