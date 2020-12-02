<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/10/2020
 * Time: 4:49 PM
 */

namespace MicroweberPackages\Comment\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use MicroweberPackages\App\Http\Controllers\AdminController;
use MicroweberPackages\Comment\Comment;


class AdminCommentController extends AdminController
{
    public function index(Request $request)
    {
        $contents = Comment::filter($request->all())
            ->groupBy(['rel_id','rel_type'])
            ->paginate($request->get('limit', 30))
            ->appends($request->except('page'));

        foreach ($contents as $content) {
            $content->allComments = Comment::where('rel_type', $content['rel_type'])->where('rel_id', $content['rel_id'])->get();
        }

        return $this->view('comment::admin.comments.index', ['contents'=>$contents]);
    }
}