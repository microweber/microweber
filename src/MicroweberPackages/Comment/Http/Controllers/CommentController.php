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


        $save_req = $request->all();
        if (!empty($save_req['comment_body']) and !empty($inputs['format']) and $inputs['format'] == 'markdown') {

            $save_req['comment_body'] = Markdown::convertToHtml($save_req['comment_body']);

        }

        $save = Comment::create($save_req);

        return (new JsonResource($save))->response();
    }
}