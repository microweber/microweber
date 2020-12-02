<?php
namespace MicroweberPackages\Helper\tests;

use MicroweberPackages\Core\tests\TestCase;

class CommentsTest extends TestCase
{
    public function testPostComment()
    {
        $this->_setDisableTerms();


        $params = array(
            'title' => 'some post test for comments',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);

        $response = $this->json(
            'POST',
            route('api.comment.post'),
            [
                'rel_id'=>$save_post1,
                'rel_type'=>'content',
                'comment_name' => 'Bozhidar',
                'comment_email' => 'selfworksbg@gmail.com',
                'comment_website' => 'credocart.bg',
                'comment_body' => 'Hello, MW! Im bobby. <h1>Html master</h1>',
            ]
        );

        $commentData = $response->getData();

        $this->assertEquals($save_post1, $commentData->data->rel_id);
        $this->assertEquals('content', $commentData->data->rel_type);
        $this->assertEquals('Bozhidar', $commentData->data->comment_name);
        $this->assertEquals('selfworksbg@gmail.com', $commentData->data->comment_email);
        $this->assertEquals('credocart.bg', $commentData->data->comment_website);
        $this->assertEquals('Hello, MW! Im bobby. <h1>Html master</h1>', $commentData->data->comment_body);

        $this->assertNotEmpty($commentData->data->id);

        $this->assertTrue(($commentData->data->id > 0));

        $this->assertEquals(201, $response->status());


    }
    public function testPostCommentWithXss()
    {
        $this->_setDisableTerms();


        $params = array(
            'title' => 'some post test for comments2',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);

        $response = $this->json(
            'POST',
            route('api.comment.post'),
            [
                'rel_id'=>$save_post1,
                'rel_type'=>'content',
                'comment_name' => 'Hacker',
                'comment_email' => 'hackker@hak.com',
                'comment_website' => 'haker.com',
                'comment_body' => 'Hello! Im hacker . <h1>XSS master</h1> <img src="http://url.to.file.which/not.exist" onerror=alert(document.cookie);>',
            ]
        );

        $commentData = $response->getData();

        $this->assertEquals($save_post1, $commentData->data->rel_id);
        $this->assertEquals('content', $commentData->data->rel_type);
        $this->assertEquals('Hacker', $commentData->data->comment_name);
        $this->assertEquals('hackker@hak.com', $commentData->data->comment_email);
        $this->assertEquals('haker.com', $commentData->data->comment_website);
        $this->assertEquals('Hello! Im hacker . <h1>XSS master</h1> <img src="http://url.to.file.which/not.exist">', $commentData->data->comment_body);


        $this->assertNotEmpty($commentData->data->id);

        $this->assertTrue(($commentData->data->id > 0));

        $this->assertEquals(201, $response->status());

    }


    public function testPostCommentWithTerms()
    {

        $this->_setEnableTerms();




        $params = array(
            'title' => 'some post test for comments3',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);


        $response = $this->json(
            'POST',
            route('api.comment.post'),
            [
                'rel_id'=>$save_post1,
                'rel_type'=>'content',
                'comment_name' => 'User for terms',
                'comment_body' => 'Hello',
            ]
        );

        $commentData = $response->getData();


        var_dump($commentData);


    }

    private function _setEnableTerms(){

        $data['option_value'] = 'y';
        $data['option_key'] = 'require_terms';
        $data['option_group'] = 'comments';
        $save = save_option($data);

    }

    private function _setDisableTerms(){

        $data['option_value'] = 'n';
        $data['option_key'] = 'require_terms';
        $data['option_group'] = 'comments';
        $save = save_option($data);

    }
}
