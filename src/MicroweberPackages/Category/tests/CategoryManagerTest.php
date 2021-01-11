<?php
namespace MicroweberPackages\Category\tests;

use MicroweberPackages\Content\Content;
use MicroweberPackages\Core\tests\TestCase;

use MicroweberPackages\Category\Models\Category;


class CategoryManagerTest extends TestCase
{
    public function testCategoryTree()
    {
       // $tree = category_tree('all=1');
    }



    public function testSelectOnlyFieldsWithCategoryFilter()
    {

        $category = new Category();
        $category->title = 'New cat for my content to test filter';
        $category->save();


        $cont_title =  'Content with cats to test filter';
        $newPage = new Content();
        $newPage->title =$cont_title;
        $newPage->category_ids = $category->id;
        $newPage->save();


        $contentForCategories = get_content(array(
            "categories"=>[$category->id],
            "fields"=>'title,id'
        ));

        $this->assertEquals($cont_title, $contentForCategories[0]['title']);
        $this->assertEquals($newPage->id, $contentForCategories[0]['id']);
        $this->assertEquals(2,count( $contentForCategories[0]));


    }



}