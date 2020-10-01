<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')
    ->prefix('admin')
    ->namespace('\MicroweberPackages\Post\Http\Controllers\Admin')
    ->middleware(['xss', 'admin'])
    ->group(function () {
        Route::resource('posts', 'PostsController');
});