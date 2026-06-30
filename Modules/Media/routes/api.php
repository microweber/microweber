<?php

use \Illuminate\Support\Facades\Route;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Modules\Media\Http\Controllers\Api\MediaApiController;

Route::post('/api/media/upload', function (\Illuminate\Http\Request $request) {

    return app()->media_manager->upload($_POST);


})->middleware(['api', 'admin', 'xss'])->name('api.media_upload');

Route::get('pixum_img', function (\Illuminate\Http\Request $request) {

    return pixum_img();


})->middleware('web')->name('api.pixum_img');

Route::get('thumbnail_img', function (\Illuminate\Http\Request $request) {

    return thumbnail_img($request->all());


})->middleware('web')->name('api.pixum_img');


Route::get('/api/image-generate-tn-request/{cache_id}', function ($mediaId) {

    $mediaId = str_replace('..', '', $mediaId);
    $check = \Modules\Media\Models\MediaThumbnail::where('uuid', $mediaId)->first();

    if ($check) {
        $opts = $check->image_options;
        $opts = app()->url_manager->replace_site_url_back($opts);
        $cache_id_data_json = $opts;
        $cache_id_data_json['cache_id'] = $check->rel_id ?? $mediaId;

        $tn = app()->media_manager->thumbnail_img($cache_id_data_json);
        return $tn;
    }


    return app()->media_manager->pixum_img();
})->name('api.image-generate-tn-request');

Route::post('/api/save_media', function (\Illuminate\Http\Request $request) {

    return save_media($request->all());


})->middleware(['api', 'admin', 'xss'])->name('api.save_media');


/*
|--------------------------------------------------------------------------
| Headless Module API (media)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/media/* surface above so
| both clients keep working through one implementation.
|
*/

Route::prefix('api/module/media')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.media.')
    ->group(function () {
        Route::get('/', [MediaApiController::class, 'index'])->name('index');
        Route::get('/{media}', [MediaApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/media')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:media:write'])
    ->name('api.module.media.')
    ->group(function () {
        Route::post('/', [MediaApiController::class, 'store'])->name('store');
        Route::put('/{media}', [MediaApiController::class, 'update'])->name('update');
        Route::patch('/{media}', [MediaApiController::class, 'update'])->name('update.partial');
        Route::delete('/{media}', [MediaApiController::class, 'destroy'])->name('destroy');
    });

