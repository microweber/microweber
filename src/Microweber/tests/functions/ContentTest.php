<?php

namespace FunctionsTest;


class ContentTest extends \PHPUnit_Framework_TestCase
{


    public function testGetContent()
    {


        $params = array(
            'limit' => 10, // get 10 posts
            'order_by' => 'created_on desc',
            'content_type' => 'post', //or page
            'subtype' => 'post', //or product, you can use this field to store your custom content
            'is_active' => 'y');
        //procedural
        $recent_posts = get_content($params);

        //PHPUnit
        $this->assertEquals(true, is_array($recent_posts));


        //OOP
        $recent_posts = mw('content')->get($params);

        //PHPUnit
        $this->assertEquals(true, is_array($recent_posts));


    }

    public function testSaveContent()
    {


        $params = array(
            'title' => 'this-is-my-test-post',
            'content_type' => 'post',
            'is_active' => 'y');
        //procedural
        $save_post = save_content($params);
        $get_post = get_content($params);


        //PHPUnit
        $this->assertEquals(true, $save_post);
        $this->assertEquals(true, is_array($get_post));


        //delete content
        foreach ($get_post as $item) {
            $del_content = array('id' => $item['id'], 'forever' => true);
            $delete = delete_content($del_content);

            $this->assertEquals(true, is_array($delete));
        }

        //OOP
        $params = array(
            'title' => 'another-test-forum-post',
            'content_type' => 'post',
            'subtype' => 'post',
            'is_active' => 'y');
        $save_post = mw('content')->save($params);
        $get_post = mw('content')->get($params);
        //PHPUnit
        $this->assertEquals(true, $save_post);
        $this->assertEquals(true, is_array($get_post));


        //delete content
        foreach ($get_post as $item) {
            $del_content = array('id' => $item['id'], 'forever' => true);
            $delete = mw('content')->delete($del_content);
            $this->assertEquals(true, is_array($delete));
        }


        //PHPUnit
        // $this->assertEquals(true, is_array($recent_posts));


    }

}