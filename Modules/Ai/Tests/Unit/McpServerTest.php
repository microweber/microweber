<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\McpServer;
use Modules\Ai\Services\Mcp\McpRequestContext;
use Modules\Ai\Services\Mcp\McpToolCatalog;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Unit coverage for {@see McpServer::handle()} — the JSON-RPC dispatch
 * core of the MCP server.
 *
 * The rest of the suite exercises this class end-to-end through the HTTP
 * controller (McpControllerTest) with real client/token fixtures and the
 * full tool catalog. That proves the wiring but makes the dispatch rules
 * themselves — notification detection, protocol negotiation, method
 * routing, and the four shapes a `tools/call` result can take — expensive
 * to pin individually. These tests drive `handle()` directly against a
 * controlled {@see McpToolCatalog} double, so each branch is asserted in
 * isolation with no DB rows, no HTTP, and no real tools:
 *
 *   - disabled-server short-circuit (error for requests, silence for
 *     notifications so a probe can't detect server state);
 *   - notification detection (by `notifications/` prefix AND by an absent
 *     `id` key, while a present `id: null` is still a real request);
 *   - initialize protocol-version negotiation (echo a supported client
 *     version, else fall back to the server's preferred version);
 *   - ping → empty result object;
 *   - tools/list with and without a request context;
 *   - the tools/call result matrix: missing context, unknown tool,
 *     plain-text success, JSON pass-through, flagged tool error, and a
 *     thrown exception becoming an internal error.
 */
class McpServerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Deterministic server metadata so the negotiation / serverInfo
        // assertions don't depend on the ambient .env.
        config([
            'modules.ai.mcp.enabled' => true,
            'modules.ai.mcp.protocol_version' => '2025-03-26',
            'modules.ai.mcp.supported_protocol_versions' => ['2024-11-05', '2025-03-26', '2025-06-18'],
            'modules.ai.mcp.server_name' => 'Microweber AI MCP',
            'modules.ai.mcp.server_version' => '0.1.0',
            'modules.ai.mcp.transport' => 'http-jsonrpc',
            'modules.ai.mcp.log_channel' => 'null',
        ]);
    }

    /**
     * A controlled catalog double. `McpToolCatalog` has no constructor and
     * none of the three methods we need are final, so overriding them is
     * safe — this keeps the real tool classes and the container out of
     * the dispatch tests entirely.
     *
     * @param list<string>              $knownTools  names hasTool() returns true for
     * @param (\Closure(string, array): string)|null $onCall callTool() body
     * @param list<array<string,mixed>> $listResult  what listTools() returns
     */
    private function catalog(array $knownTools = [], ?\Closure $onCall = null, array $listResult = []): McpToolCatalog
    {
        return new class ($knownTools, $onCall, $listResult) extends McpToolCatalog {
            /**
             * @param list<string> $knownTools
             * @param list<array<string,mixed>> $listResult
             */
            public function __construct(
                private array $knownTools,
                private ?\Closure $onCall,
                private array $listResult,
            ) {
            }

            public function listTools(McpRequestContext $context): array
            {
                return $this->listResult;
            }

            public function hasTool(string $name): bool
            {
                return in_array($name, $this->knownTools, true);
            }

            public function callTool(string $name, array $arguments): string
            {
                if ($this->onCall === null) {
                    return '';
                }

                return ($this->onCall)($name, $arguments);
            }
        };
    }

    private function server(McpToolCatalog $catalog): McpServer
    {
        return new McpServer($catalog);
    }

    /**
     * A request context backed by unsaved models — `handle()` only reads
     * `token->id` / `client->id` for the structured log line, so no DB row
     * is needed.
     */
    private function context(): McpRequestContext
    {
        $client = new McpClient();
        $client->id = 7;

        $token = new McpClientToken();
        $token->id = 11;

        return new McpRequestContext(client: $client, token: $token);
    }

    // ---- disabled-server short-circuit -------------------------------

    #[Test]
    public function a_disabled_server_returns_a_jsonrpc_error_for_a_request(): void
    {
        config(['modules.ai.mcp.enabled' => false]);

        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'ping',
        ]);

        $this->assertIsArray($response);
        $this->assertSame(1, $response['id']);
        $this->assertSame(-32000, $response['error']['code']);
        $this->assertArrayNotHasKey('result', $response);
    }

    #[Test]
    public function a_disabled_server_stays_silent_for_a_notification(): void
    {
        // A notification-shaped probe must never draw a different response
        // when the server is off, or server state leaks.
        config(['modules.ai.mcp.enabled' => false]);

        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'method' => 'notifications/initialized',
        ]);

        $this->assertNull($response);
    }

    // ---- notification detection --------------------------------------

    #[Test]
    public function a_method_in_the_notifications_namespace_is_never_answered(): void
    {
        // Even with an `id` present, the `notifications/` prefix wins — the
        // server MUST NOT reply (JSON-RPC 2.0 §4.1 / MCP convention).
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => 99,
            'method' => 'notifications/cancelled',
        ]);

        $this->assertNull($response);
    }

    #[Test]
    public function a_request_missing_the_id_key_is_treated_as_a_notification(): void
    {
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'method' => 'ping',
        ]);

        $this->assertNull($response);
    }

    #[Test]
    public function a_request_with_an_explicit_null_id_is_still_answered(): void
    {
        // `id: null` is a valid request id (older clients) — detection uses
        // array_key_exists, not isset, so this must NOT be dropped.
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => null,
            'method' => 'ping',
        ]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertNull($response['id']);
        $this->assertArrayHasKey('result', $response);
    }

    // ---- initialize ---------------------------------------------------

    #[Test]
    public function initialize_echoes_a_supported_client_protocol_version(): void
    {
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => ['protocolVersion' => '2024-11-05'],
        ]);

        $this->assertSame('2024-11-05', $response['result']['protocolVersion']);
    }

    #[Test]
    public function initialize_falls_back_to_the_server_version_for_an_unsupported_client_version(): void
    {
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => ['protocolVersion' => '1999-01-01'],
        ]);

        $this->assertSame('2025-03-26', $response['result']['protocolVersion']);
    }

    #[Test]
    public function initialize_advertises_server_info_capabilities_and_transport(): void
    {
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'initialize',
            'params' => [],
        ]);

        $result = $response['result'];
        $this->assertSame('2025-03-26', $result['protocolVersion']);
        $this->assertSame('Microweber AI MCP', $result['serverInfo']['name']);
        $this->assertSame('0.1.0', $result['serverInfo']['version']);
        $this->assertFalse($result['capabilities']['tools']['listChanged']);
        $this->assertSame('http-jsonrpc', $result['transport']);
    }

    // ---- ping ---------------------------------------------------------

    #[Test]
    public function ping_returns_an_empty_result_object(): void
    {
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => 42,
            'method' => 'ping',
        ]);

        $this->assertSame(42, $response['id']);
        // MCP mandates an (empty) object so clients can tell liveness from
        // data — json_encode must render `{}`, not `[]`.
        $this->assertSame('{}', json_encode($response['result']));
    }

    // ---- tools/list ---------------------------------------------------

    #[Test]
    public function tools_list_without_a_context_returns_an_empty_tool_set(): void
    {
        $catalog = $this->catalog(listResult: [['name' => 'content.lookup']]);

        $response = $this->server($catalog)->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
        ], null);

        $this->assertSame([], $response['result']['tools']);
    }

    #[Test]
    public function tools_list_with_a_context_returns_the_catalog_tools(): void
    {
        $catalog = $this->catalog(listResult: [
            ['name' => 'content.lookup'],
            ['name' => 'product.search'],
        ]);

        $response = $this->server($catalog)->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/list',
        ], $this->context());

        $this->assertCount(2, $response['result']['tools']);
        $this->assertSame('content.lookup', $response['result']['tools'][0]['name']);
    }

    // ---- tools/call ---------------------------------------------------

    #[Test]
    public function tools_call_without_a_context_is_an_internal_error(): void
    {
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => ['name' => 'content.lookup', 'arguments' => []],
        ], null);

        $this->assertSame(-32603, $response['error']['code']);
    }

    #[Test]
    public function tools_call_for_an_unknown_tool_is_an_invalid_params_error(): void
    {
        $response = $this->server($this->catalog(knownTools: []))->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => ['name' => 'does.not.exist', 'arguments' => []],
        ], $this->context());

        $this->assertSame(-32602, $response['error']['code']);
        $this->assertSame('Tool not found.', $response['error']['message']);
    }

    #[Test]
    public function tools_call_normalizes_html_output_and_reports_no_error(): void
    {
        $catalog = $this->catalog(
            knownTools: ['content.lookup'],
            onCall: static fn () => '<p>Hello</p><br>World',
        );

        $response = $this->server($catalog)->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => ['name' => 'content.lookup', 'arguments' => []],
        ], $this->context());

        $content = $response['result']['content'][0];
        $this->assertSame('text', $content['type']);
        $this->assertStringContainsString('Hello', $content['text']);
        $this->assertStringContainsString('World', $content['text']);
        $this->assertStringNotContainsString('<p>', $content['text']);
        $this->assertFalse($response['result']['isError']);
    }

    #[Test]
    public function tools_call_passes_a_json_envelope_through_as_application_json(): void
    {
        $json = '{"ok":true,"items":[1,2,3]}';
        $catalog = $this->catalog(
            knownTools: ['order.search'],
            onCall: static fn () => "  {$json}  ",
        );

        $response = $this->server($catalog)->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => ['name' => 'order.search', 'arguments' => []],
        ], $this->context());

        $content = $response['result']['content'][0];
        $this->assertSame('application/json', $content['mimeType']);
        // Passed through verbatim (trimmed) — NOT run through the HTML strip
        // that would collapse the structure.
        $this->assertSame($json, $content['text']);
        $this->assertFalse($response['result']['isError']);
    }

    #[Test]
    public function tools_call_flags_error_output_without_disguising_it_as_json(): void
    {
        // Error marker wins even on a JSON-shaped body: isError must be true
        // and the JSON pass-through branch must be skipped.
        $catalog = $this->catalog(
            knownTools: ['content.lookup'],
            onCall: static fn () => BaseTool::ERROR_OUTPUT_MARKER . '{"message":"denied"}',
        );

        $response = $this->server($catalog)->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => ['name' => 'content.lookup', 'arguments' => []],
        ], $this->context());

        $this->assertTrue($response['result']['isError']);
        $this->assertArrayNotHasKey('mimeType', $response['result']['content'][0]);
    }

    #[Test]
    public function tools_call_turns_a_thrown_exception_into_an_internal_error(): void
    {
        $catalog = $this->catalog(
            knownTools: ['content.lookup'],
            onCall: static function (): string {
                throw new \RuntimeException('tool blew up');
            },
        );

        $response = $this->server($catalog)->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => ['name' => 'content.lookup', 'arguments' => []],
        ], $this->context());

        $this->assertSame(-32603, $response['error']['code']);
        $this->assertSame('tool blew up', $response['error']['message']);
    }

    // ---- unknown method ----------------------------------------------

    #[Test]
    public function an_unknown_method_is_a_method_not_found_error(): void
    {
        $response = $this->server($this->catalog())->handle([
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'resources/list',
        ]);

        $this->assertSame(-32601, $response['error']['code']);
        $this->assertSame('Method not found.', $response['error']['message']);
    }
}
