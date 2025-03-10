<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('api.updater.')
    ->prefix('api/updater')
    ->middleware(['api', 'admin'])
    ->namespace('Modules\Updater\Http\Controllers')
    ->group(function () {
        Route::get('delete-temp', 'UpdaterController@deleteTemp')->name('delete-temp');
        Route::post('update-now', 'UpdaterController@updateNow')->name('update-now');
        Route::get('remove-dashboard-notice', function () {
            return save_option('last_update_check_time', \Carbon\Carbon::parse('+24 hours'), 'standalone-updater');
        })->name('remove-dashboard-notice');
    });
