<?php

use  \Illuminate\Support\Facades\Route;


// 'middleware' => 'web',
Route::group(['middleware' => 'public.web', 'namespace' => '\MicroweberPackages\Admin\Http\Controllers'], function () {

    $admin_url_legacy = mw_admin_prefix_url_legacy();

    Route::any('/' . $admin_url_legacy, 'AdminController@dashboard')->name('admin.home');

    Route::any($admin_url_legacy, array('as' => 'admin', 'uses' => 'AdminController@dashboard'))->name('admin.index');

    Route::any('/editor_tools', 'AdminEditorToolsController@index');
    Route::any('editor_tools/{all}', array('as' => 'editor_tools', 'uses' => 'AdminEditorToolsController@index'))->where('all', '.*');


    Route::any($admin_url_legacy . '/{all}', array('as' => 'admin', 'uses' => 'AdminController@index'))->where('all', '.*')->name('admin.all');

//
//  moved to src/MicroweberPackages/MetaTags/Entities/AdminWebAppManifestTags.php
//    $admin_url = mw_admin_prefix_url();
//    Route::get($admin_url.'/manifest.json','AdminController@webAppManifest' )->name('admin.web-app-manifest');

});

