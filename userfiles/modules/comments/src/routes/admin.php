<?php

Route::name('admin.comments.')
    ->prefix(ADMIN_PREFIX . '/comments')
    ->middleware(['admin'])
    ->namespace('MicroweberPackages\Modules\Comments\Http\Controllers\Admin')
    ->group(function () {

       Route::get('/', 'AdminCommentsController@index')->name('index');

    });
