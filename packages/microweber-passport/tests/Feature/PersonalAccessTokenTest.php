<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

final class PersonalAccessTokenTest extends \MicroweberPackages\Passport\Tests\TestCase
{
    protected function createUser(string $email): object
    {
        $userModel = config('auth.providers.users.model');
        $existing = $userModel::where('email', $email)->first();
        if ($existing) {
            return $existing;
        }

        return $userModel::create([
            'name' => 'Test ' . $email,
            'email' => $email,
            'password' => Hash::make('secret'),
        ]);
    }

    #[Test]
    public function user_can_create_personal_access_token(): void
    {
        $user = $this->createUser('pat-create-' . uniqid() . '@example.com');

        $result = $user->createToken('Test Token');

        $this->assertNotEmpty($result->accessToken, 'Should return an access token string');
        $this->assertNotNull($result->token, 'Should have a token model');
        $this->assertSame('Test Token', $result->token->name);
    }

    #[Test]
    public function token_authenticates_api_request(): void
    {
        $email = 'pat-auth-' . uniqid() . '@example.com';
        $user = $this->createUser($email);

        $token = $user->createToken('Auth Token')->accessToken;

        $response = $this->withToken($token)->getJson('/api/user');

        $response->assertOk();
        $response->assertJsonFragment(['email' => $email]);
    }

    #[Test]
    public function multiple_tokens_per_user(): void
    {
        $user = $this->createUser('pat-multi-' . uniqid() . '@example.com');

        $token1 = $user->createToken('Token 1')->accessToken;
        $token2 = $user->createToken('Token 2', ['content:read'])->accessToken;

        $this->assertNotSame($token1, $token2, 'Each token should be unique');

        // Both should work
        $this->withToken($token1)->getJson('/api/user')->assertOk();
        $this->withToken($token2)->getJson('/api/user')->assertOk();
    }

    #[Test]
    public function token_scopes_are_recorded(): void
    {
        $user = $this->createUser('pat-scopes-' . uniqid() . '@example.com');

        $result = $user->createToken('Scoped Token', ['content:read', 'pages:write']);

        $this->assertSame(['content:read', 'pages:write'], $result->token->scopes);
    }

    #[Test]
    public function custom_columns_exist_after_migration(): void
    {
        $this->assertTrue(
            \Schema::hasColumn('oauth_access_tokens', 'last_used_at'),
            'last_used_at column should exist on oauth_access_tokens'
        );
        $this->assertTrue(
            \Schema::hasColumn('oauth_access_tokens', 'last_used_ip'),
            'last_used_ip column should exist on oauth_access_tokens'
        );
    }

    #[Test]
    public function different_users_tokens_are_isolated(): void
    {
        $emailA = 'pat-iso-a-' . uniqid() . '@example.com';
        $emailB = 'pat-iso-b-' . uniqid() . '@example.com';

        $userA = $this->createUser($emailA);
        $userB = $this->createUser($emailB);

        $tokenA = $userA->createToken('A Token')->accessToken;
        $tokenB = $userB->createToken('B Token')->accessToken;

        // Token A authenticates as User A
        $r1 = $this->withToken($tokenA)->getJson('/api/user');
        $r1->assertOk();
        $r1->assertJsonFragment(['email' => $emailA]);

        // Reset application state so the second token is evaluated fresh
        $this->refreshApplication();

        // Token B authenticates as User B
        $r2 = $this->withToken($tokenB)->getJson('/api/user');
        $r2->assertOk();
        $r2->assertJsonFragment(['email' => $emailB]);
    }

    #[Test]
    public function token_has_correct_expiration(): void
    {
        $user = $this->createUser('pat-expire-' . uniqid() . '@example.com');

        $result = $user->createToken('Expiry Token');

        $this->assertNotNull($result->token->expires_at, 'Token should have an expiration date');
    }
}