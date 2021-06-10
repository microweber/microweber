<?php

Route::prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Blog\Http\Controllers')
    ->group(function () {

        Route::any('get-custom-fields-table', 'LiveEditAdminController@getCustomFieldsTableFromPage')->name('admin.blog.filter.get-custom-fields-table');

    });
