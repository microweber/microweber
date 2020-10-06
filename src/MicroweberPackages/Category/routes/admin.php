<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')
    ->prefix('admin')
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Admin')
    ->group(function () {

        Route::resource('categories', 'CategoriesController');

    });