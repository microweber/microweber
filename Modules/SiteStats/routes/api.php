<?php

use Illuminate\Support\Facades\Route;
use Modules\SiteStats\Http\Controllers\StatsController;

Route::middleware(['xss'])->group(function () {
    Route::post('api/pingstats', [StatsController::class, 'pingStats']);
});
