<?php

use Illuminate\Support\Facades\Route;
use Modules\Skills\Http\Controllers\SkillItemsController;

// task-2026-09-15-qskit — Skills item CRUD (JSON-option backed) for the
// Live-Edit inline list (admin-guarded).
Route::middleware(['admin'])->group(function () {
    Route::get('api/skill-items', [SkillItemsController::class, 'index']);
    Route::post('api/skill-items', [SkillItemsController::class, 'store']);
    Route::post('api/skill-items/reorder', [SkillItemsController::class, 'reorder']);
    Route::post('api/skill-items/{id}', [SkillItemsController::class, 'update']);
    Route::delete('api/skill-items/{id}', [SkillItemsController::class, 'destroy']);
});
