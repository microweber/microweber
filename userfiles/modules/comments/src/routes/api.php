<?php


Route::name('api.comment.')
    ->prefix('api/comment')
    //->middleware([\MicroweberPackages\Modules\Comments\Http\Middleware\PostCommentMiddleware::class])
    ->namespace('\MicroweberPackages\Modules\Comments\Http\Controllers')
    ->group(function () {
        Route::post('post_comment', 'CommentsController@postComment')->name('post_comment');
    });
