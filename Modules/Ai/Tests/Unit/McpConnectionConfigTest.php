<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit;

use Modules\Ai\Services\Mcp\McpConnectionConfig;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * {@see McpConnectionConfig} is the single source of the agent-connection
 * snippets shared by the `ai:mcp:connect` command and the Filament
 * "Connect" action. These pin the contract both consumers rely on: the
 * endpoint follows app.url (per-site under multisite), the token is
 * embedded verbatim (or a safe placeholder), and each client gets a
 * well-formed block.
 */
class McpConnectionConfigTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'app.url' => 'https://cms.example',
            'modules.ai.mcp.endpoint' => '/api/mcp',
            'modules.ai.mcp.protocol_version' => '2025-03-26',
        ]);
    }

    private function config(): McpConnectionConfig
    {
        return app(McpConnectionConfig::class);
    }

    #[Test]
    public function the_endpoint_url_is_built_from_app_url_and_the_endpoint_path(): void
    {
        $this->assertSame('https://cms.example/api/mcp', $this->config()->endpointUrl());
    }

    #[Test]
    public function the_endpoint_url_honours_an_explicit_base_url(): void
    {
        // Multisite / a reverse proxy can serve the endpoint under a host
        // other than app.url; the caller may pin it.
        $this->assertSame('https://tenant.example/api/mcp', $this->config()->endpointUrl('https://tenant.example/'));
    }

    #[Test]
    public function blocks_default_to_the_token_placeholder_without_mutating_anything(): void
    {
        $blocks = $this->config()->blocks('microweber');

        $bodies = implode("\n", array_column($blocks, 'body'));
        $this->assertStringContainsString(McpConnectionConfig::TOKEN_PLACEHOLDER, $bodies);
        $this->assertStringNotContainsString('Bearer mcp_', $bodies);
    }

    #[Test]
    public function blocks_embed_a_supplied_token_in_every_transport(): void
    {
        $blocks = $this->config()->blocks('microweber', 'mcp_9|secrettoken');
        $byKey = array_column($blocks, 'body', 'key');

        // OpenCode remote carries it as a Bearer header; stdio carries it as
        // a --token CLI arg; the mcp-remote bridge carries the Bearer header.
        $this->assertStringContainsString('Bearer mcp_9|secrettoken', $byKey['opencode-remote']);
        $this->assertStringContainsString('--token=mcp_9|secrettoken', $byKey['opencode-local']);
        $this->assertStringContainsString('--token=mcp_9|secrettoken', $byKey['stdio']);
        $this->assertStringContainsString('Bearer mcp_9|secrettoken', $byKey['mcp-remote']);
        // The legacy HTTP+SSE block carries it as a Bearer header too.
        $this->assertStringContainsString('Bearer mcp_9|secrettoken', $byKey['sse']);
    }

    #[Test]
    public function the_sse_endpoint_url_appends_sse_to_the_resolved_endpoint(): void
    {
        $this->assertSame('https://cms.example/api/mcp/sse', $this->config()->sseEndpointUrl());
        // Follows an explicit base URL (per-site under multisite) just like
        // the Streamable-HTTP endpoint.
        $this->assertSame('https://tenant.example/api/mcp/sse', $this->config()->sseEndpointUrl('https://tenant.example/'));
    }

    #[Test]
    public function the_sse_block_offers_the_legacy_transport_targeting_the_sse_endpoint(): void
    {
        $byKey = array_column($this->config()->blocks('my-agent'), 'body', 'key');

        $this->assertArrayHasKey('sse', $byKey, 'Operators must be able to choose the legacy HTTP+SSE transport.');

        $decoded = json_decode($byKey['sse'], true);
        $this->assertSame('sse', $decoded['mcpServers']['my-agent']['type']);
        $this->assertSame('https://cms.example/api/mcp/sse', $decoded['mcpServers']['my-agent']['url']);
    }

    #[Test]
    public function the_opencode_remote_block_targets_the_resolved_endpoint(): void
    {
        $byKey = array_column($this->config()->blocks('my-agent'), 'body', 'key');

        $decoded = json_decode($byKey['opencode-remote'], true);
        $this->assertSame('remote', $decoded['mcp']['my-agent']['type']);
        $this->assertSame('https://cms.example/api/mcp', $decoded['mcp']['my-agent']['url']);
    }

    #[Test]
    public function every_block_declares_a_title_language_and_body(): void
    {
        foreach ($this->config()->blocks('microweber') as $block) {
            $this->assertArrayHasKey('title', $block);
            $this->assertArrayHasKey('language', $block);
            $this->assertNotSame('', $block['body']);
        }
    }

    #[Test]
    public function an_empty_server_name_falls_back_to_microweber(): void
    {
        $byKey = array_column($this->config()->blocks(''), 'body', 'key');
        $decoded = json_decode($byKey['opencode-remote'], true);

        $this->assertArrayHasKey('microweber', $decoded['mcp']);
    }

    #[Test]
    public function the_curl_handshake_uses_the_negotiated_protocol_version(): void
    {
        $byKey = array_column($this->config()->blocks('microweber'), 'body', 'key');

        $this->assertStringContainsString('"protocolVersion":"2025-03-26"', $byKey['curl']);
        $this->assertStringContainsString('https://cms.example/api/mcp', $byKey['curl']);
    }
}
