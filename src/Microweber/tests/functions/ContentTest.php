<?php

namespace FunctionsTest;


class ContentTest extends \PHPUnit_Framework_TestCase
{

    function __construct()
    {
        cache_clear('db');
        mw('content')->db_init();
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
            // 'debug' => 1,
            'is_active' => 'y');
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
            'title' => 'My test product is here',
            'content_type' => 'post',
            'subtype' => 'product',
            // 'debug' => 1,
            'is_active' => 'y');
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
            // 'debug' => 1,
            'is_active' => 'y');
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

    public function testNextPrev()
    {

        $params = array(
            'title' => 'this is my test next prev post',
            'content_type' => 'post',
            // 'debug' => 1,
            'is_active' => 'y');
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

    public function testSaveWithCustomFields()
    {
        $price = rand();
        $params = array(
            'title' => 'My custom product',
            'content_type' => 'post',
            'subtype' => 'product',
            'custom_field_price' => $price,
            // 'debug' => 1,
            'is_active' => 'y');


        //saving
        $product_id = save_content($params);
        $product_data = get_content_by_id($product_id);
        $custom_fields = get_custom_fields($product_id);

        $delete_page = delete_content($product_id);
        $deleted_page = get_content_by_id($product_id);

        //PHPUnit
        $this->assertEquals(true, is_array($custom_fields));
        $this->assertEquals(true, isset($custom_fields['price']));
        $this->assertEquals($price, intval($custom_fields['price']));

        $this->assertEquals(true, is_array($delete_page));
        $this->assertEquals(false, $deleted_page);
        $this->assertEquals(true, is_array($delete_page));

    }

    public function testCustomFields()
    {
        return;
        // to BE FIXED


        for ($i = 1; $i <= 10; $i++) {
            $price = rand(10, 2000);
            $params = array(
                'title' => 'My custom product test title',
                'content_type' => 'post',
                'subtype' => 'product',
                'custom_field_color' => 'red',
                'custom_field_price' => $price,
                // 'debug' => 1,
                'is_active' => 'y');
            //saving

            $product_id = save_content($params);
        }

        $product_data = get_content_by_id($product_id);
        $custom_fields = get_custom_fields($product_id);

        //test get by custom fields
        $params = array(
            'limit' => 1,
            'custom_fields.type' => 'price',
            'custom_fields.value' => $price);

        $products = get_products($params);
        $found = false;
        foreach ($products as $product) {
            $custom_fields = get_custom_fields($product['id']);

            $this->assertEquals(true, intval($custom_fields['price']) == $price);

            //PHPUnit
        }


        $params = array(
            'limit' => 100,
            'custom_fields.type' => 'price',
            'custom_fields.name' => 'price',
            'custom_fields.value' => '>' . intval($price + 1));


        $products = get_products($params);
        $found = false;
        foreach ($products as $product) {
            $custom_fields = get_custom_fields($product['id']);
            $this->assertEquals(true, intval($custom_fields['price']) != $price);
            //PHPUnit
            $this->assertEquals(true, isset($product['id']));
        }


        $params = array(
            'limit' => 100,
            'custom_fields.type' => 'price',
            'custom_fields.value' => '<' . intval($price + 10));

        $products = get_products($params);
        foreach ($products as $product) {
            $this->assertEquals(true, isset($product['id']));
        }


        $params = array(
            'limit' => 100,
            'custom_fields.type' => 'price',
            'custom_fields.value' => '<=' . intval($price + 100));
        $products = get_products($params);
        $found = false;
        foreach ($products as $product) {
            $this->assertEquals(true, isset($product['id']));
        }


        $params = array(
            'limit' => 100,
            'custom_fields.type' => 'price',
            'custom_fields.value' => '[lte]' . intval($price + 1000));

        $products = get_products($params);
        foreach ($products as $product) {

            $this->assertEquals(true, isset($product['id']));
        }


        $params = array(
            'limit' => 100,
            'custom_fields.type' => 'price',
            'custom_fields.value' => '[lt]' . intval($price + 700));


        $products = get_products($params);
        foreach ($products as $product) {

            $this->assertEquals(true, isset($product['id']));
        }


        $params = array(
            'limit' => 100,
            'custom_fields.type' => 'price',
            'custom_fields.value' => '[md]' . intval(10));

        $products = get_products($params);
        foreach ($products as $product) {

            $this->assertEquals(true, isset($product['id']));
        }


        $params = array(
            'limit' => 100,
            'custom_field_price' => '[mde]' . intval($price - 500));

        $products = get_products($params);
        foreach ($products as $product) {

            $this->assertEquals(true, isset($product['id']));
        }


        $delete_page = delete_content($product_id);
        $deleted_page = get_content_by_id($product_id);


        //PHPUnit
        $this->assertEquals(true, is_array($products));
        $this->assertEquals(true, is_array($custom_fields));
        $this->assertEquals(true, isset($custom_fields['price']));
        $this->assertEquals(true, is_array($delete_page));
        $this->assertEquals(false, $deleted_page);
        $this->assertEquals(true, is_array($delete_page));

    }

    public function testCustomFieldsAdvanced()
    {
        $price = rand();


        $save_fields = array(
            'color' => array('title' => 'my color', 'type' => 'dropdown', 'values' => 'red,blue,green'),
            'size' => array('type' => 'checkbox', 'values' => 's,m,l'),
            'price' => $price);

        $params = array(
            'title' => 'My custom product advanced test title',
            'content_type' => 'post',
            'subtype' => 'product',
            'custom_fields' => $save_fields,
            // 'debug' => 1,
            'is_active' => 'y');

        //saving
        $product_id = save_content($params);

        $custom_fields = get_custom_fields($product_id);


        //test get by custom fields
        $params = array(
            'title' => 'My custom product advanced test title',
            'limit' => 100,
            'custom_field_price' => $price);

        $products = get_products($params);
        $found = false;
        foreach ($products as $product) {
            if (isset($product['id']) and $product['id'] == $product_id) {
                $found = true;
                $this->assertEquals($price, ($custom_fields['price']));
            }
            //PHPUnit
            $this->assertEquals(true, isset($product['id']));
        }
        $this->assertEquals(true, $found);


        $params = array(
            // 'title' => 'My custom product advanced test title',
            'limit' => 1000,
            'custom_fields.name' => 'color',
            'custom_fields.value' => 'red');

        $products = get_products($params);


        foreach ($products as $product) {

            $custom_fields = get_custom_fields($product['id']);

            //PHPUnit
            $this->assertEquals(true, isset($custom_fields['color']));
            $this->assertEquals(true, isset($product['id']));
        }


        $params = array(
            'title' => 'My custom product advanced test title',
            'limit' => 1000,
            'custom_field_color' => 'red');

        $products = get_products($params);

        if (is_array($products)) {
            foreach ($products as $product) {
                $custom_fields = get_custom_fields($product['id']);

                $delete_page = delete_content($product_id);
                $deleted_page = get_content_by_id($product_id);

                //PHPUnit
                if (isset($custom_fields['color'])) {
                    $this->assertEquals(true, stristr($custom_fields['color'], 'red') == true);
                    $this->assertEquals(true, isset($custom_fields['color']));

                }
                $this->assertEquals(true, isset($product['id']));
                $this->assertEquals(true, is_array($delete_page));
                $this->assertEquals(false, $deleted_page);
                $this->assertEquals(true, is_array($delete_page));
            }


        }
    }

    public function testCustomFieldsOrderby()
    {
        return;
        $price = rand();


        $save_fields = array(
            'color' => array('title' => 'my color', 'type' => 'dropdown', 'values' => 'red,blue,green'),
            'size' => array('type' => 'checkbox', 'values' => 's,m,l'),
            'price' => $price);

        $params = array(
            'title' => 'My custom product advanced test title',
            'content_type' => 'post',
            'subtype' => 'product',
            'custom_fields' => $save_fields,
            // 'debug' => 1,
            'is_active' => 'y');

        //saving
        $product_id = save_content($params);

        $custom_fields = get_custom_fields($product_id);


        //test get by custom fields
        $params = array(
            'title' => 'My custom product advanced test title',
            'limit' => 100,
            'custom_field_price' => $price);

        $products = get_products($params);
        $found = false;
        foreach ($products as $product) {
            if (isset($product['id']) and $product['id'] == $product_id) {
                $found = true;
                $this->assertEquals($price, ($custom_fields['price']));
            }
            //PHPUnit
            $this->assertEquals(true, isset($product['id']));
        }
        $this->assertEquals(true, $found);
        //PHPUnit
        $this->assertEquals(true, is_array($products));
        $this->assertEquals(true, is_array($custom_fields));
        $this->assertEquals(true, isset($custom_fields['price']));
    }


}