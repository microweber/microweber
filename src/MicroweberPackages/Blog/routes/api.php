<?php

Route::name('admin.blog.filter.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Blog\Http\Controllers')
    ->group(function () {

        Route::post('get-custom-fields-table', 'LiveEditAdminController@getCustomFieldsTableFromPage')->name('get-custom-fields-table');

    });
