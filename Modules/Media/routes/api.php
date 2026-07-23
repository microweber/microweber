<?php

use \Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\Api\MediaApiController;
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;

Route::post('/api/media/upload', function (\Illuminate\Http\Request $request) {

    return app()->media_manager->upload($_POST);


})->middleware(['api', 'admin', 'xss'])->name('api.media_upload');


Route::get('/api/image-generate-tn-request/{cache_id}', function ($mediaId) {

    $mediaId = str_replace('..', '', $mediaId);
    $check = app(MediaThumbnailRepository::class)->findByUuid($mediaId);

    if ($check) {
        $opts = $check->image_options;
        if (is_array($opts)) {
            $opts = app()->url_manager->replace_site_url_back($opts);
            $opts['cache_id'] = $check->rel_id ?? $mediaId;
        }

        $tn = app()->media_manager->thumbnail_img($opts ?? []);
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

