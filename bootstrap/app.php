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
    // Defensive security headers on every response (CSP frame-ancestors,
    // X-Frame-Options, X-Content-Type-Options, Referrer-Policy). Closes
    // the OWASP A04 gaps flagged in OOYES_AUDITS/01_SECURITY_AUDITOR.md.
    $middleware->append(\MicroweberPackages\App\Http\Middleware\SecurityHeaders::class);
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

    // Brute-force defence for login routes (OWASP A07 / OOYES_AUDITS A07).
    // Five attempts per minute keyed by IP + (email|username) so a single
    // attacker IP cannot enumerate one account, and so a shared corporate
    // NAT cannot lock all its users out by hitting the same email key.
    // Apply with `->middleware('throttle:login')` on the POST login route.
    \Illuminate\Support\Facades\RateLimiter::for('login', function (Request $request) {
        $key = mb_strtolower((string) ($request->input('email')
            ?? $request->input('username')
            ?? ''));

        return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)
            ->by('login::' . $request->ip() . '::' . $key);
    });

    // Per-Passport-token rate limiter. Applied alongside `throttle:api` on
    // authenticated module routes so a single rogue token can be throttled
    // without burning the whole user's budget, and so two tokens owned by
    // the same user each get their own independent bucket.
    //
    // Runs after auth:api, but we still handle the "no token" case so the
    // limiter stays safe if it's ever applied to a public route.
    RateLimiter::for('token', function (Request $request) {
        $user = $request->user();

        if (! $user) {
            return \Illuminate\Cache\RateLimiting\Limit::none();
        }

        $accessToken = method_exists($user, 'token') ? $user->token() : null;
        $tokenId = $accessToken?->oauth_access_token_id ?? $accessToken?->id;

        $perMinute = (int) config('passport.per_token_rate_limit_per_minute', 120);

        if ($tokenId) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute($perMinute)
                ->by('token::' . $tokenId);
        }

        // Session/cookie-authed callers don't have a Passport token id; fall
        // back to a per-user bucket so the middleware degrades gracefully.
        return \Illuminate\Cache\RateLimiting\Limit::perMinute($perMinute)
            ->by('user::' . $user->getMorphClass() . '::' . $user->getAuthIdentifier());
    });
})->create();
