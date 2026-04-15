<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthCheckController;

/*
|--------------------------------------------------------------------------
| Health Check Routes
|--------------------------------------------------------------------------
*/

Route::get('/health', [HealthCheckController::class, 'index'])->name('health.index');
Route::get('/health/database', [HealthCheckController::class, 'database'])->name('health.database');
Route::get('/health/cache', [HealthCheckController::class, 'cache'])->name('health.cache');
Route::get('/health/storage', [HealthCheckController::class, 'storage'])->name('health.storage');

/*
|--------------------------------------------------------------------------
| Authenticated API Routes (Passport)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
