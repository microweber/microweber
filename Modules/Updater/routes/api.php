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

use Modules\Updater\Http\Controllers\UpdaterController;

Route::name('api.updater.')
    ->prefix('api/updater')
    ->middleware(['api', 'admin'])
    ->group(function () {
        /*Route::get('delete-temp', UpdaterController::class . '@deleteTemp')->name('delete-temp');
        Route::any('update-now', UpdaterController::class . '@updateNow')->name('update-now');
        Route::get('remove-dashboard-notice', function () {
            return save_option('last_update_check_time', \Carbon\Carbon::parse('+24 hours'), 'standalone-updater');
        })->name('remove-dashboard-notice');*/
    });
