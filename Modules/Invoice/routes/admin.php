<?php

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Http\Controllers\Admin\InvoiceExportController;

Route::name('filament.admin.')
    ->prefix(mw_admin_prefix_url() . '/modules/invoice')
    ->middleware(['admin'])
    ->group(function () {
        // Export Routes
        Route::get('/export/invoices', [InvoiceExportController::class, 'export'])->name('export.invoices');
    });
