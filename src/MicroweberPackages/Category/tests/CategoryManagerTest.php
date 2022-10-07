<?php

namespace MicroweberPackages\Category\tests;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;


class CategoryManagerTest extends TestCase
{

    public function testSelectOnlyFieldsWithCategoryFilter()
    {

        $category = new Category();
        $category->title = 'New cat for my content to test filter';
        $category->save();


        $cont_title = 'Content with cats to test filter';
        $newPage = new Content();
        $newPage->title = $cont_title;
        $newPage->category_ids = $category->id;
        $newPage->save();

        $contentForCategories = get_content(array(
            "categories" => [$category->id],
            "fields" => 'title,id'
        ));

        //dd($contentForCategories);

        $this->assertEquals($cont_title, $contentForCategories[0]['title']);
        $this->assertEquals($newPage->id, $contentForCategories[0]['id']);
        $this->assertEquals(2, count($contentForCategories[0]));

        //delete from cat
        $newPage->category_ids = 0;
        $newPage->save();

        //check if deleted
        $contentForCategories = get_content(array(
            "categories" => [$category->id],
            "fields" => 'title,id'
        ));

        $this->assertEquals(null, $contentForCategories);

    }


    /*public function testCategoryTree()
    {

        $category1 = new Category();
        $category1->title = 'Cat level 1';
        $category1->data_type = 'category';
        $category1->save();

        $category2 = new Category();
        $category2->title = 'Cat level 2';
        $category2->data_type = 'category';

        $category2->parent_id =$category1->id;
        $category2->save();


        $tree = category_tree('use_cache=0&ul_class=test_ul_class&li_class=test_li_class&li_class_deep=test_li_class_deep&dsfreturn_data=1&all=1');
         var_dump($tree);
//        dump($category2);
//        var_dump($tree);
//        var_dump('sadsad');
       // $this->assertTrue(str_contains($tree,'test_ul_class'));

       // $this->assertTrue(str_contains($tree,'test_li_class'));



    }*/


