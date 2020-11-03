<?php

namespace MicroweberPackages\App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \MicroweberPackages\App\Http\Middleware\TrustHosts::class,
        \MicroweberPackages\App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \MicroweberPackages\App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \MicroweberPackages\App\Http\Middleware\TrimStrings::class,
        \Illuminate\Session\Middleware\StartSession::class, //TODO our routers must be added on web middleware
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \MicroweberPackages\App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \MicroweberPackages\App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'xss',
            'throttle:111116,1',
            'api_auth'
        ],
        'public.api' => [
            'xss',
            'throttle:161111,1',
            //  EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'static.api' => [
            \MicroweberPackages\App\Http\Middleware\SessionlessMiddleware::class,
            \Illuminate\Http\Middleware\CheckResponseForModifications::class
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \MicroweberPackages\App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \MicroweberPackages\App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        // 'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'throttle' => \MicroweberPackages\App\Http\Middleware\ThrottleExternalRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'xss' => \MicroweberPackages\App\Http\Middleware\XSS::class,
        'remove_html' => \MicroweberPackages\App\Http\Middleware\RemoveHtml::class,
        'admin' => \MicroweberPackages\App\Http\Middleware\Admin::class,
        'api_auth' => \MicroweberPackages\App\Http\Middleware\ApiAuth::class,
        'allowed_ips' => \MicroweberPackages\App\Http\Middleware\AllowedIps::class,
    ];
}