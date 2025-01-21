<?php

use Illuminate\Support\Facades\Route;
use Modules\Captcha\Http\Controllers\CaptchaController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware('web')->get('api/captcha', function () {
    $params = request()->all();
    return mw()->captcha_manager->render($params);
});
