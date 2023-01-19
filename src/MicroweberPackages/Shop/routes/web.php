<?php


Route::name('admin.')
    ->prefix(ADMIN_PREFIX)
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Shop\Http\Controllers\Admin')
    ->group(function () {
        Route::get('shop/dashboard', 'DashboardShopController@dashboard')->name('shop.dashboard');
    });

Route::name('admin.shop.filter.')
    ->prefix(ADMIN_PREFIX. '/shop/filter')
    ->middleware(['admin'])
    ->namespace('\MicroweberPackages\Shop\Http\Controllers')
    ->group(function () {

        Route::post('get-custom-fields-table', 'LiveEditAdminController@getCustomFieldsTableFromPage')->name('get-custom-fields-table');

    });


