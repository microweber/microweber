<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

// download invoice pdf with a unique_hash $id
// -------------------------------------------------
Route::middleware(['admin'])->get('/invoices/pdf/{id}', [
    'as' => 'get.invoice.pdf',
    'uses' => '\MicroweberPackages\Invoice\Http\Controllers\FrontendController@getInvoicePdf'
]);


Route::prefix('admin')->middleware(['admin'])->namespace('\MicroweberPackages\Invoice\Http\Controllers\Admin')->group(function () {

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


});