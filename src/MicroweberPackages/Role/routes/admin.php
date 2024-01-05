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


Route::prefix(mw_admin_prefix_url())->name('admin.')->middleware(['admin','api'])->namespace('\MicroweberPackages\Role\Http\Controllers\Admin')->group(function () {

    Route::resource('role', 'RolesController');

    Route::post('role/clone', [
        'as' => 'role.clone',
        'uses' => 'RolesController@cloneRole'
    ]);

});
