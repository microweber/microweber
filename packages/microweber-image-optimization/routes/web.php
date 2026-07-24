<?php

use Illuminate\Support\Facades\Route;
use MicroweberPackages\ImageOptimization\Http\Controllers\ImageOptimizationController;

/*
|--------------------------------------------------------------------------
| Image Optimization Routes
|--------------------------------------------------------------------------
|
| Loaded by ImageOptimizationServiceProvider.
|
*/

$middleware = config('image-optimization.middleware', ['web']);
if (!is_array($middleware)) {
    $middleware = ['web'];
}

Route::middleware($middleware)->group(function () {
    Route::get('image-optimization/webp', [ImageOptimizationController::class, 'serveWebp'])
        ->name('image-optimization.webp');

    Route::get('image-optimization/stats', [ImageOptimizationController::class, 'stats'])
        ->name('image-optimization.stats');

    Route::post('image-optimization/clear-cache', [ImageOptimizationController::class, 'clearCache'])
        ->name('image-optimization.clear-cache');

    Route::get('api/image-optimization/convert', [ImageOptimizationController::class, 'convert'])
        ->name('image-optimization.convert');
});
