<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/


Route::prefix(ADMIN_PREFIX)->namespace('\MicroweberPackages\Payment\Http\Controllers\Admin')->group(function () {

    Route::post('/payments/delete', [
        'as' => 'payments.delete',
        'uses' => 'PaymentController@delete'
    ]);

    Route::post('/payments/send', [
        'as' => 'payments.send',
        'uses' => 'PaymentController@sendPayment'
    ]);

    Route::resource('payments', 'PaymentController');

});