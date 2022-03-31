<?php

Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Category\Http\Controllers\Admin')
    ->group(function () {
        Route::resource('category', 'CategoryController');
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


//
//        Route::delete('category/delete/{id}', function ($id) {
//            return delete_category(['id'=>$id]);
//        });
//

        Route::apiResource('category', 'CategoryApiController');
        Route::post('category/{category}', 'CategoryApiController@update');

    });




