<?php

namespace ContentTest;


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


}