<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthCheckController;

//Route::get('/user', function (Request $request) {
// return $request->user();
//})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Health Check Routes
|--------------------------------------------------------------------------
|
| These routes provide health check endpoints for monitoring the
| application's health and dependencies.
|
*/

Route::get('/health', [HealthCheckController::class, 'index'])->name('health.index');
Route::get('/health/database', [HealthCheckController::class, 'database'])->name('health.database');
Route::get('/health/cache', [HealthCheckController::class, 'cache'])->name('health.cache');
Route::get('/health/storage', [HealthCheckController::class, 'storage'])->name('health.storage');
