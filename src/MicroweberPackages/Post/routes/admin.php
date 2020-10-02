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
    ->group(function () {

        Route::resource('posts', 'PostsController');

});