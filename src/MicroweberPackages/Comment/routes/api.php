<?php



Route::name('api.comment.')
    ->prefix(ADMIN_PREFIX)
    ->middleware([
        \MicroweberPackages\Comment\Http\Middleware\PostCommentMiddleware::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class
    ])
    ->namespace('\MicroweberPackages\Comment\Http\Controllers')
    ->group(function () {
        Route::post('post', 'CommentController@postComment')->name('post');
    });


Route::name('api.comment.admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware([\MicroweberPackages\Comment\Http\Middleware\PostCommentMiddleware::class,'admin'])
    ->namespace('\MicroweberPackages\Comment\Http\Controllers\Admin')
    ->group(function () {
        Route::post('edit', 'AdminCommentController@saveCommentEdit')->name('edit');
    });


Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware([\MicroweberPackages\Comment\Http\Middleware\PostCommentMiddleware::class,'admin'])
    ->namespace('\MicroweberPackages\Comment\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('comment', 'AdminCommentController',['only' => ['index']]);
    });
