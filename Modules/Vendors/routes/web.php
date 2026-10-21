<?php

use Illuminate\Support\Facades\Route;
use Modules\Vendors\Http\Controllers\VendorController;

/*
|--------------------------------------------------------------------------
| Vendors — public web routes
|--------------------------------------------------------------------------
|
| Opt-in loaded from VendorsServiceProvider::register() via an explicit
| loadRoutesFrom() call (BaseModuleServiceProvider does not auto-load
| module web routes — same pattern as Shop/Search).
|
|   /vendors        → directory of approved vendor storefronts
|   /vendor/{slug}  → a single vendor's public shop page (their products)
|
*/

Route::middleware('web')->group(function () {
    Route::get('vendors', [VendorController::class, 'index'])->name('vendors.index');
    Route::get('vendor/{slug}', [VendorController::class, 'show'])->name('vendors.show');
});