    public function testCategoryInStockFilter()
    {

        $clean = Content::truncate();
        $clean = Product::truncate();

        // check when in stock

        $category = new Category();
        $category->title = 'New cat for my Product to test filter';
        $category->save();


        $cont_title = 'Product with cats to test filter';
        $newPage = new Product();
        $newPage->title = $cont_title;
        $newPage->is_active = 1;
        $newPage->is_deleted = 0;
        $newPage->content_type = 'product';
        $newPage->subtype = 'product';
        $newPage->setContentData(['qty'=>'1']);
        $newPage->category_ids = $category->id;
        $newPage->save();
        $content_data = content_data($newPage->id);
        $check  = app()->category_repository->hasProductsInStock($category->id);

        $this->assertTrue($check);
        $this->assertEquals($content_data['qty'],1);


        $check  = app()->category_repository->countProductsInStock($category->id);
        $this->assertEquals($check,1);



        // check when out of stock

        $category = new Category();
        $category->title = 'New cat for my Product to test filter';
        $category->save();


        $cont_title = 'Product with cats to test filter';
        $newPage = new Product();
        $newPage->title = $cont_title;
        $newPage->is_active = 1;
        $newPage->is_deleted = 0;
        $newPage->content_type = 'product';
        $newPage->subtype = 'product';
        $newPage->setContentData( ['qty'=>'0']);
        $newPage->category_ids = $category->id;
        $newPage->save();
        $content_data = content_data($newPage->id);

        $content2 = app()->content_repository->getContentData($newPage->id);

        $check  = app()->category_repository->hasProductsInStock($category->id);
        $this->assertFalse($check);
        $this->assertEquals($content_data['qty'],0);




    }
    public function testCategorySearchByKeyword()
    {
        $clean = Category::truncate();

        $category = new Category();
        $category->title = 'New cat testCategorySearchByKeyword'.uniqid();
        $category->save();


        $category2 = new Category();
        $category2->title = 'New cat2 testCategorySearchByKeyword'.uniqid();
        $category2->parent_id = $category->id;
        $category2->save();


        $get_categories_kw = get_categories(['keyword'=>$category2->title]);
        $this->assertEquals($get_categories_kw[0]['id'], $category2->id);

        $title = $category2->title;

        $params = [];
        $params['__query_test_if_callback_works'] = function ($query) use($title){
            return $query->whereIn('id', function ($subQuery)   use($title)  {
                $subQuery->select('categories.id');
                $subQuery->from('categories');
                $subQuery->where('categories.title', '=', $title);
            });
        };
        $get_categories_kw = get_categories($params);
        $this->assertEquals($get_categories_kw[0]['id'], $category2->id);


    }
    public function testCategoryJsonTreeAdmin()
    {

        $clean = Content::truncate();
        $clean = Category::truncate();

        $newSimplePage = new Page();
        $newSimplePage->title = 'testCategoryJsonTreeAdminPageStatic0_'.uniqid();
        $newSimplePage->content_type = 'page';
        $newSimplePage->subtype = 'static';
        $newSimplePage->save();


        $newBlogPage = new Page();
        $newBlogPage->title = 'testCategoryJsonTreeAdmin_'.uniqid();
        $newBlogPage->content_type = 'page';
        $newBlogPage->subtype = 'dynamic';
        $newBlogPage->save();



        $category = new Category();
        $category->title = 'New cat testCategoryJsonTreeAdmin_'.uniqid();
        $category->rel_type = 'content';
        $category->rel_id = $newBlogPage->id;
        $category->save();


        $category2 = new Category();
        $category2->title = 'New cat2 testCategoryJsonTreeAdmin_'.uniqid();
        $category2->parent_id = $category->id;
        $category2->save();

        $category3 = new Category();
        $category3->title = 'New cat3 testCategoryJsonTreeAdmin_'.uniqid();
        $category3->parent_id = $category2->id;
        $category3->save();

        $children_test = $category->children;

        $this->assertEquals($children_test[0]->id, $category2->id);
        $this->assertEquals($children_test[0]->title, $category2->title);
        $this->assertEquals($children_test[0]->parent_id, $category2->parent_id);

        $children_test = $category2->children;
        $this->assertEquals($children_test[0]->id, $category3->id);
        $this->assertEquals($children_test[0]->title, $category3->title);
        $this->assertEquals($children_test[0]->parent_id, $category3->parent_id);




        $jsonTree= app()->category_manager->get_admin_js_tree_json(['from_content_id'=>$newBlogPage->id]);

        $this->assertEquals($jsonTree[0]['id'], $newBlogPage->id);
        $this->assertEquals($jsonTree[0]['parent_type'], 'page');
        $this->assertEquals($jsonTree[0]['type'], 'page');
        $this->assertEquals($jsonTree[0]['parent_id'], 0);

        $this->assertEquals($jsonTree[1]['id'], $category->id);
        $this->assertEquals($jsonTree[1]['parent_type'], 'page');
        $this->assertEquals($jsonTree[1]['type'], 'category');
        $this->assertEquals($jsonTree[1]['parent_id'], $newBlogPage->id);

        $this->assertEquals($jsonTree[2]['id'], $category2->id);
        $this->assertEquals($jsonTree[2]['parent_type'], 'category');
        $this->assertEquals($jsonTree[2]['type'], 'category');
        $this->assertEquals($jsonTree[2]['parent_id'], $category->id);


        $this->assertEquals($jsonTree[3]['id'], $category3->id);
        $this->assertEquals($jsonTree[3]['parent_type'], 'category');
        $this->assertEquals($jsonTree[3]['type'], 'category');
        $this->assertEquals($jsonTree[3]['parent_id'], $category2->id);



        $jsonTreeKw= app()->category_manager->get_admin_js_tree_json(['keyword'=>$newBlogPage->title]);

        $this->assertEquals($jsonTreeKw[0]['id'], $newBlogPage->id);
        $this->assertEquals($jsonTreeKw[0]['parent_type'], 'page');
        $this->assertEquals($jsonTreeKw[0]['type'], 'page');
        $this->assertEquals($jsonTreeKw[0]['parent_id'], 0);


        $get_content_kw = get_content(['keyword'=>$newBlogPage->title]);
        $this->assertEquals($get_content_kw[0]['id'], $newBlogPage->id);


        $zip = new \ZipArchive();
        $zip->open(__DIR__.'/../../Helper/tests/misc/xss-test-files.zip');
        $xssList = $zip->getFromName('xss-payload-list.txt');
        $zip->close();
        $xssList = preg_replace('~\R~u', "\r\n", $xssList);

        $xssList = explode(PHP_EOL, $xssList);




        $jsonTreeKw= app()->category_manager->get_admin_js_tree_json(['keyword'=>implode("\n", $xssList)]);
        $this->assertEmpty($jsonTreeKw);



        $jsonTreeKw= get_content(['keyword'=>implode("\n", $xssList)]);
        $this->assertEmpty($jsonTreeKw);


        $jsonTreeKw = app()->category_repository->getByParams(['keyword'=>implode("\n", $xssList)]);
        $this->assertEmpty($jsonTreeKw);


        $xssList = array_slice($xssList, 0, 10);

        foreach ($xssList as $xss) {
            $jsonTreeKw = app()->category_repository->getByParams(['parent_id' => 'non-exising', 'keyword' => $xss]);
            $this->assertEmpty($jsonTreeKw);


            $jsonTreeKw = get_categories(['parent_id' => 'non-exising', 'keyword' => $xss]);
            $this->assertEmpty($jsonTreeKw);

            $get_content_kw = get_content(['parent' => 'non-exising','keyword'=>$xss]);
            $this->assertEmpty($get_content_kw);

        }

    }

}
