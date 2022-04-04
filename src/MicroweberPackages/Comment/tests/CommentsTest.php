<?php

namespace MicroweberPackages\Helper\tests;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\RequestRoute;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class CommentsTest extends TestCase
{
    public function testPostComment()
    {
        $this->_setDisableTerms();
        $this->_setDisableCaptcha();
        $this->_setDisableMustBeLogged();

        $params = array(
            'title' => 'some post test for comments',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);

        $response = $this->json(
            'POST',
            route('api.comment.post'),
            [
                'rel_id' => $save_post1,
                'rel_type' => 'content',
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
        $this->assertEquals('Hello, MW! Im bobby. Html master', $commentData->data->comment_body);

        $this->assertNotEmpty($commentData->data->id);

        $this->assertTrue(($commentData->data->id > 0));

        $this->assertEquals(201, $response->status());


    }

    public function testPostCommentWithXss()
    {
        $this->_setDisableTerms();
        $this->_setDisableCaptcha();
        $this->_setDisableMustBeLogged();

        $params = array(
            'title' => 'some post test for comments2',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);

        $response = $this->json(
            'POST',
            route('api.comment.post'),
            [
                'rel_id' => $save_post1,
                'rel_type' => 'content',
                'comment_name' => 'Hacker',
                'comment_email' => 'hackker@hak.com',
                'comment_website' => 'haker.com',
                'comment_body' => 'Hello! Im hacker . <h1>XSS master</h1> <img src="'.site_url().'not.exist" alt="test" onerror=alert(document.cookie);>',
            ]
        );

        $commentData = $response->getData();

        $this->assertEquals($save_post1, $commentData->data->rel_id);
        $this->assertEquals('content', $commentData->data->rel_type);
        $this->assertEquals('Hacker', $commentData->data->comment_name);
        $this->assertEquals('hackker@hak.com', $commentData->data->comment_email);
        $this->assertEquals('haker.com', $commentData->data->comment_website);
        $this->assertEquals('Hello! Im hacker . XSS master ', $commentData->data->comment_body);


        $this->assertNotEmpty($commentData->data->id);

        $this->assertTrue(($commentData->data->id > 0));

        $this->assertEquals(201, $response->status());

    }


    public function testPostCommentWithTerms()
    {

        $this->_setEnableTerms();
        $this->_setDisableCaptcha();
        $this->_setDisableMustBeLogged();

        $params = array(
            'title' => 'some post test for comments3',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);
        $some = 'html' . now() . rand() . '@user.com';
        $req = [
            'rel_id' => $save_post1,
            'rel_type' => 'content',
            'comment_name' => 'User for terms',
            'comment_email' => $some,
            'comment_body' => 'Hello',
        ];


        $commentData = RequestRoute::postJson(
            route('api.comment.post'),
            $req
        );


        $this->assertNotEmpty($commentData['error']);
        $this->assertNotEmpty($commentData['terms_error']);
        $this->assertEquals("terms", $commentData['form_data_required']);


        $req['terms'] = 1;
        $response = RequestRoute::postJson(
            route('api.comment.post'),
            $req
        );

         $this->assertEquals($some, $response['data']['comment_email']);


    }

    public function testPostCommentWithCaptcha()
    {

        $this->_setEnableTerms();
        $this->_setEnableCaptcha();
        $this->_setDisableMustBeLogged();

        $params = array(
            'title' => 'some post test for comments3',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);

        $captchaAnswer = uniqid();
        $captchaWrongAnswer = $captchaAnswer . uniqid();

        $fakeCaptcha = new \MicroweberPackages\Utils\Captcha\tests\Fakers\FakeCaptcha();
        $fakeCaptcha->setAnswer($captchaAnswer);
        app()->captcha_manager->setAdapter($fakeCaptcha);


        $req = [
            'terms' => 1,
            'captcha' => $captchaWrongAnswer,
            'rel_id' => $save_post1,
            'rel_type' => 'content',
            'comment_name' => 'User for terms',
            'comment_email' => 'html' . now() . rand() . '@user.com',
            'comment_body' => 'Hello',
        ];


        $commentData = RequestRoute::postJson(
            route('api.comment.post'),
            $req
        );


        $this->assertEquals("captcha", $commentData['form_data_required']);

        $req['captcha'] = $captchaAnswer;

        $commentData = RequestRoute::postJson(
            route('api.comment.post'),
            $req
        );
        $this->assertNotEmpty($commentData['data']);



        //try to post again with the same captcha
        $commentData = RequestRoute::postJson(
            route('api.comment.post'),
            $req
        );
        $this->assertEquals("captcha", $commentData['form_data_required']);
        $this->assertEquals("captcha", $commentData['form_data_module']);


    }



    public function testPostCommentWithMarkDown()
    {

        $this->_setDisableTerms();
        $this->_setDisableCaptcha();
        $this->_setDisableMustBeLogged();

        $params = array(
            'title' => 'some post test for comments markwodn',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);

        $response = $this->json(
            'POST',
            route('api.comment.post'),
            [
                'rel_id' => $save_post1,
                'rel_type' => 'content',
                'comment_name' => 'Markdown',
                'comment_email' => 'Markdown@Markdown.com',
                'comment_website' => 'Markdown.com',
                'format' => 'markdown',
                'comment_body' => ' # Hello  this is h1',
            ]
        );

        $commentData = $response->getData();

        $this->assertEquals( "<h1>Hello  this is h1</h1>\n", $commentData->data->comment_body);

    }


    public function testCommentNotLoggedUser()
    {
        $this->_setEnableMustBeLogged();

        $this->_setDisableTerms();
        $this->_setDisableCaptcha();


        $params = array(
            'title' => 'some post test for comments markwodn',
            'content_type' => 'post',
            'is_active' => 1,);

        $save_post1 = save_content($params);

        $response = $this->json(
            'POST',
            route('api.comment.post'),
            [
                'rel_id' => $save_post1,
                'rel_type' => 'content',
                'comment_name' => 'Markdown',
                'comment_email' => 'Markdown@Markdown.com',
                'comment_website' => 'Markdown.com',
                'format' => 'markdown',
                'comment_body' => ' # Hello  this is h1',
            ]
        );

        $commentData = $response->getData();

        $this->assertEquals($commentData->errors, 'Must be logged');

    }




    public function testAdminEditComment()
    {
        $this->_setDisableMustBeLogged();
        $this->_setDisableTerms();
        $this->_setDisableCaptcha();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $params = array(
            'title' => 'some post test for comments test'.uniqid(),
            'content_type' => 'post',
            'is_active' => 1);

        $save_post1 = save_content($params);

        $comment1 = 'Hello'.uniqid();
        $comment2 = 'Hello2'.uniqid();

        $response = $this->json(
            'POST',
            route('api.comment.post'),
            [
                'rel_id' => $save_post1,
                'rel_type' => 'content',
                'comment_name' => 'Some',
                'comment_email' => 'email@gmail.com',
                'comment_website' => 'test.com',
                'comment_body' => $comment1,
            ]
        );

        $commentData = $response->getData();

        $comment_id = $commentData->data->id;

        $this->assertEquals($commentData->data->comment_body, $comment1);

        $response = $this->json(
            'POST',
            route('api.comment.admin.edit'),
            [
                'id' => $comment_id,
                'comment_body' => $comment2,
            ]
        );
        $commentData = $response->getData();
        $this->assertEquals($commentData->data->comment_body, $comment2);


        // save as markdown
        $response = $this->json(
            'POST',
            route('api.comment.admin.edit'),
            [
                'id' => $comment_id,
                'comment_body' => $comment2,
                'format' => 'markdown',
            ]
        );
        $commentData = $response->getData();

        $t1 =$commentData->data->comment_body;
        $t2 =Markdown::convertToHtml($comment2);

        $t1 = str_replace(array('\r\n', '\n\r', '\n', '\r'), '<br>', $t1);;
        $t2 = str_replace(array('\r\n', '\n\r', '\n', '\r'), '<br>', $t2);;

        $this->assertEquals($t1, $t2);


        // publish
        $response = $this->json(
            'POST',
            route('api.comment.admin.edit'),
            [
                'id' => $comment_id,
                'action' => 'publish',
            ]
        );
        $commentData = $response->getData();

        $this->assertEquals($commentData->data->is_moderated, 1);
        $this->assertEquals($commentData->data->is_spam, 0);


        // unpublish
        $response = $this->json(
            'POST',
            route('api.comment.admin.edit'),
            [
                'id' => $comment_id,
                'action' => 'unpublish',
            ]
        );
        $commentData = $response->getData();
        $this->assertEquals($commentData->data->is_moderated, 0);        // unpublish

        // mark as spam
        $response = $this->json(
            'POST',
            route('api.comment.admin.edit'),
            [
                'id' => $comment_id,
                'action' => 'spam',
            ]
        );
        $commentData = $response->getData();
        $this->assertEquals($commentData->data->is_moderated, 0);
        $this->assertEquals($commentData->data->is_spam, 1);


        // delete
        $response = $this->json(
            'POST',
            route('api.comment.admin.edit'),
            [
                'id' => $comment_id,
                'action' => 'delete',
            ]
        );

        $get_comment = get_comments("single=1&id=" . $comment_id);
        $this->assertFalse($get_comment);

    }


    private function _setDisableMustBeLogged()
    {

        $data['option_value'] = 'n';
        $data['option_key'] = 'user_must_be_logged';
        $data['option_group'] = 'comments';
        $save = save_option($data);

    }

    private function _setEnableMustBeLogged()
    {

        $data['option_value'] = 'y';
        $data['option_key'] = 'user_must_be_logged';
        $data['option_group'] = 'comments';
        $save = save_option($data);

    }

    private function _setEnableTerms()
    {

        $data['option_value'] = 'y';
        $data['option_key'] = 'require_terms';
        $data['option_group'] = 'comments';
        $save = save_option($data);

    }

    private function _setDisableTerms()
    {

        $data['option_value'] = 'n';
        $data['option_key'] = 'require_terms';
        $data['option_group'] = 'comments';
        $save = save_option($data);

    }


    private function _setEnableCaptcha()
    {

        $data['option_value'] = 'n';
        $data['option_key'] = 'disable_captcha';
        $data['option_group'] = 'comments';
        $save = save_option($data);

    }

    private function _setDisableCaptcha()
    {

        $data['option_value'] = 'y';
        $data['option_key'] = 'disable_captcha';
        $data['option_group'] = 'comments';
        $save = save_option($data);

    }
}
