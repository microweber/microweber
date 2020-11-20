<?php


Route::prefix('admin')->middleware(['admin'])->namespace('\MicroweberPackages\ContentFilter\Http\Controllers')->group(function () {

    Route::resource('content/filter', 'ContentFilterAdminController');

});