<?php
namespace MicroweberPackages\Content\tests;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;

class ContentManagerTest extends TestCase
{
    public function testPosts()
    {
        $params = array(
            'title' => 'this-is-my-test-post',
            'content_type' => 'post',

            'is_active' => 1,);
        //saving
        $save_post = save_content($params);
        //getting
        $get_post = get_content($params);
        foreach ($get_post as $item) {
            $del_params = array('id' => $item['id'], 'forever' => true);
            //delete content
            $delete = delete_content($del_params);
            //check if deleted
            $content = get_content_by_id($item['id']);
            //PHPUnit
            $this->assertEquals(true, is_array($delete));
            $this->assertEquals(false, $content);
            $this->assertEquals('this-is-my-test-post', ($item['title']));
        }
        //PHPUnit
        $this->assertEquals(true, $save_post);
        $this->assertEquals(true, is_array($get_post));
    }

    public function testPages()
    {
        $clean = Content::truncate();

        $params = array(
            'title' => 'My test page',
            'content_type' => 'page',

            'is_active' => 1,);
        //saving
        $parent_page = save_content($params);
        $page_link = content_link($parent_page);
        $params = array(
            'title' => 'My test sub page',
            'content_type' => 'page',
            'parent' => $parent_page,

            'is_active' => 1,);
        $sub_page = save_content($params);
        //getting
        $params = array(
            'parent' => $parent_page,
            'content_type' => 'page',
            'single' => true,

            'is_active' => 1,);
        $get_sub_page = get_content($params);
        $sub_page_parents = content_parents($get_sub_page['id']);



        $params = array(
            "content_type" => "page",
            "paging_param" => "pg",
            "orderby" => "position desc",
            "no_cache" => 1,
            "limit" => 3,
            "page_count" => true
        );

        $get = get_content($params);
        $this->assertEquals(1, $get);

        $params = array(
            "content_type" => "page",

            "orderby" => "position desc",
            "no_cache" => 1,
            "limit" => 1,
            "page_count" => true
        );

        $get_count = get_content('count=1');

        $get = get_content($params);
        $this->assertEquals($get_count, $get);



        $params = array(
            "content_type" => "page",
            "page" => 2, // old parameter for current_page
            "orderby" => "position desc",
            "no_cache" => 1,
            "limit" => 1

        );

        $get = get_content($params);
        $this->assertEquals($sub_page, $get[0]['id']);

        $params = array(
            "content_type" => "page",
            "current_page" => 2,
            "orderby" => "position desc",
            "no_cache" => 1,
            "limit" => 1
        );

        $get = get_content($params);
        $this->assertEquals($sub_page, $get[0]['id']);

        $params = array(
            "content_type" => "page",
            "paging_param" => "mypagingparam",
            "mypagingparam" => 2,
            "orderby" => "position desc",
            "no_cache" => 1,
            "limit" => 1
        );

        $get = get_content($params);
        $this->assertEquals($sub_page, $get[0]['id']);


        //clean
        $delete_parent = delete_content($parent_page);
        $delete_sub_page = delete_content($sub_page);
        //PHPUnit
        $this->assertEquals(true, in_array($parent_page, $sub_page_parents));
        $this->assertEquals(true, strval($page_link) != '');
        $this->assertEquals(true, intval($parent_page) > 0);
        $this->assertEquals(true, intval($sub_page) > 0);
        $this->assertEquals(true, is_array($get_sub_page));
        $this->assertEquals(true, is_array($delete_parent));
        $this->assertEquals(true, is_array($delete_sub_page));
        $this->assertEquals('My test sub page', $get_sub_page['title']);
        $this->assertEquals($sub_page, $get_sub_page['id']);






    }

    public function testGetPages()
    {
        $params = array(
            'title' => 'My test page is here',
            'content_type' => 'page',

            'is_active' => 1,);
        //saving
        $new_page_id = save_content($params);
        $get_pages = get_pages($params);
        $page_found = false;
        if (is_array($get_pages)) {
            foreach ($get_pages as $page) {
                if ($page['id'] == $new_page_id) {
                    $page_found = true;
                    $this->assertEquals('page', $page['content_type']);
                }
                //PHPUnit
                $this->assertEquals(true, intval($page['id']) > 0);
            }
        }
        //clean
        $delete_sub_page = delete_content($new_page_id);
        //PHPUnit
        $this->assertEquals(true, $page_found);
        $this->assertEquals(true, intval($new_page_id) > 0);
        $this->assertEquals(true, is_array($get_pages));
        $this->assertEquals(true, is_array($delete_sub_page));
    }

