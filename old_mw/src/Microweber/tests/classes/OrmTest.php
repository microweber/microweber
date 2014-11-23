<?php

namespace ClassTest;


class OrmTest extends \PHPUnit_Framework_TestCase
{
    protected $cleanup;

    public function testBasic()
    {

        $content = mw()->orm->get('content', 'limit=2');
        $content2 = mw()->orm->get('content', 'limit=2&offset=2');

        $this->assertEquals(true, ($content) != $content2);
        $this->assertEquals(2, count($content));
        $this->assertEquals(2, count($content2));

    }

    public function testCustomFields()
    {

        $par = array();
        $par['custom_fields.custom_field_type'] = 'price';
        $par['limit'] = 10;

        $content = mw()->orm->get('content', $par);
        $this->assertEquals(true, !empty($content));


        $par = array();
        $par['custom_fields.custom_field_type'] = 'price';
        $par['custom_fields.num_value'] = '>1000';
        $par['limit'] = 10;

        $content = mw()->orm->get('content', $par);
        $this->assertEquals(true, !empty($content));


        $par = array();
        $par['custom_fields.custom_field_type'] = 'price';
        $par['custom_fields.num_value'] = '>1000';
        $par['custom_fields.num_value.1'] = '<1500';
        $par['limit'] = 10;

        $content = mw()->orm->get('content', $par);

        $this->assertEquals(true, !empty($content));
    }

    public function testCustomFieldsColors()
    {

        $par = array();

        $par['custom_fields.custom_field_name'] = 'colors';
        $par['custom_fields.custom_field_values_plain'] = '%red%';
        $par['limit'] = 10;

        $content = mw()->orm->get('content', $par);

        $this->assertEquals(true, !empty($content));


    }

    public function testCategories()
    {

        $par = array();
        $par['categories_items.id'] = true;
        $content = mw()->orm->get('content', $par);
        $par['limit'] = 10;

        $this->assertEquals(true, !empty($content));

    }

    public function testAdvancedParams()
    {

        $par = array();

        $par['limit'] = 10;
        $par['custom_fields.custom_field_type'] = 'price';
        $par['custom_fields.custom_field_value'] = '>1000';
        $par['custom_fields.custom_field_value.1'] = '<1500';

        $content = mw()->orm->get('content', $par);

         $this->assertEquals(true, !empty($content));

    }

    protected function setUp()
    {
        $this->cleanup = array();

        $title = "Test SHOP ORM" . rand();
        $params = array(
            'title' => $title,
            'content_type' => 'page',
            'subtype' => 'page',
            'is_shop' => 'y',
            // 'debug' => true,

            'is_active' => 'y');

        $parent_shop = mw('content')->save($params);
        $this->cleanup[] = $parent_shop;

        for ($x = 0; $x <= 20; $x++) {
            $title = "Test product ORM" . rand();
            $price = rand(1000, 2000);
            $category = 'Test Category ' . rand(1000, 2000);
            $colors = array('Red', 'Blue', 'Green', 'Orange', 'Black', 'Yellow', 'Pink', 'White');
            shuffle($colors);
            $colors = array_slice($colors, 3);

            $params = array(
                'title' => $title,
                'content_type' => 'post',
                'subtype' => 'product',
                'custom_field_price' => $price,
                'custom_field_colors' => $colors,
                'parent' => $parent_shop,
                'categories' => $category,
                'is_active' => 'y');

            $content = mw('content')->save($params);
            $this->cleanup[] = $content;
            $this->assertEquals(true, intval($content) > 0);
        }

        $this->assertEquals(true, intval($parent_shop) > 0);
    }

    protected function tearDown()
    {
        foreach ($this->cleanup as $item) {
            $del_content = array('id' => $item, 'forever' => true);
            $delete = mw('content')->delete($del_content);
            $this->assertEquals(true, is_array($delete));
        }
    }
}

