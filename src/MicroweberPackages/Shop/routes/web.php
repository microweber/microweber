<?php


Route::prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Shop\Http\Controllers')
    ->group(function () {

        Route::any('get-custom-fields-table', 'LiveEditAdminController@getCustomFieldsTableFromPage')->name('admin.shop.filter.get-custom-fields-table');

    });