    public function testGetProducts()
    {
        $params = array(
            'title' => 'My test post is here',
            'content_type' => 'post',
            'subtype' => 'product',

            'is_active' => 1,);
        //saving
        $new_page_id = save_content($params);
        $get_pages = get_products($params);
        $page_found = false;
        if (is_array($get_pages)) {
            foreach ($get_pages as $page) {
                if ($page['id'] == $new_page_id) {
                    $page_found = true;
                    $this->assertEquals('post', $page['content_type']);
                    $this->assertEquals('product', $page['subtype']);
                }
                //PHPUnit
                $this->assertEquals(true, intval($page['id']) > 0);
            }
        }
        //clean
        $delete_sub_page = delete_content($new_page_id);
        //PHPUnit
        $this->assertEquals(true, $page_found);
        $this->assertEquals(true, intval($new_page_id) > 0);
        $this->assertEquals(true, is_array($get_pages));
        $this->assertEquals(true, is_array($delete_sub_page));
    }

    public function testGetPosts()
    {
        $params = array(
            'title' => 'My test post is here',
            'content_type' => 'post',

            'is_active' => 1,);
        //saving
        $new_post_id = save_content($params);
        $get_posts = get_posts($params);
        $post_found = false;
        if (is_array($get_posts)) {
            foreach ($get_posts as $post) {
                if ($post['id'] == $new_post_id) {
                    $post_found = true;
                    $this->assertEquals('post', $post['content_type']);
                    $this->assertEquals('post', $post['subtype']);
                }
                //PHPUnit
                $this->assertEquals(true, intval($post['id']) > 0);
            }
        }
        //clean
        $delete_sub_page = delete_content($new_post_id);
        //PHPUnit
        $this->assertEquals(true, $post_found);
        $this->assertEquals(true, intval($new_post_id) > 0);
        $this->assertEquals(true, is_array($get_posts));
        $this->assertEquals(true, is_array($delete_sub_page));
    }

    public function testContentCategories()
    {
        app()->database_manager->extended_save_set_permission(true);


        $params = array(
            'title' => 'My categories page',
            'content_type' => 'page',
            'subtype' => 'dynamic',

            'is_active' => 1,);

        //saving
        $parent_page_id = save_content($params);
        $parent_page_data = get_content_by_id($parent_page_id);
        $params = array(
            'title' => 'Test Category 1',
            'rel_type' => 'content',
            'rel_id' => $parent_page_id,
        );
        //saving
        $category_id = save_category($params);
        $category_data = get_category_by_id($category_id);
        $category_page = get_page_for_category($category_data['id']);
        $delete_category = delete_category($category_id);
        $delete_page = delete_content($parent_page_id);
        $deleted_page = get_content_by_id($parent_page_id);

        $params = array(
            'title' => 'Test Category with invalid position',
            'position' => 'uga buga',
        );
        $category_with_invalid_pos = save_category($params);

        //PHPUnit
        $this->assertEquals(true, intval($parent_page_id) > 0);
        $this->assertEquals(true, intval($category_id) > 0);
        $this->assertEquals(true, is_array($category_data));
        $this->assertEquals(true, is_array($category_page));
        $this->assertEquals($category_page['title'], $parent_page_data['title']);

        $this->assertEquals(true, $delete_category);
        $this->assertEquals(false, $deleted_page);
        $this->assertEquals(true, intval($category_with_invalid_pos) > 0);

        $this->assertEquals(true, is_array($delete_page));
    }


    public function testContentCategories2()
    {
        app()->database_manager->extended_save_set_permission(true);

        $params = array(
            'title' => 'My categories page 2',
            'content_type' => 'page',
            'subtype' => 'dynamic',
            'is_active' => 1,);

        //saving
        $parent_page_id = save_content($params);
        $parent_page_data = get_content_by_id($parent_page_id);


        $params = array(
            'title' => 'My post in category',
            'categories' => 'new sub category',
            'content_type' => 'post',
            'subtype' => 'post',
            'parent' => $parent_page_id,
        );


        //saving
        $post_in_category = save_content($params);

        $post_in_category_data = get_content_by_id($post_in_category);

        $this->assertEquals($parent_page_data['id'], $post_in_category_data['parent']);

        $post_cats = content_categories($post_in_category, 'categories');

        $this->assertEquals($post_cats[0]['title'],'new sub category');

        $delete_category = delete_category($post_cats[0]['id']);
        $deleted_category = get_category_by_id($post_cats[0]['id']);
        $this->assertEquals(true, $delete_category);


        $this->assertEquals(false, $deleted_category);
    }



