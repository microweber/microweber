<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\User\Models\User;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class McpClientTokenManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('mcp_clients') || ! Schema::hasTable('mcp_client_tokens') || ! Schema::hasTable('mcp_client_token_events')) {
            DB::table('migrations')
                ->where('migration', '2026_04_13_184400_create_mcp_client_tables')
                ->delete();

            Artisan::call('migrate', [
                '--path' => base_path('Modules/Ai/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        DB::table('mcp_client_token_events')->delete();
        DB::table('mcp_client_tokens')->delete();
        DB::table('mcp_clients')->delete();

        config([
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
        ]);
    }

    #[Test]
    public function it_creates_clients_with_scopes_tools_modules_and_audit_metadata(): void
    {
        $actor = User::factory()->create([
            'email' => 'mcp-client-creator-' . uniqid() . '@example.com',
        ]);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $client = $manager->createClient([
            'name' => 'Catalog Reader',
            'description' => 'Read-only catalog automation client',
            'allowed_scopes' => ['mcp:access', 'content:read'],
            'allowed_tools' => ['content.lookup', 'product.lookup'],
            'allowed_modules' => ['content', 'product'],
            'rate_limit_per_minute' => 90,
            'is_active' => true,
        ], $actor);

        $this->assertNotEmpty($client->uuid);
        $this->assertSame('catalog-reader', $client->slug);
        $this->assertTrue($client->allowsScope('content:read'));
        $this->assertTrue($client->allowsTool('product.lookup'));
        $this->assertTrue($client->allowsModule('content'));
        $this->assertFalse($client->allowsTool('order.lookup'));

        $this->assertDatabaseHas('mcp_clients', [
            'id' => $client->id,
            'slug' => 'catalog-reader',
            'rate_limit_per_minute' => 90,
            'created_by_user_id' => $actor->id,
        ]);

        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $client->id,
            'action' => 'client.created',
            'actor_user_id' => $actor->id,
        ]);
    }

    #[Test]
    public function it_issues_plain_text_tokens_once_and_stores_only_a_hash(): void
    {
        $actor = User::factory()->create([
            'email' => 'mcp-token-issuer-' . uniqid() . '@example.com',
        ]);
        $client = McpClient::factory()->create([
            'allowed_scopes' => ['mcp:access', 'tools:list'],
        ]);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $issuedToken = $manager->issueToken(
            client: $client,
            name: 'Primary Token',
            actor: $actor,
        );

        $this->assertInstanceOf(GeneratedMcpClientToken::class, $issuedToken);
        $this->assertStringStartsWith('mcp_' . $issuedToken->token->id . '|', $issuedToken->plainTextToken);
        $this->assertNotSame($issuedToken->plainTextToken, $issuedToken->token->token_hash);
        $this->assertTrue(Hash::check(explode('|', $issuedToken->plainTextToken, 2)[1], $issuedToken->token->token_hash));
        $this->assertSame(['mcp:access', 'tools:list'], $issuedToken->token->abilities);

        $this->assertDatabaseHas('mcp_client_tokens', [
            'id' => $issuedToken->token->id,
            'mcp_client_id' => $client->id,
            'name' => 'Primary Token',
            'created_by_user_id' => $actor->id,
        ]);
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $client->id,
            'mcp_client_token_id' => $issuedToken->token->id,
            'action' => 'token.issued',
            'actor_user_id' => $actor->id,
        ]);
    }

    #[Test]
    public function it_can_find_rotate_revoke_and_record_usage_for_tokens(): void
    {
        $actor = User::factory()->create([
            'email' => 'mcp-rotator-' . uniqid() . '@example.com',
        ]);
        $client = McpClient::factory()->create([
            'allowed_scopes' => ['mcp:access'],
            'allowed_tools' => ['content.lookup'],
        ]);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $issuedToken = $manager->issueToken($client, 'Original Token', ['mcp:access'], null, $actor);
        $foundToken = $manager->findToken($issuedToken->plainTextToken);

        $this->assertInstanceOf(McpClientToken::class, $foundToken);
        $this->assertSame($issuedToken->token->id, $foundToken->id);
        $this->assertTrue($foundToken->allowsScope('mcp:access'));

        $manager->recordUsage($foundToken, '127.0.0.1', 'PHPUnit');

        $foundToken->refresh();
        $client->refresh();

        $this->assertNotNull($foundToken->last_used_at);
        $this->assertSame('127.0.0.1', $foundToken->last_used_ip);
        $this->assertSame('PHPUnit', $foundToken->last_used_user_agent);
        $this->assertNotNull($client->last_used_at);

        $rotatedToken = $manager->rotateToken($foundToken, 'Rotated Token', ['mcp:access'], null, $actor);

        $foundToken->refresh();

        $this->assertNotNull($foundToken->revoked_at);
        $this->assertSame('Rotated', $foundToken->revocation_reason);
        $this->assertSame($actor->id, $foundToken->revoked_by_user_id);
        $this->assertSame($foundToken->id, $rotatedToken->token->rotated_from_token_id);
        $this->assertStringStartsWith('mcp_' . $rotatedToken->token->id . '|', $rotatedToken->plainTextToken);
        $this->assertNotNull($manager->findToken($rotatedToken->plainTextToken));

        $manager->revokeToken($rotatedToken->token, $actor, 'Manual revoke');
        $rotatedToken->token->refresh();

        $this->assertNotNull($rotatedToken->token->revoked_at);
        $this->assertSame('Manual revoke', $rotatedToken->token->revocation_reason);
        $this->assertCount(5, DB::table('mcp_client_token_events')->where('mcp_client_id', $client->id)->get());

        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $client->id,
            'mcp_client_token_id' => $foundToken->id,
            'action' => 'token.used',
        ]);
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $client->id,
            'mcp_client_token_id' => $foundToken->id,
            'action' => 'token.revoked',
        ]);
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $client->id,
            'mcp_client_token_id' => $rotatedToken->token->id,
            'action' => 'token.rotated',
        ]);
    }
}
