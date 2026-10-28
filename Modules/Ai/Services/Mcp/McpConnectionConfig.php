<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Mcp;

/**
 * Single source of truth for the "how to connect an external agent"
 * config blocks.
 *
 * The same OpenCode / Claude Desktop / Cursor / curl snippets are
 * surfaced in two places — the `ai:mcp:connect` console command and the
 * "Connection setup" action on {@see \Modules\Ai\Filament\Resources\McpClientResource}
 * — and they MUST stay identical, or an operator copying from the admin
 * UI gets a config the CLI docs say is wrong. Both consumers render from
 * {@see blocks()} so the shapes never drift.
 *
 * Nothing here mutates state or mints a token: callers pass the token
 * (or accept the {@see TOKEN_PLACEHOLDER} default) so the blocks can be
 * generated for display without touching the database.
 */
class McpConnectionConfig
{
    /** Shown in place of a real bearer when no token is embedded. */
    public const string TOKEN_PLACEHOLDER = '<YOUR_TOKEN>';

    /**
     * Resolve the public HTTP endpoint from a base URL (defaults to
     * `app.url`, which the multisite package repoints per-site) and the
     * configured endpoint path.
     */
    public function endpointUrl(?string $baseUrl = null): string
    {
        $baseUrl = $baseUrl !== null && $baseUrl !== '' ? $baseUrl : (string) config('app.url');
        $endpoint = (string) config('modules.ai.mcp.endpoint', '/api/mcp');

        return rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');
    }

    public function artisanPath(): string
    {
        return base_path('artisan');
    }

    /** Human-readable stdio invocation shown in the summary line. */
    public function stdioCommandDisplay(): string
    {
        return 'php ' . $this->artisanPath() . ' ai:mcp:serve';
    }

    /**
     * Rendered connection blocks for display. Each entry is
     * ['key', 'title', 'hint'|null, 'language', 'body'].
     *
     * @return list<array{key: string, title: string, hint: ?string, language: string, body: string}>
     */
    public function blocks(string $serverName, ?string $token = null, ?string $baseUrl = null): array
    {
        $token = $token !== null && $token !== '' ? $token : self::TOKEN_PLACEHOLDER;
        $serverName = $serverName !== '' ? $serverName : 'microweber';
        $url = $this->endpointUrl($baseUrl);
        $artisanPath = $this->artisanPath();
        $authHeader = 'Authorization: Bearer ' . $token;

        return [
            [
                'key' => 'opencode-remote',
                'title' => 'OpenCode — remote (Streamable HTTP)',
                'hint' => 'Add to opencode.json (or ~/.config/opencode/opencode.json):',
                'language' => 'json',
                'body' => $this->json($this->openCodeRemote($serverName, $url, $token)),
            ],
            [
                'key' => 'opencode-local',
                'title' => 'OpenCode — local (stdio)',
                'hint' => null,
                'language' => 'json',
                'body' => $this->json($this->openCodeLocal($serverName, $artisanPath, $token)),
            ],
            [
                'key' => 'stdio',
                'title' => 'Claude Desktop / Cursor / Cline — stdio',
                'hint' => "Add to the client's mcpServers config:",
                'language' => 'json',
                'body' => $this->json($this->stdioServers($serverName, $artisanPath, $token)),
            ],
            [
                'key' => 'mcp-remote',
                'title' => 'Claude Desktop / Cursor — remote via mcp-remote bridge',
                'hint' => 'For clients that only speak stdio, bridge to the HTTP endpoint:',
                'language' => 'json',
                'body' => $this->json($this->mcpRemoteServers($serverName, $url, $authHeader)),
            ],
            [
                'key' => 'curl',
                'title' => 'Any agent — raw curl handshake',
                'hint' => null,
                'language' => 'bash',
                'body' => $this->curlHandshake($url, $authHeader),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function openCodeRemote(string $serverName, string $url, string $token): array
    {
        return [
            '$schema' => 'https://opencode.ai/config.json',
            'mcp' => [
                $serverName => [
                    'type' => 'remote',
                    'url' => $url,
                    'enabled' => true,
                    'headers' => ['Authorization' => 'Bearer ' . $token],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function openCodeLocal(string $serverName, string $artisanPath, string $token): array
    {
        return [
            'mcp' => [
                $serverName => [
                    'type' => 'local',
                    'command' => ['php', $artisanPath, 'ai:mcp:serve', '--token=' . $token],
                    'enabled' => true,
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function stdioServers(string $serverName, string $artisanPath, string $token): array
    {
        return [
            'mcpServers' => [
                $serverName => [
                    'command' => 'php',
                    'args' => [$artisanPath, 'ai:mcp:serve', '--token=' . $token],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function mcpRemoteServers(string $serverName, string $url, string $authHeader): array
    {
        return [
            'mcpServers' => [
                $serverName => [
                    'command' => 'npx',
                    'args' => ['mcp-remote', $url, '--header', $authHeader],
                ],
            ],
        ];
    }

    public function curlHandshake(string $url, string $authHeader): string
    {
        return sprintf(
            "curl -sS %s \\\n  -H %s \\\n  -H 'Content-Type: application/json' \\\n  -H 'Accept: application/json, text/event-stream' \\\n  -d '%s'",
            escapeshellarg($url),
            escapeshellarg($authHeader),
            '{"jsonrpc":"2.0","id":1,"method":"initialize","params":{"protocolVersion":"'
                . (string) config('modules.ai.mcp.protocol_version') . '"}}'
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    public function json(array $data): string
    {
        return (string) json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
