<?php

use Illuminate\Support\Facades\Route;
use Modules\DigitalProduct\Http\Controllers\DownloadController;

Route::middleware('web')->group(function () {
    // Secure tokenized delivery of a purchased file. The token is the secret;
    // the controller enforces limit + expiry and records each download.
    Route::get('download/{token}', [DownloadController::class, 'show'])
        ->name('digital-product.download');
});
