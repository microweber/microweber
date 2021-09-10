<?php


Route::prefix(ADMIN_PREFIX)->namespace('\MicroweberPackages\Tax\Http\Controllers\Admin')->middleware(['admin'])->group(function () {

    Route::resource('tax-types', 'TaxTypeController');

});
