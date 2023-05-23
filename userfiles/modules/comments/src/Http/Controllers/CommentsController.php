<?php
namespace MicroweberPackages\Modules\Comments\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\App\Http\Controllers\ModuleFrontController;
use MicroweberPackages\Modules\Comments\Models\Comment;

class CommentsController extends ModuleFrontController {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $moduleId = $request->get('id');
        $getComments = Comment::where('reply_to_comment_id', null)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->view('comments::index', [
            'comments' => $getComments,
        ]);
    }

    public function postComment(Request $request) {

        $newComment = new Comment();
        $newComment->fill($request->all());
        $newComment->save();

    }
}
