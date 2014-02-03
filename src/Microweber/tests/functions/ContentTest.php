<?php

namespace FunctionsTest;


class ContentTest extends \PHPUnit_Framework_TestCase
{

    function __construct()
    {
        //  cache_clear('db');
        // mw('content')->db_init();
    }

    public function testPosts()
    {

        $params = array(
            'title' => 'this-is-my-test-post',
            'content_type' => 'post',
            // 'debug' => 1,
            'is_active' => 'y');
        //saving
        $save_post = save_content($params);

        //getting
        $get_post = get_content($params);





        //delete content
        foreach ($get_post as $item) {
            $del_params = array('id' => $item['id'], 'forever' => true);
            $delete = delete_content($del_params);


            //check if deleted
            $content = get_content_by_id($item['id']);


            //PHPUnit
            $this->assertEquals(true, is_array($delete));
            $this->assertEquals(false, $content);
        }



        //PHPUnit
        $this->assertEquals(true, $save_post);
        $this->assertEquals(true, is_array($get_post));

    }

    public function testPages()
    {
        $params = array(
            'title' => 'My test page',
            'content_type' => 'page',
            // 'debug' => 1,
            'is_active' => 'y');
        //saving
        $parent_page = save_content($params);

        $page_link = content_link($parent_page);


        $params = array(
            'title' => 'My test sub page',
            'content_type' => 'page',
            'parent' => $parent_page,
            // 'debug' => 1,
            'is_active' => 'y');
        $sub_page = save_content($params);

        //getting
        $params = array(
            'parent' => $parent_page,
            'content_type' => 'page',
            'single' => true,
            // 'debug' => 1,
            'is_active' => 'y');
        $get_sub_page = get_content($params);

        $sub_page_parents = content_parents($get_sub_page['id']);


        //clean
        $delete_parent = delete_content($parent_page);
        $delete_sub_page = delete_content($sub_page);


        //PHPUnit
        $this->assertEquals(true, in_array($parent_page,$sub_page_parents));
        $this->assertEquals(true, strval($page_link) != '');
        $this->assertEquals(true, intval($parent_page) > 0);
        $this->assertEquals(true, intval($sub_page) > 0);
        $this->assertEquals(true, is_array($get_sub_page));
        $this->assertEquals(true, is_array($delete_parent));
        $this->assertEquals(true, is_array($delete_sub_page));

    }




    public function testContentCategories()
    {

        $params = array(
            'title' => 'My categories page',
            'content_type' => 'page',
            'subtype' => 'dynamic',
            // 'debug' => 1,
            'is_active' => 'y');

        //saving
        $parent_page_id = save_content($params);

        $parent_page_data = get_content_by_id($parent_page_id);


        $params = array(
            //  'id' => '0',
            'title' => 'Test Category 1',
            'parent_page' => $parent_page_id
        );

        //saving
        $category_id = save_category($params);

        $category_data = get_category_by_id($category_id);
        $category_page = get_page_for_category($category_data['id']);

        $delete_category = delete_category($category_id);
        $delete_page = delete_content($parent_page_id);
        $deleted_page = get_content_by_id($parent_page_id);


        //PHPUnit
        $this->assertEquals(true, intval($parent_page_id) > 0);
        $this->assertEquals(true, intval($category_id) > 0);
        $this->assertEquals(true, is_array($category_data));
        $this->assertEquals(true, is_array($category_page));
        $this->assertEquals($category_page['title'], $parent_page_data['title']);


        $this->assertEquals($category_id, $delete_category);
        $this->assertEquals(false, $deleted_page);
        $this->assertEquals(true, is_array($delete_page));

    }


}