<?php

// Blog and pages
Route::name('admin.')
    ->prefix(mw_admin_prefix_url())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('category', 'CategoryController');
    });


// Shop
Route::name('admin.shop.')
    ->prefix(mw_admin_prefix_url().'/shop')
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

        Route::delete('category/delete/{id}', 'CategoryApiController@delete')->name('category.delete');
        Route::delete('category/delete-bulk', 'CategoryApiController@destroy')->name('category.delete-bulk');
        Route::post('category/hidden-bulk', 'CategoryApiController@hiddenBulk')->name('category.hidden-bulk');
        Route::post('category/visible-bulk', 'CategoryApiController@visibleBulk')->name('category.visible-bulk');
        Route::post('category/move-bulk', 'CategoryApiController@moveBulk')->name('category.move-bulk');

        Route::post('category/{category}', 'CategoryApiController@update');

    });





Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Api')
    ->group(function () {

        Route::apiResource('category', 'CategoryApiController');

    });




