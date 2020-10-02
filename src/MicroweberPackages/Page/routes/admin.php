<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('admin.')
    ->prefix('admin')
    ->namespace('\MicroweberPackages\Page\Http\Controllers\Admin')
    ->group(function () {

        Route::resource('pages', 'PagesController');

    });