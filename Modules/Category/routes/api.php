<?php


use  \Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Api\CategoriesApiController;

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

Route::prefix('api/module/categories')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.categories.')
    ->group(function () {
        Route::get('/', [CategoriesApiController::class, 'index'])->name('index');
        Route::get('/{category}', [CategoriesApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/categories')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:categories:write'])
    ->name('api.module.categories.')
    ->group(function () {
        Route::post('/', [CategoriesApiController::class, 'store'])->name('store');
        Route::put('/{category}', [CategoriesApiController::class, 'update'])->name('update');
        Route::patch('/{category}', [CategoriesApiController::class, 'update'])->name('update.partial');
        Route::delete('/{category}', [CategoriesApiController::class, 'destroy'])->name('destroy');
    });

