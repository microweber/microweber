<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/2/2020
 * Time: 11:20 AM
 */

namespace MicroweberPackages\Comment\Http\Controllers;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\App\Http\RequestRoute;
use MicroweberPackages\Comment\Models\Comment;
use MicroweberPackages\Comment\Events\NewComment;
use MicroweberPackages\Comment\Notifications\NewCommentNotification;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Option\Facades\Option;
use MicroweberPackages\User\Models\User;

class CommentController
{
    public function postComment(Request $request)
    {
        if (empty(Auth::user()->id)) {
            $mustBeLogged = Option::getValue('user_must_be_logged', 'comments');
            if ($mustBeLogged) {
                return ['errors'=>'Must be logged'];
            }
        }

        $rules = [];
        $inputs = $request->all();
        if(isset($inputs['rel']) and !isset($inputs['rel_type'])){
            $inputs['rel_type'] = $inputs['rel'];
            unset($inputs['rel']);
        }


        if(isset($inputs['id'])) {
            $comment = get_comments('single=1&id=' . $inputs['id']);
            if (empty($comment)) {
                return \Response::make(['errors' => ['Cannot find comment']]);
            }
            if (mw()->user_manager->session_id() != $comment['session_id']) {
                return \Response::make(['errors' => ['Cannot edit comment']]);
            }

        }

        $rules['rel_id'] = 'required';
        $rules['rel_type'] = 'required';
        $rules['comment_body'] = 'required';

        if (!empty($inputs['email'])) {
            $inputs['comment_email'] = $inputs['email'];
            unset( $inputs['email']);
        }

        if (Option::getValue('require_terms', 'comments')) {
            if(!isset($inputs['terms'])) {
                $rules['terms'] = 'terms:terms_comments';
                if (isset($inputs['newsletter_subscribe']) and $inputs['newsletter_subscribe']) {
                    $rules['terms'] = $rules['terms'] . ', terms_newsletter';
                }
                $rules['comment_email'] = 'required';
            }
        }

        $rules['captcha'] = 'captcha';
         if (is_module('captcha') && Option::getValue('disable_captcha', 'comments')) {
            unset($rules['captcha']);
        }

        $validator = \Validator::make($inputs, $rules);
        if ($validator->fails()) {


            $response = \Response::make(['errors'=>$validator->messages()->toArray()]);

            $response->setStatusCode(422);

            $response = RequestRoute::formatFrontendResponse($response);

            return $response;
        }

        $saveComment = $inputs;

        $requireModeration = Option::getValue('require_moderation', 'comments');
        if ($requireModeration) {
            $saveComment['is_moderated'] = 0;
        } else {
            $saveComment['is_moderated'] = 1;
        }

        $clearInput = new HTMLClean();
        $saveComment['comment_body'] = $clearInput->onlyTags($saveComment['comment_body']);

        if (!empty($saveComment['comment_body']) and !empty($inputs['format']) and $inputs['format'] == 'markdown') {
            $saveComment['comment_body'] = Markdown::convertToHtml($saveComment['comment_body']);
        }

        $save = Comment::create($saveComment);

        event(new NewComment($save));

        Notification::send(User::whereIsAdmin(1)->get(), new NewCommentNotification($save));

        cache_clear('comments');


        return (new JsonResource($save))->response();
    }
}
