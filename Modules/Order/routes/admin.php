<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Admin\OrderExportController;

Route::middleware(['web', 'auth', 'admin'])
    ->prefix('admin/order')
    ->name('filament.admin.order.')
    ->group(function () {
        Route::get('/export', [OrderExportController::class, 'export'])->name('export');
    });