//
//$par = array();
//$par['custom_fields.custom_field_type'] = 'price';
//$par['custom_fields.custom_field_value'] = '75555';
//
//
//// $content = mw()->orm->get('content',$par);
//// print_r(mw()->orm->getLastQuery());
////d($content);
////d(count($content));
//
//$par = array();
////    $par['custom_fields.custom_field_type'] = 'price';
////    $par['custom_fields.custom_field_value'] = '75555';
//$pdar['custom_fields.custom_field_type'] = array('
//    (custom_field_type = ?
//    OR custom_field_value = ?)
//    ', array('price', 75555));
//$par['custom_fields.get_color'] = array('
//    (custom_field_name = ?
//    and custom_field_values_plain LIKE ?)
//    ', array('color', '%red%'));
//
//// $content = mw()->orm->get('content',$par);
//// print_r(mw()->orm->getLastQuery());
////d(count($content));
//
//
//
//
//
//
//
//$par = array();
//$par['custom_fields.custom_field_type'] = 'price';
////  $par['custom_fields.custom_field_values_plain'] = '>575550006';
//$par['custom_fields.custom_field_num_value'] = '>=101.7471';
//// $par['comments.rel_id'] = 'content.id';
//
//
////$content = mw()->orm->get('content',$par);
////   print_r(mw()->orm->getLastQuery());
////d($content);
//// d(count($content));
//
//
//
//
//
//$par = array();
//$par['content_type'] = 'post';
//$par['custom_fields.custom_field_type'] = 'price';
////  $par['custom_fields.custom_field_values_plain'] = '>575550006';
//$par['custom_fields.num_value'] = '>=101.7471';
//// $par['comments.id'] = '>1';
//// $par['comments.id'] = true;
//$par['order_by'] = 'custom_fields.num_value asc';
////  $par['count'] = 'custom_fields.num_value asc';
//
//// $content = mw()->orm->get('content',$par);
//// print_r(mw()->orm->getLastQuery());
//// d($content);
////  d(count($content));
//
//
//
//
//$par = array();
//$par['content.title'] = '!%Dell%';
//$par['content.title.2'] = '!%Link%';
//$par['content.title.2'] = '%inkjet%';
////$par['content.title.4'] = '%Intel%';
//$par['content.content_type'] = 'post';
//$par['custom_fields.custom_field_type'] = 'price';
////  $par['custom_fields.custom_field_values_plain'] = '>575550006';
//$par['custom_fields.num_value'] = '>=11.7471';
//$par['custom_fields.num_value.2'] = '<1001.7471';
//$par['limit'] = 1;
//$par['offset'] = 2;
//// $par['comments.id'] = '>1';
//// $par['comments.id'] = true;
//// $par['order_by'] = 'custom_fields.num_value asc';
////  $par['count'] = 'custom_fields.num_value asc';
//
////     $content = mw()->orm->get('content',$par);
////     print_r(mw()->orm->getLastQuery());
////      d($content);
////  d(count($content));
//
//
//
//
//
//$par = array();
//$par['content.title'] = '!%Dell%';
//$par['content.title.2'] = '!%Link%';
//$par['content.title.2'] = '%inkjet%';
////$par['content.title.4'] = '%Intel%';
//$par['content.content_type'] = 'post';
//$par['content.subtype_value'] = 'is_null';
//$par['custom_fields.custom_field_type'] = 'price';
////  $par['custom_fields.custom_field_values_plain'] = '>575550006';
////$par['custom_fields.num_value'] = '>=11.7471';
////$par['custom_fields.num_value.2'] = '<1001.7471';
////$par['limit'] = 1;
//// $par['offset'] = 2;
//// $par['comments.id'] = '>1';
//// $par['comments.id'] = true;
//// $par['order_by'] = 'custom_fields.num_value asc';
////  $par['count'] = 'custom_fields.num_value asc';
//
////    $content = mw()->orm->get('content',$par);
////    print_r(mw()->orm->getLastQuery());
////    d($content);
//
//
//
////    $par = array();
//////    $par['content.title'] = '!%Dell%';
//////    $par['content.title.2'] = '!%Link%';
//////    $par['content.title.2'] = '%inkjet%';
////    //$par['content.title.4'] = '%Intel%';
////    $par['content.content_type'] = 'post';
////    $par['content.subtype_value'] = 'is_null';
////    $par['custom_fields.custom_field_type'] = 'price';
////    //  $par['custom_fields.custom_field_values_plain'] = '>575550006';
////    //$par['custom_fields.num_value'] = '>=11.7471';
////    $par['custom_fields.num_value.2'] = '>=101.7471';
////    // $par['max'] = 'custom_fields.num_value';
////    // $par['offset'] = 2;
////    // $par['comments.id'] = '>1';
////    // $par['comments.id'] = true;
////    // $par['order_by'] = 'custom_fields.num_value asc';
////    //  $par['count'] = 'custom_fields.num_value asc';
////
////    $content = mw()->orm->get('content',$par);
////    print_r(mw()->orm->getLastQuery());
////    d($content);
//
//
//
//$par = array();
//$par['title'] = '!%Dell%';
//$par['content.title.2'] = '!%Link%';
////    $par['content.title.2'] = '%inkjet%';
////$par['content.title.4'] = '%Intel%';
//$par['content.content_type'] = 'post';
//$par['content.subtype_value'] = 'is_null';
//$par['custom_fields.custom_field_type'] = 'price';
////  $par['custom_fields.custom_field_values_plain'] = '>575550006';
////$par['custom_fields.num_value'] = '>=11.7471';
//$par['custom_fields.num_value.2'] = '>=101.7471';
//// $par['max'] = 'custom_fields.num_value';
//// $par['offset'] = 2;
//// $par['comments.id'] = '>1';
//// $par['comments.id'] = true;
//// $par['order_by'] = 'custom_fields.num_value asc';
////  $par['count'] = 'custom_fields.num_value asc';
//
//$content = mw()->orm->get('content',$par);
//print_r(mw()->orm->getLastQuery());
//d($content);
