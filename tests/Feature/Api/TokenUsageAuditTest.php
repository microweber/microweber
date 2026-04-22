<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\AccessToken;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Verifies `token.audit` middleware stamps `last_used_at` on the
 * oauth_access_tokens row belonging to the Passport access token that
 * authenticated the request.
 *
 * The stamp is throttled by a Cache::add mutex whose TTL is controlled
 * by `passport.token_usage_stamp_interval_seconds` so that a noisy
 * token does not produce one write per request.
 */
final class TokenUsageAuditTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'token-audit-' . uniqid() . '@example.com',
            'is_admin' => 1,
            'is_active' => 1,
        ]);

        // Each test needs a fresh mutex, otherwise the interval-guard from
        // a previous test can swallow the write we're trying to assert.
        Cache::flush();
    }

    /**
     * Insert a real oauth_access_tokens row owned by the test user so the
     * middleware has something to update, and bind it to the `api` guard
     * as the "current" Passport token.
     */
    private function actAsToken(string $tokenId, array $scopes = ['*']): void
    {
        DB::table('oauth_access_tokens')->updateOrInsert(
            ['id' => $tokenId],
            [
                'user_id' => $this->admin->getAuthIdentifier(),
                'client_id' => '00000000-0000-0000-0000-000000000000',
                'name' => 'Audit test token',
                'scopes' => json_encode($scopes),
                'revoked' => false,
                'created_at' => now(),
                'updated_at' => now(),
                'expires_at' => now()->addDay(),
                'last_used_at' => null,
                'last_used_ip' => null,
            ]
        );

        $this->admin->withAccessToken(new AccessToken([
            'oauth_access_token_id' => $tokenId,
            'oauth_user_id' => $this->admin->getAuthIdentifier(),
            'oauth_scopes' => $scopes,
        ]));

        app('auth')->guard('api')->setUser($this->admin);
        app('auth')->shouldUse('api');
    }

    #[Test]
    public function authenticated_request_stamps_last_used_at_and_ip(): void
    {
        $tokenId = 'audit-single-' . uniqid();

        $this->actAsToken($tokenId);
        $before = now()->subSecond();

        $this->getJson('/api/module/profile')->assertStatus(200);

        $row = DB::table('oauth_access_tokens')->where('id', $tokenId)->first();

        $this->assertNotNull($row->last_used_at, 'last_used_at must be stamped after an auth:api hit');
        $this->assertTrue(
            \Carbon\Carbon::parse($row->last_used_at)->greaterThanOrEqualTo($before),
            'last_used_at must be set to the moment the request was served'
        );
        $this->assertNotEmpty($row->last_used_ip, 'last_used_ip must also be captured');
    }

    #[Test]
    public function stamp_is_throttled_within_the_configured_interval(): void
    {
        // 10 minutes is long enough that the second call in this test
        // must not trigger a second DB write.
        config()->set('passport.token_usage_stamp_interval_seconds', 600);

        $tokenId = 'audit-throttle-' . uniqid();
        $this->actAsToken($tokenId);

        $this->getJson('/api/module/profile')->assertStatus(200);

        $firstStamp = DB::table('oauth_access_tokens')
            ->where('id', $tokenId)
            ->value('last_used_at');

        // Second request arrives well within the throttling window — the
        // DB must NOT be touched, otherwise the mutex is not doing its job.
        $this->getJson('/api/module/profile')->assertStatus(200);

        $secondStamp = DB::table('oauth_access_tokens')
            ->where('id', $tokenId)
            ->value('last_used_at');

        $this->assertSame(
            $firstStamp,
            $secondStamp,
            'last_used_at must NOT be rewritten within the configured throttle window'
        );
    }

    #[Test]
    public function cleared_mutex_allows_a_second_stamp(): void
    {
        config()->set('passport.token_usage_stamp_interval_seconds', 60);

        $tokenId = 'audit-reclear-' . uniqid();
        $this->actAsToken($tokenId);

        $this->getJson('/api/module/profile')->assertStatus(200);
        $first = DB::table('oauth_access_tokens')->where('id', $tokenId)->value('last_used_at');

        // Simulate enough time passing for the throttle cache to expire.
        Cache::forget('passport:token_last_used:' . $tokenId);

        // Force a stamp distinguishable from $first regardless of clock
        // precision by travelling forward several seconds.
        $this->travel(5)->seconds();

        $this->actAsToken($tokenId);
        $this->getJson('/api/module/profile')->assertStatus(200);

        $second = DB::table('oauth_access_tokens')->where('id', $tokenId)->value('last_used_at');

        $this->assertNotSame(
            $first,
            $second,
            'After the mutex expires the next request must stamp a fresh last_used_at'
        );
    }

    #[Test]
    public function public_request_does_not_touch_any_token_row(): void
    {
        // Seed a dummy token so we can assert its last_used_at stays null.
        $tokenId = 'audit-public-' . uniqid();
        DB::table('oauth_access_tokens')->insert([
            'id' => $tokenId,
            'user_id' => $this->admin->getAuthIdentifier(),
            'client_id' => '00000000-0000-0000-0000-000000000000',
            'name' => 'Untouched',
            'scopes' => json_encode(['*']),
            'revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addDay(),
            'last_used_at' => null,
            'last_used_ip' => null,
        ]);

        // A read on /api/module/content is public — no auth:api, no token
        // middleware — so nothing should be stamped on our token row.
        $this->getJson('/api/module/content')->assertSuccessful();

        $row = DB::table('oauth_access_tokens')->where('id', $tokenId)->first();
        $this->assertNull($row->last_used_at);
        $this->assertNull($row->last_used_ip);
    }
}
