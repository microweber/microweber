<?php
use \Illuminate\Support\Facades\Route;
//
// legacy route
//Route::group(['middleware' => 'admin', 'namespace' => '\MicroweberPackages\LiveEdit\Http\Controllers'], function () {
//
//    $admin_url = mw_admin_prefix_url();
//
//    $live_edit_url = 'live-edit';
//
//    Route::any('/' . $admin_url . '/' . $live_edit_url, 'LiveEditIframeController@index')
//        ->name('admin.live-edit.index')
//        ->middleware(['live_edit', 'admin']);
//
//});

/*
Route::group(['middleware' => 'admin', 'namespace' =>
    '\MicroweberPackages\LiveEdit\Http\Controllers'], function () {

    $admin_url = mw_admin_prefix_url();

    $live_edit_url = 'live-edit-page';

    Route::any('/' . $admin_url . '/' . $live_edit_url, function () {
        return redirect(admin_url('live-edit'));
    });


});*/
