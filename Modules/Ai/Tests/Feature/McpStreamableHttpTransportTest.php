<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Streamable HTTP transport conformance (MCP 2025-03-26 / 2025-06-18).
 *
 * The JSON-RPC core (initialize / tools/list / tools/call) was already
 * spec-pinned by {@see McpControllerTest} and {@see McpSpecComplianceTest}.
 * What those don't cover is the *transport envelope* that strict remote
 * clients — OpenCode, mcp-remote, Open WebUI, the official MCP SDK
 * `StreamableHTTPClientTransport` — rely on to mount the server:
 *
 *   1. The server mints an `Mcp-Session-Id` on `initialize` so the client
 *      has a session handle to reuse.
 *   2. A client-supplied `Mcp-Session-Id` is echoed back unchanged (the
 *      server is stateless and never 404s an unknown session).
 *   3. `GET /api/mcp` — the optional server→client SSE channel — returns a
 *      clean `405 Method Not Allowed` with an `Allow` header, because this
 *      tools-only server emits no server-initiated messages. (Before this
 *      transport work GET fell through to Microweber's page router and
 *      returned an unparseable HTML 403.)
 *   4. `DELETE /api/mcp` — session termination — returns `204`, not an
 *      error, so clients that tear down cleanly don't log a failure.
 *   5. `Mcp-Session-Id` is in the CORS `exposed_headers` allow-list so
 *      browser-based clients can actually read it off the response.
 *
 * A regression in any of these makes the server look "broken on connect"
 * to a conformant client even though every tool still works, so they are
 * pinned here as an explicit transport contract.
 */
class McpStreamableHttpTransportTest extends TestCase
{
    private ?GeneratedMcpClientToken $token = null;

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

        $client = $manager->createClient([
            'name' => 'Streamable HTTP Transport Test Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 0,
            'is_active' => true,
        ]);

        $this->token = $manager->issueToken($client, 'transport', ['mcp:access', 'mcp:admin']);
    }

    private function authHeader(): array
    {
        return ['Authorization' => 'Bearer ' . $this->token->plainTextToken];
    }

    #[Test]
    public function initialize_mints_a_non_empty_session_id(): void
    {
        $response = $this->withHeaders($this->authHeader())->postJson(route('api.ai.mcp'), [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => ['protocolVersion' => '2025-06-18'],
        ]);

        $response->assertOk();

        $sessionId = $response->headers->get('Mcp-Session-Id');
        $this->assertNotEmpty(
            $sessionId,
            'The server must mint an Mcp-Session-Id on initialize so Streamable-HTTP '
            . 'clients have a session handle to reuse for the rest of the connection.'
        );
    }

    #[Test]
    public function a_client_supplied_session_id_is_echoed_back_unchanged(): void
    {
        $response = $this->withHeaders($this->authHeader() + ['Mcp-Session-Id' => 'client-chosen-session-abc'])
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 2,
                'method' => 'ping',
            ]);

        $response->assertOk();
        $response->assertHeader('Mcp-Session-Id', 'client-chosen-session-abc');
    }

    #[Test]
    public function a_non_initialize_request_without_a_supplied_id_does_not_invent_one(): void
    {
        // The id is only minted on initialize; a bare ping with no header
        // must not grow a session id (the server is stateless and does not
        // track sessions it never handed out).
        $response = $this->withHeaders($this->authHeader())->postJson(route('api.ai.mcp'), [
            'jsonrpc' => '2.0',
            'id' => 3,
            'method' => 'ping',
        ]);

        $response->assertOk();
        $this->assertNull(
            $response->headers->get('Mcp-Session-Id'),
            'A non-initialize request that supplies no Mcp-Session-Id must not receive a freshly minted one.'
        );
    }

    #[Test]
    public function get_on_the_mcp_endpoint_returns_a_clean_405_with_an_allow_header(): void
    {
        $response = $this->withHeaders($this->authHeader())
            ->get(route('api.ai.mcp.stream'), ['Accept' => 'text/event-stream']);

        $response->assertStatus(405);
        $this->assertStringContainsString(
            'POST',
            (string) $response->headers->get('Allow'),
            'GET on the MCP endpoint must advertise POST in the Allow header so clients know where to send JSON-RPC.'
        );
    }

    #[Test]
    public function delete_terminates_the_session_with_a_204(): void
    {
        $response = $this->withHeaders($this->authHeader() + ['Mcp-Session-Id' => 'teardown-session-xyz'])
            ->delete(route('api.ai.mcp.terminate'));

        $response->assertNoContent();
        $response->assertHeader('Mcp-Session-Id', 'teardown-session-xyz');
    }

    #[Test]
    public function cors_exposes_the_session_id_header_to_browser_clients(): void
    {
        $exposed = (array) config('cors.exposed_headers', []);

        $this->assertContains(
            'Mcp-Session-Id',
            $exposed,
            'Mcp-Session-Id must be in the CORS exposed_headers allow-list so browser-based '
            . 'MCP clients can read the session handle off the response.'
        );
    }
}
