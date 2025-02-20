<?php

use Illuminate\Support\Facades\Route;
use Modules\Rating\Http\Controllers\RatingController;

Route::prefix('api')->group(function () {
    Route::post('rating/Controller/save', [RatingController::class, 'save'])
        ->name('api.rating.save');
});
