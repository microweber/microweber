<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqItemsController;

// task-2026-09-15-qskit — FAQ item CRUD for the Live-Edit inline list (admin-guarded).
Route::middleware(['admin'])->group(function () {
    Route::get('api/faq-items', [FaqItemsController::class, 'index']);
    Route::post('api/faq-items', [FaqItemsController::class, 'store']);
    Route::post('api/faq-items/reorder', [FaqItemsController::class, 'reorder']);
    Route::post('api/faq-items/{id}', [FaqItemsController::class, 'update']);
    Route::delete('api/faq-items/{id}', [FaqItemsController::class, 'destroy']);
});
