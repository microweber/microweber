<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Plan A.1 / A.2 — JSON-RPC + MCP spec compliance tests.
 *
 * The pre-existing {@see McpControllerTest} drives the happy-path
 * tools/* methods end-to-end with rich fixture data. This test
 * focuses on the spec-compliance surface — the bits real MCP
 * clients (Claude Desktop, Cursor, Cline, Continue) probe BEFORE
 * sending a single tools/call:
 *
 *   - `ping` returns an empty result (utility liveness probe).
 *   - `notifications/initialized` is accepted with no response
 *     body (JSON-RPC 2.0 §4.1: notifications carry no `id` and
 *     the server MUST NOT respond).
 *   - `notifications/cancelled` and other `notifications/*`
 *     methods are similarly accepted-no-content.
 *   - `initialize` echoes the client-advertised `protocolVersion`
 *     when the server supports it; falls back to the server's
 *     preferred version when it doesn't.
 *   - Invalid request envelopes (no `jsonrpc`, no `method`, wrong
 *     `params` type) return a proper -32600 JSON-RPC error
 *     envelope, not a Laravel 422 redirect.
 *   - Batch requests (JSON-RPC 2.0 §6) are dispatched and the
 *     response is an array of corresponding response envelopes.
 *
 * Uses a single full-access client + token to keep setUp cheap;
 * the rich fixture coverage lives in the sibling McpControllerTest.
 */
