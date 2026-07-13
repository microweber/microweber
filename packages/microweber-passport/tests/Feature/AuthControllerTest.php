<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

final class AuthControllerTest extends \MicroweberPackages\Passport\Tests\TestCase
{
    protected function createTestUser(string $email = 'passport-test@example.com', string $password = 'secret123'): object
    {
        $userModel = config('auth.providers.users.model');
        $existing = $userModel::where('email', $email)->first();
        if ($existing) {
            return $existing;
        }

        return $userModel::create([
            'name' => 'Passport Test User',
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    #[Test]
    public function login_returns_token_for_valid_credentials(): void
    {
        $this->createTestUser();

        $response = $this->postJson('/api/passport/login', [
            'email' => 'passport-test@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token', 'token_type', 'user']);
        $this->assertSame('Bearer', $response->json('token_type'));
        $this->assertNotEmpty($response->json('token'));
    }

    #[Test]
    public function login_rejects_invalid_credentials(): void
    {
        $this->createTestUser();

        $response = $this->postJson('/api/passport/login', [
            'email' => 'passport-test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $response->assertJsonFragment(['message' => 'Invalid credentials']);
    }

    #[Test]
    public function login_requires_email_or_username(): void
    {
        $response = $this->postJson('/api/passport/login', [
            'password' => 'secret123',
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function login_accepts_username_field(): void
    {
        $this->createTestUser();

        $response = $this->postJson('/api/passport/login', [
            'username' => 'passport-test@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['token', 'token_type']);
    }

    #[Test]
    public function token_authenticates_user_on_api_route(): void
    {
        $this->createTestUser();

        $loginResponse = $this->postJson('/api/passport/login', [
            'email' => 'passport-test@example.com',
            'password' => 'secret123',
        ]);

        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token, 'Should receive a token');

        $response = $this->withToken($token)
            ->getJson('/api/user');

        $response->assertOk();
        $response->assertJsonFragment(['email' => 'passport-test@example.com']);
    }

    #[Test]
    public function unauthenticated_request_is_rejected(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    #[Test]
    public function logout_revokes_token(): void
    {
        $user = $this->createTestUser('passport-logout-' . uniqid() . '@example.com');

        $loginResponse = $this->postJson('/api/passport/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $token = $loginResponse->json('token');

        // Logout
        $logoutResponse = $this->withToken($token)
            ->postJson('/api/passport/logout');

        $logoutResponse->assertOk();
        $logoutResponse->assertJsonFragment(['message' => 'Logged out']);

        // Verify that at least one token owned by this user is revoked
        $revokedCount = \DB::table('oauth_access_tokens')
            ->where('user_id', $user->id)
            ->where('revoked', true)
            ->count();

        $this->assertGreaterThan(0, $revokedCount, 'At least one token should be revoked after logout');
    }
}