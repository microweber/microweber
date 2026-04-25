<?php

declare(strict_types=1);

namespace MicroweberPackages\Module\Routing;

use Illuminate\Support\Facades\Route;

/**
 * Helper for registering a module's headless `/api/module/{slug}/*`
 * REST surface from the module's own `routes/api.php` file.
 *
 * Pre-existing pattern (`routes/module-api.php`) declared every
 * module's REST block in one global file, which coupled bootstrap
 * to module knowledge that should live inside each module. This
 * helper replaces the global loop with per-module
 * `ModuleApiRoutes::register('content', ContentApiController::class, 'content')`
 * calls in each module's `routes/api.php`.
 *
 * Reads are public (rate-limited by IP), writes require a Passport
 * token belonging to a user with `is_admin = 1`. Each controller
 * performs its own admin check against `$request->user()` so the
 * same controller can be reused across authenticated and
 * unauthenticated surfaces.
 *
 * Aliased slugs (e.g. `contact-form` → `forms`) reuse the canonical
 * scope prefix instead of declaring their own. Pass `$scope` to
 * override; otherwise it defaults to the slug.
 */
class ModuleApiRoutes
{
    /**
     * Register the standard REST block under `/api/module/{slug}/*`
     * for one module. Idempotent: registering the same slug twice
     * is harmless (Laravel will overwrite the previous binding,
     * matching the global-file historic behaviour).
     *
     * @param string      $slug       URL slug (e.g. 'content', 'pages').
     * @param string      $controller Fully-qualified controller class.
     * @param string      $binding    Route-model-binding parameter name
     *                                (e.g. 'content' resolves to `{content}`).
     * @param string|null $scope      Override the Passport-scope prefix
     *                                used on write routes. Defaults to
     *                                `$slug`. Useful for aliases.
     */
    public static function register(string $slug, string $controller, string $binding, ?string $scope = null): void
    {
        $scope ??= $slug;

        Route::prefix("api/module/{$slug}")
            ->middleware(['api', 'throttle:public'])
            ->name("api.module.{$slug}.")
            ->group(function () use ($controller, $binding) {
                Route::get('/', [$controller, 'index'])->name('index');
                Route::get('/{' . $binding . '}', [$controller, 'show'])->name('show');
            });

        Route::prefix("api/module/{$slug}")
            ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', "scope:{$scope}:write"])
            ->name("api.module.{$slug}.")
            ->group(function () use ($controller, $binding) {
                Route::post('/', [$controller, 'store'])->name('store');
                Route::put('/{' . $binding . '}', [$controller, 'update'])->name('update');
                Route::patch('/{' . $binding . '}', [$controller, 'update'])->name('update.partial');
                Route::delete('/{' . $binding . '}', [$controller, 'destroy'])->name('destroy');
            });
    }
}
