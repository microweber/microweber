<?php
// admin
use  \Illuminate\Support\Facades\Route;

Route::name('admin.')
    ->prefix(mw_admin_prefix_url_legacy())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Product\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('shop/product', 'ProductController',['except' => ['show']]);
        Route::get('shop/dashboard', 'ProductController@dashboard')->name('shop.dashboard');

    });

//// front end
//Route::name('api.product.')->prefix('api/product')
//    ->namespace('\MicroweberPackages\Product\Http\Controllers')
//    ->group(function () {
//        Route::get('quick-view', 'ProductQuickViewController@view')->name('quick-view');
//    });
//
