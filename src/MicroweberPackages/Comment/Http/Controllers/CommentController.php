<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 12/2/2020
 * Time: 11:20 AM
 */

namespace MicroweberPackages\Comment\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Comment\Comment;

class CommentController
{
    public function postComment(Request $request)
    {

        $rules = [];
        $inputs = $request->all();

        $rules['rel_id'] = 'required';
        $rules['rel_type'] = 'required';
        $rules['comment_body'] = 'required';

        if (get_option('require_terms', 'comments') == 'y') {
            $rules['terms'] = 'terms:terms_comments';
            if (isset($inputs['newsletter_subscribe']) and $inputs['newsletter_subscribe']) {
                $rules['terms'] = $rules['terms'] . ', terms_newsletter';
            }
        }

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ['errors'=>$validator->messages()->toArray()];
        }

        $save = Comment::create($request->all());

        return (new JsonResource($save))->response();
    }
}