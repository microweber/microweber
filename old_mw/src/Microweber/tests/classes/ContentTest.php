<?php

namespace ClassTest;


class ContentTest extends \PHPUnit_Framework_TestCase
{




    public function testContent()
    {

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



    }

}