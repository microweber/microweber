<?php

use Illuminate\Support\Facades\Route;
use Modules\Tabs\Http\Controllers\TabItemsController;

// task-2026-09-15-qskit — Tabs item CRUD for the Live-Edit inline list (admin-guarded).
Route::middleware(['admin'])->group(function () {
    Route::get('api/tab-items', [TabItemsController::class, 'index']);
    Route::post('api/tab-items', [TabItemsController::class, 'store']);
    Route::post('api/tab-items/reorder', [TabItemsController::class, 'reorder']);
    Route::post('api/tab-items/{id}', [TabItemsController::class, 'update']);
    Route::delete('api/tab-items/{id}', [TabItemsController::class, 'destroy']);
});
