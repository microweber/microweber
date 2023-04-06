<?php


Route::name('admin.module.')
    ->prefix(mw_admin_prefix_url().'/module')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Module\Http\Controllers\Admin')
    ->group(function () {

        Route::get('/', 'AdminModuleController@index')->name('index');
        Route::get('/view', 'AdminModuleController@view')->name('view');

    });
