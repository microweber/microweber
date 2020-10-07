<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/5/2020
 * Time: 1:45 PM
 */

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['admin'])->namespace('\MicroweberPackages\User\Http\Controllers')->group(function () {
    Route::get('login', 'AuthController@index')->name('admin.login');
});