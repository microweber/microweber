<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class McpControllerTest extends TestCase
{
    protected ?McpClient $fullAccessClient = null;
    protected ?McpClient $limitedToolClient = null;
    protected ?GeneratedMcpClientToken $fullAccessToken = null;
    protected ?GeneratedMcpClientToken $missingScopeToken = null;
    protected ?GeneratedMcpClientToken $missingAdminScopeToken = null;
    protected ?GeneratedMcpClientToken $limitedToolToken = null;
    protected ?GeneratedMcpClientToken $revokedToken = null;

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
        RateLimiter::clear('mcp-client-token:1');
        RateLimiter::clear('mcp-client-token:2');
        RateLimiter::clear('mcp-client-token:3');
        RateLimiter::clear('mcp-client-token:4');
        RateLimiter::clear('mcp-client-token:5');

        config([
            'modules.ai.enabled' => true,
            'modules.ai.mcp.enabled' => true,
            'modules.ai.mcp.server_name' => 'Microweber AI MCP',
            'modules.ai.mcp.server_version' => '0.1.0',
            'modules.ai.mcp.protocol_version' => '2025-03-26',
            'modules.ai.mcp.transport' => 'http-jsonrpc',
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
            'modules.ai.mcp.auth.required_abilities' => ['mcp:access'],
            'modules.ai.mcp.auth.admin_scope' => 'mcp:admin',
            'modules.ai.mcp.auth.admin_only_tools' => [],
            'modules.ai.mcp.auth.admin_only_modules' => [],
        ]);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $this->fullAccessClient = $manager->createClient([
            'name' => 'Full Access MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);

        $this->limitedToolClient = $manager->createClient([
            'name' => 'Limited Tool MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['content.lookup', 'orders.lookup'],
            'allowed_modules' => ['content', 'orders'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);

        $this->fullAccessToken = $manager->issueToken($this->fullAccessClient, 'full-access', ['mcp:access', 'mcp:admin']);
        $this->missingScopeToken = $manager->issueToken($this->fullAccessClient, 'missing-scope', ['content:read']);
        $this->missingAdminScopeToken = $manager->issueToken($this->limitedToolClient, 'missing-admin', ['mcp:access']);
        $this->limitedToolToken = $manager->issueToken($this->limitedToolClient, 'limited-tool', ['mcp:access', 'mcp:admin']);
        $this->revokedToken = $manager->issueToken($this->fullAccessClient, 'revoked', ['mcp:access', 'mcp:admin']);
        $manager->revokeToken($this->revokedToken->token, null, 'Revoked in test setup');
    }

    #[Test]
    public function client_token_can_initialize_the_mcp_endpoint_and_updates_usage_tracking(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'initialize',
                'params' => [
                    'clientInfo' => [
                        'name' => 'test-client',
                        'version' => '1.0.0',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJson([
                'jsonrpc' => '2.0',
                'id' => 1,
                'result' => [
                    'protocolVersion' => '2025-03-26',
                    'serverInfo' => [
                        'name' => 'Microweber AI MCP',
                        'version' => '0.1.0',
                    ],
                    'transport' => 'http-jsonrpc',
                ],
            ]);

        $this->fullAccessToken->token->refresh();
        $this->fullAccessClient->refresh();

        $this->assertNotNull($this->fullAccessToken->token->last_used_at);
        $this->assertNotNull($this->fullAccessClient->last_used_at);
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $this->fullAccessClient->id,
            'mcp_client_token_id' => $this->fullAccessToken->token->id,
            'action' => 'token.used',
        ]);
        $this->assertFalse((bool) data_get($response->json(), 'result.capabilities.tools.listChanged'));
    }

    #[Test]
    public function client_token_can_request_the_initial_tools_list(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'tools-1',
                'method' => 'tools/list',
            ]);

        $response->assertOk()
            ->assertJson([
                'jsonrpc' => '2.0',
                'id' => 'tools-1',
                'result' => [
                    'tools' => [],
                ],
            ]);
    }

    #[Test]
    public function allowed_tool_calls_still_return_json_rpc_method_not_found_until_tool_mapping_exists(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 99,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'content.lookup',
                ],
            ]);

        $response->assertOk()
            ->assertJson([
                'jsonrpc' => '2.0',
                'id' => 99,
                'error' => [
                    'code' => -32601,
                    'message' => 'Method not found.',
                ],
            ]);
    }

    #[Test]
    public function mcp_endpoint_requires_authentication(): void
    {
        $response = $this->postJson(route('api.ai.mcp'), [
            'jsonrpc' => '2.0',
            'id' => 7,
            'method' => 'initialize',
        ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function mcp_endpoint_rejects_invalid_bearer_tokens(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 8,
                'method' => 'initialize',
            ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function mcp_endpoint_rejects_tokens_without_the_required_scope(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->missingScopeToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 9,
                'method' => 'initialize',
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $this->fullAccessClient->id,
            'mcp_client_token_id' => $this->missingScopeToken->token->id,
            'action' => 'token.denied',
        ]);
    }

    #[Test]
    public function mcp_endpoint_rejects_revoked_tokens(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->revokedToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 10,
                'method' => 'initialize',
            ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function mcp_endpoint_rejects_tools_outside_the_clients_allowed_list(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 11,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'settings.read',
                ],
            ]);

        $response->assertForbidden();
    }

    #[Test]
    public function mcp_endpoint_enforces_admin_only_tool_scope_when_configured(): void
    {
        config([
            'modules.ai.mcp.auth.admin_only_tools' => ['orders.lookup'],
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->missingAdminScopeToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 12,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'orders.lookup',
                ],
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $this->limitedToolClient->id,
            'mcp_client_token_id' => $this->missingAdminScopeToken->token->id,
            'action' => 'token.denied',
        ]);
    }

    #[Test]
    public function mcp_endpoint_applies_per_client_rate_limits(): void
    {
        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $rateLimitedClient = $manager->createClient([
            'name' => 'Rate Limited Client',
            'allowed_scopes' => ['mcp:access'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 1,
            'is_active' => true,
        ]);
        $rateLimitedToken = $manager->issueToken($rateLimitedClient, 'rate-limited', ['mcp:access']);

        $headers = ['Authorization' => 'Bearer ' . $rateLimitedToken->plainTextToken];
        $payload = [
            'jsonrpc' => '2.0',
            'id' => 13,
            'method' => 'initialize',
        ];

        $this->withHeaders($headers)->postJson(route('api.ai.mcp'), $payload)->assertOk();
        $this->withHeaders($headers)->postJson(route('api.ai.mcp'), $payload)->assertStatus(429);

        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $rateLimitedClient->id,
            'mcp_client_token_id' => $rateLimitedToken->token->id,
            'action' => 'token.rate_limited',
        ]);
    }
}
