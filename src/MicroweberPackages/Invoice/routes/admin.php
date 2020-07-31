<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

// download invoice pdf with a unique_hash $id
// -------------------------------------------------
Route::get('/invoices/pdf/{id}', [
    'as' => 'get.invoice.pdf',
    'uses' => '\MicroweberPackages\Invoice\Http\Controllers\FrontendController@getInvoicePdf'
]);

/*
Route::group([
    'middleware' => 'admin'
], function () {

});*/
Route::prefix('admin')->namespace('\MicroweberPackages\Invoice\Http\Controllers\Admin')->group(function () {

    Route::post('/payments/delete', [
        'as' => 'payments.delete',
        'uses' => 'PaymentController@delete'
    ]);

    Route::post('/payments/send', [
        'as' => 'payments.send',
        'uses' => 'PaymentController@sendPayment'
    ]);

    Route::resource('payments', 'PaymentController');


    Route::post('/invoices/delete', [
        'as' => 'invoices.delete',
        'uses' => 'InvoicesController@delete'
    ]);

    Route::post('/invoices/send', [
        'as' => 'invoices.send',
        'uses' => 'InvoicesController@sendInvoice'
    ]);

    Route::post('/invoices/clone', [
        'as' => 'invoices.clone',
        'uses' => 'InvoicesController@cloneInvoice'
    ]);

    Route::post('/invoices/mark-as-paid', [
        'as' => 'invoices.paid',
        'uses' => 'InvoicesController@markAsPaid'
    ]);

    Route::post('/invoices/mark-as-sent', [
        'as' => 'invoices.sent',
        'uses' => 'InvoicesController@markAsSent'
    ]);

    Route::get('/invoices/unpaid/{id}', [
        'as' => 'bootstrap',
        'uses' => 'InvoicesController@getCustomersUnpaidInvoices'
    ]);

    Route::resource('invoices', 'InvoicesController');


    Route::post('/customers/delete', [
        'as' => 'customers.delete',
        'uses' => 'CustomersController@delete'
    ]);

    Route::resource('customers', 'CustomersController');

});