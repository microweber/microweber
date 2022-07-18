<?php




// 'middleware' => 'web',
Route::group(['middleware' => 'public.web', 'namespace' => '\MicroweberPackages\Admin\Http\Controllers'], function () {

    $custom_admin_url = \Config::get('microweber.admin_url');
    $admin_url = 'admin';
    if ($custom_admin_url) {
        $admin_url = $custom_admin_url;
    }

    Route::any('/' . $admin_url, 'AdminController@index')->name('admin.home');
    Route::any($admin_url, array('as' => 'admin', 'uses' => 'AdminController@index'));

    Route::any($admin_url . '/{all}', array('as' => 'admin', 'uses' => 'AdminController@index'))->where('all', '.*')->name('admin.all');


});

