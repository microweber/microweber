<?php

// Blog and pages
Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('category', 'CategoryController');
    });


// Shop
Route::name('admin.shop.')
    ->prefix(ADMIN_PREFIX.'/shop')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('category', 'CategoryShopController');
    });



Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Api')
    ->group(function () {

        Route::post('category/reorder', function (\Illuminate\Http\Request $request) {
            return mw()->category_manager->reorder($request->only('ids'));
        });

        Route::delete('category/delete/{id}', 'CategoryApiController@destroy');

        Route::apiResource('category', 'CategoryApiController');
        Route::post('category/{category}', 'CategoryApiController@update');

    });




