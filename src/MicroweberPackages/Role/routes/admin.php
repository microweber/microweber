<?php

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

/*
Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('admin/permissions', \MicroweberPackages\Role\Http\Controllers\Admin\PermissionsController::class);
    Route::resource('admin/roles', \MicroweberPackages\Role\Http\Controllers\Admin\RolesController::class);
    Route::resource('admin/users', \MicroweberPackages\Role\Http\Controllers\Admin\UsersController::class);
    // Route::resource('add-items', 'Admin\AddItemController');
});
*/

Route::resource('admin/permissions', \MicroweberPackages\Role\Http\Controllers\Admin\PermissionsController::class);
Route::resource('admin/roles', \MicroweberPackages\Role\Http\Controllers\Admin\RolesController::class);
Route::resource('admin/users', \MicroweberPackages\Role\Http\Controllers\Admin\UsersController::class);