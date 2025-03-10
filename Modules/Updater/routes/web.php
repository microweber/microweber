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
    ->prefix(ADMIN_PREFIX . '/updater')
    ->middleware(['admin'])
    ->namespace('Modules\Updater\Http\Controllers')
    ->group(function () {
        Route::get('about-new-version', 'UpdaterController@aboutNewVersion')->name('about-new-version');
    });
