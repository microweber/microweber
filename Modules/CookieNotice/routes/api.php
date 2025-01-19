<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/


Route::post('api/cookie-notice/set-cookie',
    [\Modules\CookieNotice\Http\Controllers\Api\CookieNoticeController::class, 'setCookie'])
    ->middleware(['api'])
    ->name('api.cookie-notice.set-cookie');
