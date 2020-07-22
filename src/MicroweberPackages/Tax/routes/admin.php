<?php


Route::prefix('admin')->namespace('\MicroweberPackages\Tax\Http\Controllers\Admin')->group(function () {

    Route::resource('tax-types', 'TaxTypeController');

});