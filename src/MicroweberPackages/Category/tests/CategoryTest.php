<?php

namespace MicroweberPackages\Category\tests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Category\Models\CategoryItem;
use MicroweberPackages\Category\PlainTextCategoriesSave;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\User\Models\User;


class ContentTestModelForCategories extends Model
{
    use CategoryTrait;

    protected $table = 'content';

}

class CategoryTest extends TestCase
{

    private function _assertCategoryRecursive($categoryTreeRendered, $array, $parentId = 0)
    {
        if (is_array($array)) {
            foreach ($array as $categoryName => $categoryChildren) {

                $this->assertTrue(str_contains($categoryTreeRendered, $categoryName));

                if (!empty($categoryChildren)) {
                    $this->_assertCategoryRecursive($categoryTreeRendered, $categoryChildren, $parentId);
                }
            }
        }
    }

    public function testRecusriveRender()
    {

        Content::truncate();
        Category::truncate();
        CategoryItem::truncate();


        $categoryLink = category_link(0);
        $this->assertFalse($categoryLink);

        Content::truncate();
        Category::truncate();
        CategoryItem::truncate();


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

        $newCategorySave = new PlainTextCategoriesSave();
        $newCategorySave->saveCategories($categoriesToSave, $mainCategoryId);

        $findCategoryProperties = Category::where('title', 'Properties')->first();
        $this->assertIsInt($findCategoryProperties->id);
        $this->assertIsInt($findCategoryProperties->position);
        $this->assertIsInt($findCategoryProperties->parent_id);
        $this->assertIsInt($findCategoryProperties->is_active);
        $this->assertEquals($findCategoryProperties->parent_id, $mainCategoryId);
        $this->assertEquals($findCategoryProperties->is_active, 1);

        $categoryTreeRendered = category_tree(['return_data' => true]);
        foreach ($categoriesToSave as $categoryTreePlain) {
            $categoriesToSave = app()->format->stringToTree($categoryTreePlain);
            $this->_assertCategoryRecursive($categoryTreeRendered, $categoriesToSave, $mainCategoryId);
        }


    }

    public function testRender()
    {
        Content::truncate();
        Category::truncate();
        CategoryItem::truncate();


        $categoryLink = category_link(0);
        $this->assertFalse($categoryLink);

        $pageTitle = 'my-new-page-' . uniqid();
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

        $subCategory = new Category();
        $subCategory->parent_id = $mainCategory->id;
        $subCategory->title = 'Category level 2' . uniqid();
        $subCategory->save();


        $categoryTitle = category_title($mainCategory->id);
        $this->assertEquals($mainCategory->title, $categoryTitle);

        $postTitle = 'my-new-post-' . uniqid();
        $post = new Post();
        $post->title = $postTitle;
        $post->content_type = 'post';
        $post->url = $postTitle;
        $post->category_ids = [$mainCategory->id, $subCategory->id];
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

        $this->assertTrue(str_contains($categoryTree, 'data-category-id'));
        $this->assertTrue(str_contains($categoryTree, $mainCategory->title));


        $categoryItemsTestGetFalse = get_category_items(99999);
        $this->assertFalse($categoryItemsTestGetFalse);


        $foundCat = false;
        $foundSubCat = false;
        $categoryItemsTestGet = get_category_items(false, 'content', $post->id);
        $this->assertEquals(2, count($categoryItemsTestGet));

        $categoryItemsTestGet2 = app()->category_repository->getItems(false, 'content', $post->id);
        $this->assertEquals($categoryItemsTestGet, $categoryItemsTestGet2);

        $this->assertIsArray($categoryItemsTestGet);
        $this->assertNotEmpty($categoryItemsTestGet);
        foreach ($categoryItemsTestGet as $item) {
            $this->assertIsArray($item);
            $this->assertArrayHasKey('parent_id', $item);
            $this->assertArrayHasKey('rel_id', $item);
            $this->assertArrayHasKey('rel_type', $item);
            $this->assertEquals($item['rel_id'], $post->id);

            if ($item['parent_id'] == $mainCategory->id) {
                $foundCat = true;
            }
            if ($item['parent_id'] == $subCategory->id) {
                $foundSubCat = true;
            }

        }
        $this->assertTrue($foundCat);
        $this->assertTrue($foundSubCat);


    }

    public function testAddcategoriesToModel()
    {
        $title = 'New cat for my custom model' . uniqid();

        $category = new Category();
        $category->title = $title;
        $category->save();

        $newPage = new ContentTestModelForCategories();
        $newPage->title = 'Content with cats ';
        $newPage->category_ids = $category->id;
        $newPage->save();

        $cat = $newPage->categories->first();

        $this->assertNotEmpty($cat);
        $this->assertEquals($cat->parent->title, $title);

    }

