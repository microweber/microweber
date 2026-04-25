<?php


use  \Illuminate\Support\Facades\Route;

Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->namespace('\Modules\Category\Http\Controllers\Api')
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
    ->namespace('\Modules\Category\Http\Controllers\Api')
    ->group(function () {

        Route::apiResource('category', 'CategoryApiController');

    });

/*
|--------------------------------------------------------------------------
| Headless Module API (categories)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/categories/* surface above so
| both clients keep working through one implementation.
|
*/

\MicroweberPackages\Module\Routing\ModuleApiRoutes::register(
    'categories',
    \Modules\Category\Http\Controllers\Api\CategoriesApiController::class,
    'category'
);
