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
    ->middleware(['xss','admin'])
    ->group(function () {

        Route::resource('pages', 'PagesController');

    });