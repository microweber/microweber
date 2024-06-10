<?php


Route::name('admin.module.')
    ->prefix(mw_admin_prefix_url_legacy())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Module\Http\Controllers\Admin')
    ->group(function () {

        Route::get('modules', 'AdminModuleController@index')->name('index');
        Route::get('module/view', 'AdminModuleController@view')->name('view');

    });
