<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Passport\AccessToken;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Verifies the per-Passport-token rate limiter wired onto `/api/module/*`
 * authenticated routes.
 *
 * The limiter:
 *   - keys by `oauth_access_token_id` so two tokens owned by the same user
 *     each get their own independent bucket
 *   - reads its ceiling from `config('passport.per_token_rate_limit_per_minute')`
 *   - degrades to a per-user bucket when the caller is authenticated without
 *     a Passport token id (session/cookie surface)
 */
final class PerTokenRateLimitTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'per-token-rate-' . uniqid() . '@example.com',
            'is_admin' => 1,
            'is_active' => 1,
        ]);

        // Throttle middleware uses the cache-backed RateLimiter; a single
        // leftover bucket from a prior test would poison the counters and
        // make the assertions flaky.
        Cache::flush();
    }

    /**
     * Authenticate the test user via the `api` guard with a synthetic
     * AccessToken carrying the given id, so the throttle:token middleware
     * can key its bucket.
     */
    private function actAsTokenId(string $tokenId, array $scopes = ['*']): void
    {
        $this->admin->withAccessToken(new AccessToken([
            'oauth_access_token_id' => $tokenId,
            'oauth_user_id' => $this->admin->getAuthIdentifier(),
            'oauth_scopes' => $scopes,
        ]));

        app('auth')->guard('api')->setUser($this->admin);
        app('auth')->shouldUse('api');
    }

    #[Test]
    public function single_token_returns_429_after_exceeding_the_configured_burst(): void
    {
        config()->set('passport.per_token_rate_limit_per_minute', 2);

        $tokenId = 'token-single-' . uniqid();

        $this->actAsTokenId($tokenId);
        $this->getJson('/api/module/profile')->assertStatus(200);

        $this->actAsTokenId($tokenId);
        $this->getJson('/api/module/profile')->assertStatus(200);

        $this->actAsTokenId($tokenId);
        $this->getJson('/api/module/profile')->assertStatus(429);
    }

    #[Test]
    public function two_tokens_owned_by_the_same_user_do_not_share_a_bucket(): void
    {
        config()->set('passport.per_token_rate_limit_per_minute', 1);

        $tokenA = 'token-A-' . uniqid();
        $tokenB = 'token-B-' . uniqid();

        // Token A burns its single allowed hit, next request is throttled.
        $this->actAsTokenId($tokenA);
        $this->getJson('/api/module/profile')->assertStatus(200);

        $this->actAsTokenId($tokenA);
        $this->getJson('/api/module/profile')->assertStatus(429);

        // Token B should have an untouched bucket even though it belongs
        // to the same user — that is the whole point of per-token keying.
        $this->actAsTokenId($tokenB);
        $this->getJson('/api/module/profile')->assertStatus(200);
    }

    #[Test]
    public function rate_limit_closure_falls_back_to_user_bucket_when_no_token_id_is_present(): void
    {
        config()->set('passport.per_token_rate_limit_per_minute', 7);

        // Simulate a session/cookie-authed caller — user resolved, but no
        // Passport access token attached to the request.
        $limiter = RateLimiter::limiter('token');
        $this->assertNotNull($limiter, 'throttle:token limiter should be registered');

        $request = \Illuminate\Http\Request::create('/api/module/profile', 'GET');
        $request->setUserResolver(fn () => $this->admin);

        $limit = $limiter($request);

        $this->assertSame(7, $limit->maxAttempts);
        $this->assertStringStartsWith('user::', $limit->key);
        $this->assertStringContainsString((string) $this->admin->getAuthIdentifier(), $limit->key);
    }

    #[Test]
    public function rate_limit_closure_returns_unlimited_for_anonymous_requests(): void
    {
        $limiter = RateLimiter::limiter('token');
        $this->assertNotNull($limiter);

        $request = \Illuminate\Http\Request::create('/api/module/profile', 'GET');

        $limit = $limiter($request);

        // Laravel's Limit::none() uses PHP_INT_MAX to express "unlimited"; we
        // only care that we did not construct a low cap that could block a
        // legitimate public route if this limiter were ever mis-applied.
        $this->assertSame(PHP_INT_MAX, $limit->maxAttempts);
    }
}
