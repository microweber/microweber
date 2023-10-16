<?php



// 'middleware' => 'web',
Route::group(['middleware' => 'public.web', 'namespace' => '\MicroweberPackages\Admin\Http\Controllers'], function () {

    $admin_url = mw_admin_prefix_url();

    Route::any('/' . $admin_url, 'AdminController@dashboard')->name('admin.home');
    Route::any($admin_url, array('as' => 'admin', 'uses' => 'AdminController@dashboard'))->name('admin.index');

    Route::any('/editor_tools', 'AdminEditorToolsController@index');
    Route::any('editor_tools/{all}', array('as' => 'editor_tools', 'uses' => 'AdminEditorToolsController@index'))->where('all', '.*');


    Route::any($admin_url . '/{all}', array('as' => 'admin', 'uses' => 'AdminController@index'))->where('all', '.*')->name('admin.all');


});

