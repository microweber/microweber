<?php
use  \Illuminate\Support\Facades\Route;


Route::name('admin.module.')
    ->prefix(mw_admin_prefix_url_legacy())
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Module\Http\Controllers\Admin')
    ->group(function () {

        Route::get('modules', 'AdminModuleController@index')->name('index');
        Route::get('module/view', 'AdminModuleController@view')->name('view');

        // AI-71 / TICKET-AA (cycle-84 2026-05-08): module-zoo dev/QA
        // page that auto-discovers every module + each of its skins
        // and renders them in one scrollable page so reviewers can
        // catch visual regressions across all skins at once. Admin
        // middleware gates the route — non-admins get a 404.
        Route::get('module-zoo', 'AdminModuleController@zoo')->name('zoo');

    });
