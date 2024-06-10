<?php

Route::name('admin.comments.')
    ->prefix(mw_admin_prefix_url_legacy() . '/comments')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Comments\Http\Controllers\Admin')
    ->group(function () {

       Route::get('/', 'AdminCommentsController@index')->name('index');

    });