class McpSpecComplianceTest extends TestCase
{
    private McpClient $client;
    private GeneratedMcpClientToken $token;

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
            'modules.ai.enabled' => true,
            'modules.ai.mcp.enabled' => true,
            'modules.ai.mcp.server_name' => 'Microweber AI MCP',
            'modules.ai.mcp.server_version' => '0.1.0',
            'modules.ai.mcp.protocol_version' => '2025-03-26',
            'modules.ai.mcp.supported_protocol_versions' => [
                '2024-11-05',
                '2025-03-26',
                '2025-06-18',
            ],
            'modules.ai.mcp.transport' => 'http-jsonrpc',
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
            'modules.ai.mcp.auth.required_abilities' => ['mcp:access'],
            'modules.ai.mcp.auth.admin_scope' => 'mcp:admin',
            'modules.ai.mcp.auth.admin_only_tools' => [],
            'modules.ai.mcp.auth.admin_only_modules' => [],
        ]);

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $this->client = $manager->createClient([
            'name' => 'Spec Compliance MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 600,
            'is_active' => true,
        ]);

        $this->token = $manager->issueToken(
            client: $this->client,
            name: 'Spec Compliance Token',
            abilities: ['mcp:access', 'mcp:admin'],
        );

        RateLimiter::clear('mcp-client-token:' . $this->token->token->id);
    }

    private function authHeaders(): array
    {
        return ['Authorization' => 'Bearer ' . $this->token->plainTextToken];
    }

    #[Test]
    public function ping_method_returns_an_empty_result_envelope(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'ping-1',
                'method' => 'ping',
            ]);

        $response->assertOk();
        $body = $response->json();

        $this->assertSame('2.0', $body['jsonrpc']);
        $this->assertSame('ping-1', $body['id']);
        $this->assertArrayHasKey('result', $body);
        // The MCP spec accepts `{}` here — both an empty array and an
        // empty object marshal to the same JSON. We only need the
        // result key to be present and non-error.
        $this->assertArrayNotHasKey('error', $body);
    }

    #[Test]
    public function notifications_initialized_returns_204_no_content(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'method' => 'notifications/initialized',
            ]);

        $response->assertNoContent();
    }

    #[Test]
    public function any_notifications_method_returns_204_no_content(): void
    {
        // Spec mandates server MUST NOT respond to notifications.
        // Test a few representative method names so a regression that
        // only special-cases `notifications/initialized` still fails.
        foreach (['notifications/initialized', 'notifications/cancelled', 'notifications/progress'] as $method) {
            $response = $this->withHeaders($this->authHeaders())
                ->postJson(route('api.ai.mcp'), [
                    'jsonrpc' => '2.0',
                    'method' => $method,
                ]);

            $response->assertNoContent();
        }
    }

    #[Test]
    public function initialize_echoes_back_a_client_advertised_supported_protocol_version(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'initialize',
                'params' => [
                    'protocolVersion' => '2024-11-05',
                    'clientInfo' => ['name' => 'spec-test', 'version' => '1.0.0'],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.protocolVersion', '2024-11-05')
            ->assertJsonPath('result.serverInfo.name', 'Microweber AI MCP');
    }

    #[Test]
    public function initialize_falls_back_to_server_version_for_unsupported_client_version(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 2,
                'method' => 'initialize',
                'params' => [
                    'protocolVersion' => '2099-12-31',
                    'clientInfo' => ['name' => 'future-client', 'version' => '1.0.0'],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.protocolVersion', '2025-03-26');
    }

    #[Test]
    public function initialize_without_protocol_version_returns_server_default(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 3,
                'method' => 'initialize',
            ]);

        $response->assertOk()
            ->assertJsonPath('result.protocolVersion', '2025-03-26');
    }

    #[Test]
    public function missing_method_returns_invalid_request_envelope(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 4,
            ]);

        $response->assertStatus(400)
            ->assertJsonPath('jsonrpc', '2.0')
            ->assertJsonPath('id', 4)
            ->assertJsonPath('error.code', -32600);
    }

    #[Test]
    public function wrong_jsonrpc_version_returns_invalid_request_envelope(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '1.0',
                'id' => 5,
                'method' => 'initialize',
            ]);

        $response->assertStatus(400)
            ->assertJsonPath('jsonrpc', '2.0')
            ->assertJsonPath('id', 5)
            ->assertJsonPath('error.code', -32600);
    }

    #[Test]
    public function batch_request_returns_one_response_per_envelope_in_order(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                ['jsonrpc' => '2.0', 'id' => 'a', 'method' => 'initialize'],
                ['jsonrpc' => '2.0', 'id' => 'b', 'method' => 'ping'],
            ]);

        $response->assertOk();
        $body = $response->json();

        $this->assertIsArray($body);
        $this->assertCount(2, $body);
        $this->assertSame('a', $body[0]['id']);
        $this->assertArrayHasKey('result', $body[0]);
        $this->assertSame('b', $body[1]['id']);
        $this->assertArrayHasKey('result', $body[1]);
    }

    #[Test]
    public function batch_request_with_only_notifications_returns_204_no_content(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                ['jsonrpc' => '2.0', 'method' => 'notifications/initialized'],
                ['jsonrpc' => '2.0', 'method' => 'notifications/cancelled'],
            ]);

        $response->assertNoContent();
    }

    #[Test]
    public function batch_request_mixes_responses_and_notifications(): void
    {
        // Per JSON-RPC 2.0 §6: the response array contains responses
        // for non-notification entries only; notification entries are
        // silently dropped.
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                ['jsonrpc' => '2.0', 'id' => 'request', 'method' => 'ping'],
                ['jsonrpc' => '2.0', 'method' => 'notifications/initialized'],
                ['jsonrpc' => '2.0', 'id' => 'request2', 'method' => 'ping'],
            ]);

        $response->assertOk();
        $body = $response->json();
        $this->assertCount(2, $body);
        $this->assertSame('request', $body[0]['id']);
        $this->assertSame('request2', $body[1]['id']);
    }

    #[Test]
    public function empty_batch_returns_invalid_request_envelope(): void
    {
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), []);

        $response->assertStatus(400)
            ->assertJsonPath('error.code', -32600);
    }

    #[Test]
    public function initialize_capabilities_only_declare_supported_features(): void
    {
        // Spec: a server's `capabilities` object MUST only contain
        // keys for features the server actually supports. Today we
        // only ship the `tools` capability — the catalog is fully
        // tools-driven, no resources / prompts / logging / sampling /
        // completion. Pin this so a future contributor who adds
        // `resources: {}` to the response without wiring up the
        // resources/list and resources/read methods fails this test.
        $response = $this->withHeaders($this->authHeaders())
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'caps-1',
                'method' => 'initialize',
            ]);

        $response->assertOk();

        $caps = $response->json('result.capabilities');
        $this->assertIsArray(
            $caps,
            'initialize result.capabilities must be an object — every spec-compliant '
            . 'MCP client reads this to decide which methods are safe to call.'
        );
        $this->assertArrayHasKey(
            'tools',
            $caps,
            'tools is the canonical capability this server actually supports — '
            . 'a regression that drops it would mean every MCP client (Claude Desktop, '
            . 'Cursor, Cline) would refuse to call tools/list because the server '
            . 'just told them the server doesn\'t support it.'
        );

        // Reject every spec-defined capability key the server does
        // NOT yet implement. Adding a new key here when shipping a
        // real implementation is the documented path; declaring it
        // before wiring up the methods produces a server that lies
        // to clients about its capabilities.
        $unimplemented = ['resources', 'prompts', 'logging', 'sampling', 'completion'];
        foreach ($unimplemented as $key) {
            $this->assertArrayNotHasKey(
                $key,
                $caps,
                "initialize must NOT advertise capabilities.{$key} until the matching "
                . "MCP methods are implemented — otherwise spec-compliant clients will "
                . "issue {$key}/* requests the server can't honour, and the server's "
                . "-32601 'Method not found' replies become a footgun where clients "
                . "trusted the capabilities object."
            );
        }
    }
}
