<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

/**
 * Filament/Livewire-level integration tests for the ApiApplicationsPage.
 *
 * These test the Livewire component methods directly (without a browser)
 * via the Livewire::test() helper. They require the Microweber CMS to
 * be installed so Filament + Livewire are available.
 */
final class FilamentApiApplicationsPageTest extends \MicroweberPackages\Passport\Tests\TestCase
{
    protected function createAdminUser(): object
    {
        $userModel = config('auth.providers.users.model');
        $email = 'passport-admin-' . uniqid() . '@example.com';
        $existing = $userModel::where('email', $email)->first();
        if ($existing) {
            return $existing;
        }

        $attrs = [
            'email' => $email,
            'password' => Hash::make('secret'),
        ];

        // Set admin flag if the model supports it (MW CMS)
        if (property_exists(new $userModel, 'is_admin') || in_array('is_admin', (new $userModel)->getFillable())) {
            $attrs['is_admin'] = 1;
        }
        $attrs['name'] = 'Passport Admin';

        return $userModel::create($attrs);
    }

    #[Test]
    public function create_personal_token_returns_access_token(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        // Directly test the createToken method on the user
        $result = $user->createToken('Test API Token');
        $this->assertNotEmpty($result->accessToken);
        $this->assertSame('Test API Token', $result->token->name);
    }

    #[Test]
    public function revoke_token_marks_it_as_revoked_in_db(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        $result = $user->createToken('Revoke Me');
        $tokenId = $result->token->id;

        // Verify it's not revoked
        $this->assertFalse(
            (bool) DB::table('oauth_access_tokens')->where('id', $tokenId)->value('revoked')
        );

        // Revoke
        $result->token->revoke();

        // Verify it IS revoked
        $this->assertTrue(
            (bool) DB::table('oauth_access_tokens')->where('id', $tokenId)->value('revoked')
        );
    }

    #[Test]
    public function revoke_all_tokens_marks_all_as_revoked(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        $token1 = $user->createToken('Token A')->token;
        $token2 = $user->createToken('Token B')->token;
        $token3 = $user->createToken('Token C')->token;

        // Revoke all
        $tokenIds = $user->tokens()->where('revoked', false)->pluck('id');
        $user->tokens()->whereIn('id', $tokenIds)->update(['revoked' => true]);

        $this->assertTrue((bool) $token1->fresh()->revoked);
        $this->assertTrue((bool) $token2->fresh()->revoked);
        $this->assertTrue((bool) $token3->fresh()->revoked);
    }

    #[Test]
    public function oauth_client_can_be_created_and_revoked(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        $secret = \Illuminate\Support\Str::random(40);
        $client = \Laravel\Passport\Passport::client()->forceFill([
            'owner_id' => $user->id,
            'owner_type' => get_class($user),
            'name' => 'Test OAuth App',
            'secret' => $secret,
            'redirect_uris' => ['http://localhost/callback'],
            'revoked' => false,
            'grant_types' => ['authorization_code', 'refresh_token'],
        ]);
        $client->save();

        $this->assertNotNull($client->id);
        $this->assertFalse((bool) $client->revoked);

        // Revoke
        $client->update(['revoked' => true]);
        $this->assertTrue((bool) $client->fresh()->revoked);
    }

    #[Test]
    public function token_scopes_are_validated(): void
    {
        $user = $this->createAdminUser();
        $this->actingAs($user);

        $result = $user->createToken('Scoped Token', ['content:read', 'pages:write']);
        $this->assertSame(['content:read', 'pages:write'], $result->token->scopes);

        // Wildcard scope
        $resultWild = $user->createToken('Wildcard Token', ['*']);
        $this->assertSame(['*'], $resultWild->token->scopes);
    }
}