<?php

use Illuminate\Support\Facades\Route;
use Modules\Teamcard\Http\Controllers\TeamcardItemsController;

// task-2026-09-15-qskit — Teamcard item CRUD for the Live-Edit inline list (admin-guarded).
Route::middleware(['admin'])->group(function () {
    Route::get('api/teamcard-items', [TeamcardItemsController::class, 'index']);
    Route::post('api/teamcard-items', [TeamcardItemsController::class, 'store']);
    Route::post('api/teamcard-items/reorder', [TeamcardItemsController::class, 'reorder']);
    Route::post('api/teamcard-items/{id}', [TeamcardItemsController::class, 'update']);
    Route::delete('api/teamcard-items/{id}', [TeamcardItemsController::class, 'destroy']);
});
