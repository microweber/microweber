<?php

use Illuminate\Support\Facades\Route;
use Modules\Pictures\Http\Controllers\PictureItemsController;

// task-2026-09-15-qskit — Pictures gallery CRUD for the Live-Edit inline image
// list (admin-guarded). No {id} update route — images are picked, not text-edited.
Route::middleware(['admin'])->group(function () {
    Route::get('api/picture-items', [PictureItemsController::class, 'index']);
    Route::post('api/picture-items', [PictureItemsController::class, 'store']);
    Route::post('api/picture-items/reorder', [PictureItemsController::class, 'reorder']);
    Route::delete('api/picture-items/{id}', [PictureItemsController::class, 'destroy']);
});
