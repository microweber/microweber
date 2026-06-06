<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-06-06-adminapisession
 *
 * The Live Edit module/layout picker calls api/module/list (+ getSkins,
 * layout-preview). These routes are guarded by the `admin` middleware, which
 * authorises via is_admin() / Auth::check() — i.e. the session-backed auth
 * guard. But the route groups ran in the stateless `api` middleware group
 * (or with only `['admin']`), which never starts a session. So the logged-in
 * admin's session cookie was ignored and every request returned
 * 401 "Please as admin login to continue".
 *
 * In the browser this broke the Live Edit "+ ADD" content picker and the
 * Insert-Layout panel: the 401 surfaced to the user as a false
 * "Your session has expired" banner even though the admin was logged in.
 *
 * The fix prepends the cookie + session stack (EncryptCookies,
 * AddQueuedCookiesToResponse, StartSession) BEFORE the `admin` middleware on
 * these route groups, so `admin` can see the authenticated admin session.
 *
 * This contract pins that every admin-gated module API route both starts a
 * session AND keeps the `admin` guard, with the session middleware ordered
 * before `admin`.
 */
class ModuleAdminApiSessionMiddlewareContractTest extends TestCase
{
    /**
     * @return array<string, array{0: string}>
     */
    public static function adminModuleRouteNames(): array
    {
        return [
            'api.module.list' => ['api.module.list'],
            'api.module.getSkins' => ['api.module.getSkins'],
            'api.module.layout-preview' => ['api.module.layout-preview'],
        ];
    }

    #[Test]
    #[DataProvider('adminModuleRouteNames')]
    public function admin_module_route_starts_a_session_before_the_admin_guard(string $routeName): void
    {
        $route = Route::getRoutes()->getByName($routeName);
        $this->assertNotNull($route, "Route {$routeName} must exist.");

        $gathered = app('router')->gatherRouteMiddleware($route);

        $this->assertContains(
            StartSession::class,
            $gathered,
            "Route {$routeName} must start a session — the `admin` middleware authorises via the "
            . 'session-backed auth guard, so without StartSession the logged-in admin is invisible and the route 401s.'
        );
        $this->assertContains(
            EncryptCookies::class,
            $gathered,
            "Route {$routeName} must decrypt the session cookie (EncryptCookies) before StartSession reads it."
        );
        $this->assertContains(
            AddQueuedCookiesToResponse::class,
            $gathered,
            "Route {$routeName} must flush the session cookie back to the client."
        );

        // The `admin` guard must still be present (the fix authenticates the
        // session, it does NOT drop the admin authorisation). gatherRouteMiddleware
        // resolves the `admin` alias to its middleware class, so match the class.
        $adminIdx = null;
        foreach ($gathered as $i => $mw) {
            if (preg_match('/Middleware\\\\Admin$/', (string) $mw)) {
                $adminIdx = $i;
                break;
            }
        }
        $this->assertNotNull($adminIdx, "Route {$routeName} must keep the `admin` guard (Admin middleware).");

        // Ordering: StartSession must run BEFORE the `admin` guard so the
        // session is available when `admin` calls is_admin() / Auth::check().
        $startIdx = array_search(StartSession::class, $gathered, true);
        $this->assertNotFalse($startIdx);
        $this->assertLessThan(
            $adminIdx,
            $startIdx,
            "On {$routeName}, StartSession must be ordered before the `admin` guard."
        );
    }
}
