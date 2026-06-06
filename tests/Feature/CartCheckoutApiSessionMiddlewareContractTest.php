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
 * task-2026-06-06-cartsession
 *
 * The REST cart + checkout endpoints are session-backed: CartService scopes
 * every cart row by `user_manager->session_id()` (=== Session::getId()), and
 * CheckoutApiController reads the cart the same way before placing an order.
 *
 * But the route groups were declared with only the stateless `api`
 * middleware group (and, for the ecommerce-api.php groups, required inside
 * `Route::middleware('api')` in bootstrap/app.php). Laravel's `api` group
 * does NOT start a session, so `Session::getId()` minted a fresh, never-
 * persisted id on every request. The result: an item added by
 * `POST /api/cart` was written under one session id, and the next
 * `GET /api/cart` (or `POST /api/checkout`) read under a different id and saw
 * an empty cart — the REST cart never survived a single round trip, and REST
 * checkout always failed with "Cart is empty".
 *
 * The fix adds the cookie + session middleware stack (EncryptCookies,
 * AddQueuedCookiesToResponse, StartSession) to each of these route groups,
 * mirroring the legacy web-group endpoints (api/update_cart, api/cart_sum)
 * that already work because the `web` group starts a session.
 *
 * This contract pins that every session-dependent cart/checkout route gathers
 * the session middleware, so the stateless-session regression cannot return.
 */
class CartCheckoutApiSessionMiddlewareContractTest extends TestCase
{
    /**
     * Every cart/checkout REST route that reads the session-scoped cart.
     *
     * @return array<string, array{0: string}>
     */
    public static function sessionBackedRouteNames(): array
    {
        return [
            // ecommerce-api.php (api/cart, api/checkout)
            'api.cart.index' => ['api.cart.index'],
            'api.cart.store' => ['api.cart.store'],
            'api.cart.totals' => ['api.cart.totals'],
            'api.checkout.index' => ['api.checkout.index'],
            'api.checkout.store' => ['api.checkout.store'],
            // Modules/Cart/routes/api.php (api/module/cart)
            'api.module.cart.index' => ['api.module.cart.index'],
            'api.module.cart.store' => ['api.module.cart.store'],
            // Modules/Checkout/routes/api.php (api/module/checkout)
            'api.module.checkout.store' => ['api.module.checkout.store'],
        ];
    }

    #[Test]
    #[DataProvider('sessionBackedRouteNames')]
    public function session_backed_route_gathers_the_session_middleware(string $routeName): void
    {
        $route = Route::getRoutes()->getByName($routeName);
        $this->assertNotNull($route, "Route {$routeName} must exist.");

        $gathered = app('router')->gatherRouteMiddleware($route);

        $this->assertContains(
            StartSession::class,
            $gathered,
            "Route {$routeName} must start a session — the cart/checkout services scope by Session::getId(), "
            . 'so without StartSession the cart is written and read under different, non-persisted session ids.'
        );
        $this->assertContains(
            EncryptCookies::class,
            $gathered,
            "Route {$routeName} must decrypt the incoming session cookie (EncryptCookies) so StartSession reads the right id."
        );
        $this->assertContains(
            AddQueuedCookiesToResponse::class,
            $gathered,
            "Route {$routeName} must flush the session cookie back to the client (AddQueuedCookiesToResponse)."
        );
    }

    /**
     * Guard: a read-only product route stays stateless. The fix is scoped to
     * the session-backed cart/checkout groups; it must NOT bleed onto the
     * public product catalogue (which needs no session).
     */
    #[Test]
    public function product_catalogue_route_stays_stateless(): void
    {
        $route = Route::getRoutes()->getByName('api.products.index');
        $this->assertNotNull($route, 'Route api.products.index must exist.');

        $gathered = app('router')->gatherRouteMiddleware($route);

        $this->assertNotContains(
            StartSession::class,
            $gathered,
            'The product catalogue is read-only and must stay stateless — the session stack is reserved for cart/checkout.'
        );
    }
}
