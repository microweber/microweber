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

        // Headless module API — /api/module/{module}/* namespace
        require __DIR__.'/../routes/module-api.php';
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
        return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by(
            $request->user()?->getMorphClass() . '::' . $request->user()?->id ?? $request->ip()
        );
    });

    // Configure public rate limiter for unauthenticated API routes
    RateLimiter::for('public', function (Request $request) {
        return \Illuminate\Cache\RateLimiting\Limit::perMinute(100)->by(
            'public::' . $request->ip()
        );
    });
})->create();
