<?php

use Illuminate\Support\Facades\Route;
use MicroweberPackages\MediaPixum\Http\Controllers\PixumController;

/*
|--------------------------------------------------------------------------
| Media Pixum Routes
|--------------------------------------------------------------------------
|
| These routes handle placeholder image generation and serving.
| Loaded by MediaPixumServiceProvider.
|
*/

Route::middleware('web')->group(function () {
    Route::get('pixum_img', [PixumController::class, 'serve'])
        ->name('media-pixum.serve');
});