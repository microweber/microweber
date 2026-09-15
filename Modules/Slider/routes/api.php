<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\SliderSlidesController;

// task-2026-09-15-qskit — Slider slides CRUD for the Live-Edit inline list (admin-guarded).
Route::middleware(['admin'])->group(function () {
    Route::get('api/slider-slides', [SliderSlidesController::class, 'index']);
    Route::post('api/slider-slides', [SliderSlidesController::class, 'store']);
    Route::post('api/slider-slides/reorder', [SliderSlidesController::class, 'reorder']);
    Route::post('api/slider-slides/{id}', [SliderSlidesController::class, 'update']);
    Route::delete('api/slider-slides/{id}', [SliderSlidesController::class, 'destroy']);
});
