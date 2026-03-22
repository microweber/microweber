<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
//return Application::configure(basePath: dirname(__DIR__))
return \MicroweberPackages\App\LaravelApplication::configure(basePath: dirname(__DIR__))
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        // Load additional API routes
        Route::middleware('api')->group(function () {
            require __DIR__.'/../routes/ecommerce-api.php';
        });
    }
  )
->withMiddleware(function (Middleware $middleware) {
    //
})
->withExceptions(function (Exceptions $exceptions) {
    //
})
->booted(function () {
    // Configure API rate limiting
    RateLimiter::for('api', function (Request $request) {
        return RateLimiter::limit(
            $request->user()?->getMorphClass() . '::' . $request->user()?->id ?? $request->ip(),
            60,
            1
        );
    });
})->create();
