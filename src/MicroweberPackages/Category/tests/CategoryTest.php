<?php

namespace MicroweberPackages\Category\tests;

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

    private function _addCategory($title, $parentId = 0)
    {
        $findOrNeCategoryQuery = Category::query();
        $findOrNeCategoryQuery->where('title', $title);
        if ($parentId > 0) {
            $findOrNeCategoryQuery->where('parent_id', $parentId);
        }
        $findOrNeCategory = $findOrNeCategoryQuery->first();

        if ($findOrNeCategory == null) {
            $findOrNeCategory = new Category();
        }

        $findOrNeCategory->title = $title;

        if ($parentId > 0) {
            $findOrNeCategory->parent_id = $parentId;
        }

        $findOrNeCategory->save();

        return $findOrNeCategory->id;
    }

    private function _addCategoryRecursive($array, $parentId = 0)
    {
        if (is_array($array)) {
            foreach ($array as $categoryName=>$categoryChildren) {
                $parentId = $this->_addCategory($categoryName, $parentId);
                if (!empty($categoryChildren)) {
                    $this->_addCategoryRecursive($categoryChildren, $parentId);
                }
            }
        }
    }

    public function testRecusriveRender()
    {
        Content::truncate();
        Category::truncate();
        CategoryItem::truncate();
        clearcache();

        $categoryLink = category_link(0);
       //  $this->assertFalse($categoryLink);


        Content::truncate();
        Category::truncate();
        CategoryItem::truncate();
        clearcache();

        $page = new Page();
        $page->title = 'Blog';
        $page->content_type = 'page';
        $page->url = 'blog';
        $page->subtype = 'dynamic';
        $page->save();
        $blogId = $page->id;

        $category = new Category();
        $category->title = 'Categories';
        $category->rel_type = 'content';
        $category->rel_id = $page->id;
        $category->parent_id = 0;
        $category->save();
        $mainCategoryId = $category->id;

        $categoriesToSave = [];
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Dragalevci';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Mladost';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Nadejda';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Center';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Center > Market > Shoes';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Center > Market > Clothes';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Center > Market > Clocks';
        $categoriesToSave[] = 'Properties > Locations > City > Sofia > Center > Market > Machine';

        $categoriesToSave[] = 'Properties > Locations > City > Haskovo > Kenana';
        $categoriesToSave[] = 'Properties > Locations > City > Haskovo > Rakovska';
        $categoriesToSave[] = 'Properties > Locations > City > Haskovo > Bqlo more';
        $categoriesToSave[] = 'Properties > Locations > City > Haskovo > Center';
        $categoriesToSave[] = 'Properties > Locations > City > Haskovo > Center > Market > Shoes';
        $categoriesToSave[] = 'Properties > Locations > City > Haskovo > Center > Market > Clothes';
        $categoriesToSave[] = 'Properties > Locations > City > Haskovo > Center > Market > Clocks';
        $categoriesToSave[] = 'Properties > Locations > City > Haskovo > Center > Market > Machine';

        $categoriesToSave[] = 'Properties > Locations > City > Plovdiv > Qvor';
        $categoriesToSave[] = 'Properties > Locations > City > Plovdiv > Georgi Qnakiev';
        $categoriesToSave[] = 'Properties > Locations > City > Plovdiv > Asen II';
        $categoriesToSave[] = 'Properties > Locations > City > Plovdiv > Center';
        $categoriesToSave[] = 'Properties > Locations > City > Plovdiv > Center > Market > Shoes';
        $categoriesToSave[] = 'Properties > Locations > City > Plovdiv > Center > Market > Clothes';
        $categoriesToSave[] = 'Properties > Locations > City > Plovdiv > Center > Market > Clocks';
        $categoriesToSave[] = 'Properties > Locations > City > Plovdiv > Center > Market > Machine';

        foreach ($categoriesToSave as $categoryTreePlain) {
            $categoriesToSave = stringToTree($categoryTreePlain);
            $this->_addCategoryRecursive($categoriesToSave, $mainCategoryId);
        }

    }

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
        $newPage->save();

        $cat = $newPage->categories->first();

        $this->assertNotEmpty($cat );
        $this->assertEquals($cat->parent->title,$title );

    }

}
