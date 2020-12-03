<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/25/2020
 * Time: 11:13 AM
 */


Route::name('api.comment.')
    ->prefix('api/comment')
    ->middleware(['xss'])
    ->namespace('\MicroweberPackages\Comment\Http\Controllers')
    ->group(function () {
        Route::post('post', 'CommentController@postComment')->name('post');
    });




Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Comment\Http\Controllers\Admin')
    ->group(function () {

        Route::resource('comment', 'AdminCommentController');

    });