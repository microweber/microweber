<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/2/2020
 * Time: 11:20 AM
 */

namespace MicroweberPackages\Comment\Http\Controllers;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Comment\Comment;
use MicroweberPackages\Option\Facades\Option;

class CommentController
{
    public function postComment(Request $request)
    {

        $rules = [];
        $inputs = $request->all();

        $rules['rel_id'] = 'required';
        $rules['rel_type'] = 'required';
        $rules['comment_body'] = 'required';

        if (!empty($inputs['comment_email'])) {
            $inputs['email'] = $inputs['comment_email'];
        }

        if (get_option('require_terms', 'comments') == 'y') {
            $rules['terms'] = 'terms:terms_comments';
            if (isset($inputs['newsletter_subscribe']) and $inputs['newsletter_subscribe']) {
                $rules['terms'] = $rules['terms'] . ', terms_newsletter';
            }
            $rules['comment_email'] = 'required';
        }

        $rules['captcha'] = 'captcha';
         if (is_module('captcha') && Option::getValue('disable_captcha', 'comments')) {
            unset($rules['captcha']);
        }

        $validator = \Validator::make($inputs, $rules);
        if ($validator->fails()) {
            return ['errors'=>$validator->messages()->toArray()];
        }

        $saveComment = $request->all();

        $requireModeration = Option::getValue('require_moderation', 'comments');
        if ($requireModeration) {
            $saveComment['is_moderated'] = 1;
        }

        if (!empty($saveComment['comment_body']) and !empty($inputs['format']) and $inputs['format'] == 'markdown') {
            $saveComment['comment_body'] = Markdown::convertToHtml($saveComment['comment_body']);
        }

        $save = Comment::create($saveComment);

        event(new NewComment($save));

        Notification::send(User::whereIsAdmin(1)->get(), new NewCommentNotification($save));

        return (new JsonResource($save))->response();
    }
}