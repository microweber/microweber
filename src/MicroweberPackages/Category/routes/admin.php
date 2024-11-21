<?php
use  \Illuminate\Support\Facades\Route;
// Blog and pages
Route::name('admin.')
    ->prefix(mw_admin_prefix_url_legacy())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('category', 'CategoryController');
    });


// Shop
Route::name('admin.shop.')
    ->prefix(mw_admin_prefix_url_legacy().'/shop')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('category', 'CategoryShopController');
    });





