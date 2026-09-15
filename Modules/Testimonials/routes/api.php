<?php

use Illuminate\Support\Facades\Route;
use Modules\Testimonials\Http\Controllers\TestimonialItemsController;

// task-2026-09-15-qskit — Testimonials item CRUD for the Live-Edit inline list (admin-guarded).
Route::middleware(['admin'])->group(function () {
    Route::get('api/testimonial-items', [TestimonialItemsController::class, 'index']);
    Route::post('api/testimonial-items', [TestimonialItemsController::class, 'store']);
    Route::post('api/testimonial-items/reorder', [TestimonialItemsController::class, 'reorder']);
    Route::post('api/testimonial-items/{id}', [TestimonialItemsController::class, 'update']);
    Route::delete('api/testimonial-items/{id}', [TestimonialItemsController::class, 'destroy']);
});
