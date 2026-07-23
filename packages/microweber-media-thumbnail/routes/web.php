<?php

use Illuminate\Support\Facades\Route;
use MicroweberPackages\MediaThumbnail\Http\Controllers\ThumbnailController;

/*
|--------------------------------------------------------------------------
| Media Thumbnail Routes
|--------------------------------------------------------------------------
|
| These routes handle thumbnail generation and serving.
| They are loaded by MediaThumbnailServiceProvider and use the 'web'
| middleware group by default (configurable).
|
*/

Route::middleware('web')->group(function () {
    Route::get('thumbnail_img', [ThumbnailController::class, 'thumbnailImg'])
        ->name('media-thumbnail.thumbnail');

    Route::get('api/media-thumbnail/generate/{uuid}', [ThumbnailController::class, 'generateByUuid'])
        ->name('media-thumbnail.generate');
});