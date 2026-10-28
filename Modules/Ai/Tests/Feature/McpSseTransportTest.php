<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use Modules\Ai\Services\Mcp\McpSseSessionStore;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Legacy MCP HTTP+SSE transport (protocol 2024-11-05):
 *   GET  /api/mcp/sse       — opens the stream, first frame is the
 *                             `endpoint` event with the messages URL.
 *   POST /api/mcp/messages  — carries JSON-RPC; the response is enqueued
 *                             onto the session (delivered over the stream)
 *                             and the POST returns 202.
 *
 * Both endpoints reuse the `mcp.client` bearer auth. The stream is capped
 * at `max_seconds = 0` here so the response terminates after a single
 * drain pass (production streams stay open ~25s and recycle).
 */
class McpSseTransportTest extends TestCase
{
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
            'cache.default' => 'array',
            'modules.ai.enabled' => true,
            'modules.ai.mcp.enabled' => true,
            'modules.ai.mcp.endpoint' => '/api/mcp',
            'modules.ai.mcp.protocol_version' => '2025-03-26',
            'modules.ai.mcp.supported_protocol_versions' => ['2024-11-05', '2025-03-26', '2025-06-18'],
            'modules.ai.mcp.auth.required_abilities' => ['mcp:access'],
            'modules.ai.mcp.sse.enabled' => true,
            // Terminate the stream after one drain pass so tests don't hang.
            'modules.ai.mcp.sse.max_seconds' => 0,
            'modules.ai.mcp.sse.cache_store' => null,
        ]);

        $manager = app(McpClientTokenManager::class);
        $client = $manager->createClient([
            'name' => 'zz-sse-agent',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 120,
            'is_active' => true,
        ]);
        $this->token = $manager->issueToken(
            client: $client,
            name: 'sse',
            abilities: ['mcp:access', 'mcp:admin'],
        );
    }

    private function bearer(): string
    {
        return 'Bearer ' . $this->token->plainTextToken;
    }

    #[Test]
    public function the_sse_stream_requires_authentication(): void
    {
        $this->get('http://localhost/api/mcp/sse')->assertStatus(401);
    }

    #[Test]
    public function the_sse_stream_opens_and_announces_the_messages_endpoint(): void
    {
        $response = $this->withHeader('Authorization', $this->bearer())
            ->get('http://localhost/api/mcp/sse');

        $response->assertOk();
        $this->assertStringContainsString(
            'text/event-stream',
            (string) $response->baseResponse->headers->get('Content-Type'),
        );

        $body = $response->streamedContent();
        $this->assertStringContainsString('event: endpoint', $body);
        $this->assertStringContainsString('data: http://localhost/api/mcp/messages?sessionId=', $body);
    }

    #[Test]
    public function posting_messages_requires_authentication(): void
    {
        $this->postJson('http://localhost/api/mcp/messages?sessionId=whatever', [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'ping',
        ])->assertStatus(401);
    }

    #[Test]
    public function posting_to_an_unknown_session_is_not_found(): void
    {
        $response = $this->withHeader('Authorization', $this->bearer())
            ->postJson('http://localhost/api/mcp/messages?sessionId=does-not-exist', [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'ping',
            ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function posting_without_a_session_id_is_not_found(): void
    {
        $response = $this->withHeader('Authorization', $this->bearer())
            ->postJson('http://localhost/api/mcp/messages', [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'ping',
            ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function a_posted_request_is_accepted_and_its_response_is_queued_for_the_stream(): void
    {
        $sessions = app(McpSseSessionStore::class);
        $sessionId = $sessions->open();

        $response = $this->withHeader('Authorization', $this->bearer())
            ->postJson('http://localhost/api/mcp/messages?sessionId=' . $sessionId, [
                'jsonrpc' => '2.0',
                'id' => 7,
                'method' => 'initialize',
                'params' => ['protocolVersion' => '2024-11-05'],
            ]);

        // The POST only acknowledges — the JSON-RPC result travels over SSE.
        $response->assertStatus(202);

        $queued = $sessions->drain($sessionId);
        $this->assertCount(1, $queued);
        $this->assertSame(7, $queued[0]['id']);
        $this->assertSame('2024-11-05', $queued[0]['result']['protocolVersion']);
    }

    #[Test]
    public function a_posted_notification_is_accepted_and_queues_nothing(): void
    {
        $sessions = app(McpSseSessionStore::class);
        $sessionId = $sessions->open();

        $response = $this->withHeader('Authorization', $this->bearer())
            ->postJson('http://localhost/api/mcp/messages?sessionId=' . $sessionId, [
                'jsonrpc' => '2.0',
                'method' => 'notifications/initialized',
            ]);

        $response->assertStatus(202);
        $this->assertSame([], $sessions->drain($sessionId));
    }

    #[Test]
    public function the_transport_can_be_disabled(): void
    {
        config(['modules.ai.mcp.sse.enabled' => false]);

        $this->withHeader('Authorization', $this->bearer())
            ->get('http://localhost/api/mcp/sse')
            ->assertStatus(404);

        $sessions = app(McpSseSessionStore::class);
        $sessionId = $sessions->open();

        $this->withHeader('Authorization', $this->bearer())
            ->postJson('http://localhost/api/mcp/messages?sessionId=' . $sessionId, [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'ping',
            ])
            ->assertStatus(404);
    }
}