    public function testNextPrev()
    {
        $params = array(
            'title' => 'this is my test next prev post',
            'content_type' => 'post',

            'is_active' => 1,);
        //saving
        $save_post1 = save_content($params);
        $save_post2 = save_content($params);
        $save_post3 = save_content($params);

        //getting
        $next = next_content($save_post1);
        $prev = prev_content($save_post2);

        $this->assertEquals($save_post2, ($next['id']));
        $this->assertEquals($save_post1, ($prev['id']));
        $next = next_content($save_post2);
        $prev = prev_content($save_post3);
        $this->assertEquals($save_post3, ($next['id']));
        $this->assertEquals($save_post2, ($prev['id']));
        $del1 = delete_content($save_post1);
        $del2 = delete_content($save_post2);
        $del3 = delete_content($save_post3);
        //PHPUnit
        $this->assertEquals(true, is_array($del1));
        $this->assertEquals(true, is_array($del2));
        $this->assertEquals(true, is_array($del3));
        $this->assertEquals(true, is_array($next));
    }

    public function testCrudFilter()
    {
        $phpunit = $this;
        $params = array(
            'title' => 'Test override',
            'content_type' => 'post',
            'is_active' => 1
        );

        $saved_id = save_content($params);


   /*     event_bind('mw.crud.content.get.params', function ($original_params) use ($saved_id,$phpunit) {
            if(is_array($original_params) and isset($original_params['id']) and $original_params['id'] == $saved_id) {
                $new_params = $original_params;
                $new_params['is_deleted'] = 0;


                $phpunit->assertEquals($saved_id, $original_params['id']);

                return $new_params;
            }
        });*/

/*
        event_bind('mw.crud.content.get', function ($items) use ($saved_id) {
            if($items){
                foreach ($items as $k=> $item){
                    if(is_array($item) and $item['id'] == $saved_id){
                        $item['title'] = 'I just changed the title from a filter';
                    }
                    $items[$k] = $item;
                }
            }
            return $items;

        });*/


        $cont = get_content_by_id($saved_id);

      //  $this->assertEquals('I just changed the title from a filter', $cont['title']);
        $this->assertEquals($saved_id, $cont['id']);
    }

    public function testContentSearch()
    {
        $title = 'Search By title '. uniqid('kw');
        app()->database_manager->extended_save_set_permission(true);
        $params = array(
            'title' => $title,
            'content_type' => 'post',
            'is_active' => 1
        );

        $saved_id = save_content($params);
        $get = get_content('keyword='.$title);

        $this->assertEquals($title, $get[0]['title']);
        $this->assertEquals($saved_id, $get[0]['id']);


    }

    public function testContentLimitPaging()
    {

        $title = 'New '. uniqid('New');
        app()->database_manager->extended_save_set_permission(true);
        $params = array(
            'title' => $title,
            'content_type' => 'post',
            'is_active' => 1
        );

        $saved_id = save_content($params);

        $title = 'New  '. uniqid('New');
        $params = array(
            'title' => $title,
            'content_type' => 'post',
            'is_active' => 1
        );

        $saved_id = save_content($params);



        $get = get_content('limit=1');
        $get2 = get_content('limit=1&current_page=2');
        $this->assertNotEquals($get[0]['id'], $get2[0]['id']);

        $get3 = get_content('ids='.$get2[0]['id']);
        $this->assertEquals(1, count($get3));
        $this->assertEquals($get3[0]['id'], $get2[0]['id']);



        $get = get_content('nolimit=1');
        $get2 = get_content('limit=2');
        $this->assertNotEquals(count($get), count($get2));

    }

    public function testContentOrderBy()
    {
        $get = get_content('limit=1&order_by=id desc');
        $get2 = get_content('limit=1');
        $this->assertNotEquals($get[0]['id'], $get2[0]['id']);

    }
    public function testContentGroupBy()
    {
        $title = 'New '. uniqid('ParentGroupBy');
        $parent = rand(1000,9999);
        app()->database_manager->extended_save_set_permission(true);
        $params = array(
            'title' => $title,
            'content_type' => 'post',
            'parent' => $parent,
            'is_active' => 1
        );

        $saved_id = save_content($params);
        $saved_id2 = save_content($params);

        $get = get_content('limit=100&group_by=parent&parent='.$parent);
        $this->assertEquals(1, count($get));

    }

}
