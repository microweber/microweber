<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/


Route::prefix(ADMIN_PREFIX)->namespace('\MicroweberPackages\Customer\Http\Controllers\Admin')->group(function () {

    Route::post('/customers/delete', [
        'as' => 'customers.delete',
        'uses' => 'CustomersController@delete'
    ]);

    Route::resource('customers', 'CustomersController');

});