<?php

use Illuminate\Support\Facades\Route;
use Modules\Updater\Http\Controllers\UpdaterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('updater.')
    ->prefix(mw_admin_prefix_url() . '/updater')
    ->middleware(['admin'])
    ->namespace( )
    ->group(function () {
        Route::get('about-new-version', Modules\Updater\Http\Controllers\UpdaterController::class.'@aboutNewVersion')->name('about-new-version');
    });
