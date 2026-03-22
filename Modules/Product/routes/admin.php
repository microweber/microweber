<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Admin\ProductExportController;

Route::middleware(['web', 'auth', 'admin'])
    ->prefix('admin/product')
    ->name('filament.admin.product.')
    ->group(function () {
        Route::get('/export', [ProductExportController::class, 'export'])->name('export');
    });
