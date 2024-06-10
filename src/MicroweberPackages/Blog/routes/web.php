<?php

Route::name('admin.blog.filter.')
    ->prefix(mw_admin_prefix_url_legacy() . '/blog/filter')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Blog\Http\Controllers')
    ->group(function () {

        Route::any('get-custom-fields-table', 'LiveEditAdminController@getCustomFieldsTableFromPage')->name('get-custom-fields-table');

    });
