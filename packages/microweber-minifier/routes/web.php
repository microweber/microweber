<?php

use Illuminate\Support\Facades\Route;
use MicroweberPackages\Minifier\Http\Controllers\MinifierController;

/*
|--------------------------------------------------------------------------
| Minifier Routes
|--------------------------------------------------------------------------
|
| Loaded by MinifierServiceProvider.
|
*/

$middleware = config('minifier.middleware', ['admin']);
if (!is_array($middleware)) {
    $middleware = ['admin'];
}

Route::middleware($middleware)->group(function () {
    Route::get('minifier/stats', [MinifierController::class, 'stats'])
        ->name('minifier.stats');

    Route::get('minifier/self-test', [MinifierController::class, 'selfTest'])
        ->name('minifier.self-test');

    Route::get('api/minifier/ping', [MinifierController::class, 'ping'])
        ->name('minifier.ping');

    Route::post('minifier/js', [MinifierController::class, 'minifyJs'])
        ->name('minifier.js');

    Route::post('minifier/css', [MinifierController::class, 'minifyCss'])
        ->name('minifier.css');
});
