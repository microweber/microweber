<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-67 / AI-53 / TICKET-E — login throttle named-limiter
 * regression coverage.
 *
 * Pins:
 *   - Both POST /login routes in src/MicroweberPackages/User/routes/api.php
 *     are wired onto `throttle:login` (NOT bare `throttle:60,1`).
 *   - The named `login` limiter is registered (declared in
 *     bootstrap/app.php and FortifyServiceProvider) — invoke it via
 *     RateLimiter::limiter('login') and assert it returns a 5/min
 *     Limit keyed on `login::<ip>::<email|username>`.
 *   - The bare `throttle:60,1` shape is GONE from those two routes —
 *     a regression to it would let a credential-stuffer rotate emails
 *     and bypass the per-account rate limit.
 *
 * Style after the cycle-52..66 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class LoginThrottleNamedLimiterContractTest extends TestCase
{
    private string $apiRoutesSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiRoutesSrc = file_get_contents(base_path(
            'src/MicroweberPackages/User/routes/api.php'
        ));
    }

    #[Test]
    public function both_post_login_routes_use_throttle_login_named_limiter(): void
    {
        // Route 1: api/v1/login → AuthController@login
        $this->assertMatchesRegularExpression(
            "/Route::post\\('login',\\s*\\\\MicroweberPackages\\\\User\\\\Http\\\\Controllers\\\\Api\\\\AuthController::class\\s*\\.\\s*'@login'\\)[^;]*->middleware\\(\\[[^\\]]*'throttle:login'/",
            $this->apiRoutesSrc,
            'AuthController@login route must use throttle:login (named limiter), not throttle:60,1'
        );

        // Route 2: api/login → UserLoginController@login
        $this->assertMatchesRegularExpression(
            "/Route::post\\('login',\\s*\\\\MicroweberPackages\\\\User\\\\Http\\\\Controllers\\\\UserLoginController::class\\.'@login'\\)[^;]*->middleware\\(\\[[^\\]]*'throttle:login'/",
            $this->apiRoutesSrc,
            'UserLoginController@login route must use throttle:login (named limiter), not throttle:60,1'
        );
    }

    #[Test]
    public function bare_throttle_60_1_is_gone_from_login_routes(): void
    {
        // The whole point of this ticket is that 60/min by-IP is too
        // permissive against credential stuffing. Pin that the bare
        // shape no longer appears NEAR a login route — guards against
        // future revert / cargo-culting.
        $loginAuthControllerPos = strpos(
            $this->apiRoutesSrc,
            "AuthController::class . '@login'"
        );
        $loginUserControllerPos = strpos(
            $this->apiRoutesSrc,
            "UserLoginController::class.'@login'"
        );
        $this->assertNotFalse($loginAuthControllerPos, 'AuthController@login route must exist');
        $this->assertNotFalse($loginUserControllerPos, 'UserLoginController@login route must exist');

        // Window: 200 chars after each route declaration must NOT
        // contain `throttle:60,1`. (200 chars covers the ->name(...)
        // ->middleware([...]) chain comfortably.)
        $authWindow = substr($this->apiRoutesSrc, $loginAuthControllerPos, 400);
        $userWindow = substr($this->apiRoutesSrc, $loginUserControllerPos, 400);
        $this->assertStringNotContainsString(
            'throttle:60,1',
            $authWindow,
            'AuthController@login route must not still carry throttle:60,1'
        );
        $this->assertStringNotContainsString(
            'throttle:60,1',
            $userWindow,
            'UserLoginController@login route must not still carry throttle:60,1'
        );
    }

    #[Test]
    public function named_login_limiter_is_registered_and_keyed_per_account(): void
    {
        // Boot Laravel's RateLimiter facade and ask for the `login`
        // limiter callback; build a fake request with a known
        // ip+email and assert the resulting Limit is 5/min keyed on
        // `login::<ip>::<email>`.
        $callback = RateLimiter::limiter('login');
        $this->assertNotNull(
            $callback,
            'RateLimiter::for(\'login\') must be registered (bootstrap/app.php or FortifyServiceProvider)'
        );

        $request = Request::create('/api/login', 'POST', [
            'email' => 'jane@example.com',
        ]);
        $request->server->set('REMOTE_ADDR', '198.51.100.7');

        $limit = $callback($request);
        // Some setups return an array; normalise.
        if (is_array($limit)) {
            $limit = $limit[0] ?? null;
        }
        $this->assertInstanceOf(
            Limit::class,
            $limit,
            'login limiter callback must return an Illuminate\\Cache\\RateLimiting\\Limit'
        );
        $this->assertSame(
            5,
            $limit->maxAttempts,
            'login limiter must allow 5 attempts per window'
        );
        $this->assertSame(
            60,
            $limit->decaySeconds,
            'login limiter window must be 60 seconds (per-minute limit)'
        );
        // The key must include both IP and email so a botnet rotating
        // IPs can still only test 5 passwords per minute against any
        // single account.
        $this->assertStringContainsString(
            'login::',
            (string) $limit->key,
            'login limiter key must be namespaced with `login::`'
        );
        $this->assertStringContainsString(
            '198.51.100.7',
            (string) $limit->key,
            'login limiter key must include the request IP'
        );
        $this->assertStringContainsString(
            'jane@example.com',
            (string) $limit->key,
            'login limiter key must include the email/username — per-account rate limiting closes the credential-stuffing rotation gap'
        );
    }
}
