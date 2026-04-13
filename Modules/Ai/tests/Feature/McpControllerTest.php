<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Providers\AiServiceProvider;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use Modules\Ai\Services\Secrets\PassCommandRunner;
use Modules\Ai\Services\Secrets\PassSecretStore;
use Modules\Content\Models\Content;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
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
            'allowed_tools' => ['content.lookup', 'order.lookup'],
            'allowed_modules' => ['content', 'order'],
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
            ]);

        $tools = collect($response->json('result.tools'));

        $this->assertSame([
            'content.lookup',
            'content.get',
            'product.lookup',
            'order.lookup',
            'settings.read',
        ], $tools->pluck('name')->all());
        $this->assertSame('object', data_get($tools->firstWhere('name', 'content.lookup'), 'inputSchema.type'));
        $this->assertTrue((bool) data_get($tools->firstWhere('name', 'settings.read'), 'annotations.readOnlyHint'));
    }

    #[Test]
    public function tools_list_only_returns_tools_allowed_for_the_client(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 91,
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame(
            ['content.lookup', 'order.lookup'],
            collect($response->json('result.tools'))->pluck('name')->all()
        );
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
            'modules.ai.mcp.auth.admin_only_tools' => ['order.lookup'],
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->missingAdminScopeToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 12,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'order.lookup',
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

    #[Test]
    public function content_lookup_returns_plain_text_results_for_matching_content(): void
    {
        Content::factory()->create([
            'title' => 'MCP Knowledge Base',
            'description' => 'Reference page for MCP integrations.',
            'content_type' => 'page',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 14,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'content.lookup',
                    'arguments' => [
                        'search_term' => 'MCP Knowledge',
                        'content_type' => 'page',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $this->assertStringContainsString('MCP Knowledge Base', data_get($response->json(), 'result.content.0.text', ''));
    }

    #[Test]
    public function product_lookup_returns_plain_text_results_for_matching_products(): void
    {
        Product::factory()->create([
            'title' => 'MCP Commerce Product',
            'description' => 'A product exposed through the MCP catalog.',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 15,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'product.lookup',
                    'arguments' => [
                        'search_term' => 'Commerce Product',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $this->assertStringContainsString('MCP Commerce Product', data_get($response->json(), 'result.content.0.text', ''));
    }

    #[Test]
    public function order_lookup_returns_plain_text_results_for_matching_orders(): void
    {
        Order::factory()->create([
            'order_reference_id' => 'MCP-ORDER-1001',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'order_status' => 'processing',
            'amount' => 99.95,
            'currency' => 'USD',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 16,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'order.lookup',
                    'arguments' => [
                        'search_term' => 'MCP-ORDER-1001',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('MCP-ORDER-1001', $text);
        $this->assertStringContainsString('Ada Lovelace', $text);
    }

    #[Test]
    public function settings_read_returns_plain_text_for_non_sensitive_options(): void
    {
        save_option([
            'option_key' => 'site_name',
            'option_value' => 'Microweber MCP Demo',
            'option_group' => 'website',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 17,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'settings.read',
                    'arguments' => [
                        'option_group' => 'website',
                        'option_key' => 'site_name',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('site_name', $text);
        $this->assertStringContainsString('Microweber MCP Demo', $text);
    }

    #[Test]
    public function settings_read_marks_sensitive_options_as_tool_errors(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 18,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'settings.read',
                    'arguments' => [
                        'option_group' => 'ai',
                        'option_key' => 'openai_api_key',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', true);

        $this->assertStringContainsString('sensitive', data_get($response->json(), 'result.content.0.text', ''));
    }

    #[Test]
    public function rollout_path_supports_pass_backed_ai_config_and_a_first_mcp_session(): void
    {
        config([
            'modules.ai.secret_store.driver' => 'pass',
            'modules.ai.secret_store.pass.enabled' => true,
            'modules.ai.secret_store.pass.path_prefix' => 'microweber',
            'modules.ai.secret_store.pass.environment' => 'testing',
        ]);

        save_option([
            'option_key' => 'openai_api_key',
            'option_value' => 'pass://microweber/testing/ai/openai',
            'option_group' => 'ai',
        ]);

        $runner = $this->createMock(PassCommandRunner::class);
        $runner->expects($this->once())
            ->method('run')
            ->with(['show', 'microweber/testing/ai/openai'], null)
            ->willReturn('stored-openai-key');

        $this->app->instance(PassCommandRunner::class, $runner);
        $this->app->singleton(PassSecretStore::class, fn ($app) => new PassSecretStore($app->make(PassCommandRunner::class)));

        $provider = new AiServiceProvider($this->app);
        $provider->setAiConfig();

        $this->assertSame('stored-openai-key', config('modules.ai.drivers.openai.api_key'));
        $this->assertSame('pass://microweber/testing/ai/openai', get_option('openai_api_key', 'ai'));

        Content::factory()->create([
            'title' => 'Rollout MCP Page',
            'description' => 'Rollout validation content for MCP.',
            'content_type' => 'page',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        $headers = ['Authorization' => 'Bearer ' . $this->limitedToolToken->plainTextToken];

        $this->withHeaders($headers)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'rollout-init',
                'method' => 'initialize',
                'params' => [
                    'clientInfo' => [
                        'name' => 'rollout-check',
                        'version' => '1.0.0',
                    ],
                ],
            ])
            ->assertOk()
            ->assertJsonPath('result.serverInfo.name', 'Microweber AI MCP');

        $toolsListResponse = $this->withHeaders($headers)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'rollout-tools',
                'method' => 'tools/list',
            ]);

        $toolsListResponse->assertOk();
        $this->assertSame(
            ['content.lookup', 'order.lookup'],
            collect($toolsListResponse->json('result.tools'))->pluck('name')->all()
        );
        $this->assertSame(
            ['search_term'],
            data_get($toolsListResponse->json(), 'result.tools.0.inputSchema.required')
        );

        $toolCallResponse = $this->withHeaders($headers)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'rollout-call',
                'method' => 'tools/call',
                'params' => [
                    'name' => 'content.lookup',
                    'arguments' => [
                        'search_term' => 'Rollout MCP',
                        'content_type' => 'page',
                    ],
                ],
            ]);

        $toolCallResponse->assertOk()
            ->assertJsonPath('result.isError', false);

        $this->assertStringContainsString(
            'Rollout MCP Page',
            data_get($toolCallResponse->json(), 'result.content.0.text', '')
        );
        $this->assertSame(
            3,
            DB::table('mcp_client_token_events')
                ->where('mcp_client_id', $this->limitedToolClient->id)
                ->where('mcp_client_token_id', $this->limitedToolToken->token->id)
                ->where('action', 'token.used')
                ->count()
        );
    }
}