    public function testCategoriesSameSlug()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);


        $title = 'New cat for my slug test ' . uniqid();
        $title2 = 'New cat for my slug 2 ' . uniqid();
        $title3 = 'New cat for my slug 3 ' . uniqid();
        $slug = str_slug($title);
        $slug2 = str_slug($title2);
        $slug3 = str_slug($title3);

        $save = [];
        $save['title'] = $title;
        $save['url'] = $slug;

        //   $save1Id = save_category($save );
        $save1Id = app()->category_repository->save($save);
        $save1Get = get_category_by_id($save1Id);

        $this->assertNotNull($save1Get['updated_at']);
        $this->assertNotNull($save1Get['created_at']);
        $this->assertNotNull($save1Get['created_by']);
        $this->assertNotNull($save1Get['edited_by']);
        $this->assertNotNull($save1Get['title']);
        $this->assertNotNull($save1Get['url']);
        $this->assertEquals($save1Get['is_active'], 1);
        $this->assertEquals($save1Get['is_deleted'], 0);
        $this->assertEquals($save1Get['is_hidden'], 0);


        $this->assertTrue(is_int($save1Id));

        $save = [];
        $save['id'] = $save1Id;
        $save['url'] = $slug2;
        // $save2Id = save_category($save );
        $save2Id = app()->category_repository->save($save);
        $save2Get = get_category_by_id($save2Id);


        $this->assertEquals($save1Id, $save2Id);
        $this->assertEquals($save1Get["position"], $save2Get["position"]);
        $this->assertNotEquals($save1Get["url"], $save2Get["url"]);

        $save = [];
        $save['title'] = $title;
        $save['url'] = $slug2;
        $save3 = app()->category_repository->save($save);
        $save3Get = get_category_by_id($save3);

        $this->assertNotEquals($save3Get["url"], $save2Get["url"]);
        $this->assertNotEquals($save3Get["position"], $save2Get["position"]);


        // test if category_subtype_settings is saved as array
        $save = [];
        $save['title'] = $title3;
        $save['url'] = $slug3;
        $save['category_subtype_settings'] = ['test' => 'test'];
        $save4 = app()->category_repository->save($save);
        $save4Get = get_category_by_id($save4);
        $this->assertIsArray($save4Get['category_subtype_settings']);
        $this->assertEquals($save4Get['category_subtype_settings']['test'], 'test');


    }

    public function testCategoriesSlugToAcceptIdnChars()
    {
        Content::truncate();
        Category::truncate();
        CategoryItem::truncate();

        $slugs = [
            'category-1' => 'category-1',
            'категория-на-бг' => 'категория-на-бг',
            'категория-на-бг with spaces' => 'категория-на-бг-with-spaces',
            '网址别名' => '网址别名',
            'category with weird symbols !@#$%^&*()_+:?' => 'category-with-weird-symbols',
        ];

        foreach ($slugs as $slug => $expected) {
            $save = [];
            $save['title'] = $slug;
            $save['url'] = $slug;
            $saveId = app()->category_repository->save($save);
            $saveGet = get_category_by_id($saveId);
            $this->assertEquals($saveGet['url'], $expected);

            $findCategoryBySlug = get_categories('url=' . $expected . '&single=1');
            $this->assertEquals($saveGet['url'], $findCategoryBySlug['url']);
            $this->assertEquals($saveGet['id'], $findCategoryBySlug['id']);
        }
    }
    public function testCategoriesSameItemsIfSamePostIsSavedTwice()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);


        $save = [];
        $save['title'] = 'testCategoriesSameRelIdsIfSamePost-test';
        $savedId = app()->category_repository->save($save);

        $save = [];
        $save['title'] = 'testCategoriesSameRelIdsIfSamePost-test2';
        $savedId2 = app()->category_repository->save($save);

        $unique = uniqid('testSaveContentOnPage');
        $testPostData = [
            'subtype' => 'post',
            'content_type' => 'post',
            'title' => 'testCategoriesSameRelIdsIfSamePost' . $unique,
            'category_ids' => [$savedId,$savedId2],

        ];
        $newPostInCategoriesId = save_content($testPostData);

        $catItems = get_category_items($savedId);

        $testPostData['id'] = $newPostInCategoriesId;

        // save again and check if the items array is the same as before
        $newPostInCategoriesId2 = save_content($testPostData);

        $catItems2 = get_category_items($savedId);
        $this->assertEquals( $catItems,$catItems2);
        $this->assertEquals(  $newPostInCategoriesId,$newPostInCategoriesId2);


    }

}
