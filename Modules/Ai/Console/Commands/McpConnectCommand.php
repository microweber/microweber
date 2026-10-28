<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use Modules\Ai\Services\Mcp\McpConnectionConfig;

/**
 * Print ready-to-paste connection config for external MCP agents.
 *
 * The server already speaks MCP over HTTP (`POST /api/mcp`, Streamable
 * HTTP) and stdio (`ai:mcp:serve`). What operators then need is the
 * exact config blob each client wants, filled in with THIS install's
 * endpoint URL and a working bearer token. This command is that last
 * mile: it resolves (or creates) an MCP client, mints a token, and
 * emits copy-paste snippets for:
 *
 *   - OpenCode           (remote = Streamable HTTP, and local = stdio)
 *   - Claude Desktop / Cursor / Cline (stdio via `ai:mcp:serve`, and
 *     remote via the `mcp-remote` bridge)
 *   - legacy HTTP+SSE    (the two-endpoint transport for older clients)
 *   - raw curl           (the initialize handshake, for any other agent)
 *
 * The minted token is shown ONCE — it is hashed at rest and cannot be
 * recovered — so the output warns to store it securely. Pass
 * `--no-token` to emit the same configs with a `<YOUR_TOKEN>`
 * placeholder and mint nothing (safe to share / commit to docs).
 */
class McpConnectCommand extends Command
{
    protected $signature = 'ai:mcp:connect
        {--client= : Existing MCP client id or name to issue a token for. Omit to create a fresh client.}
        {--server-name=microweber : The server key used inside the generated client configs.}
        {--base-url= : Base URL the endpoint is served from (default: app.url).}
        {--scopes=mcp:access,mcp:admin : Comma-separated abilities for the issued token.}
        {--tools= : Comma-separated tool allow-list for a NEW client ("*"/omit = unrestricted).}
        {--modules= : Comma-separated module allow-list for a NEW client ("*"/omit = unrestricted).}
        {--ttl-days= : Token expiry in days (omit = config default; 0 = no expiry).}
        {--no-token : Do not mint a token; emit configs with a <YOUR_TOKEN> placeholder.}';

    protected $description = 'Print ready-to-paste MCP connection config (OpenCode, Claude Desktop, Cursor, curl) for this install.';

    public function handle(McpClientTokenManager $tokenManager): int
    {
        if (! (bool) config('modules.ai.mcp.enabled', false)) {
            $this->error(
                'MCP is disabled. Set AI_ENABLED=true and AI_MCP_ENABLED=true in .env, '
                . 'then re-run — external agents get 503 until the flag is on.'
            );
            return self::FAILURE;
        }

        $baseUrl = (string) ($this->option('base-url') ?: config('app.url'));
        if ($baseUrl === '') {
            $this->error('No base URL. Pass --base-url or set APP_URL in .env.');
            return self::FAILURE;
        }

        $endpoint = (string) config('modules.ai.mcp.endpoint', '/api/mcp');
        $httpUrl = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');
        $artisanPath = base_path('artisan');
        $serverName = (string) ($this->option('server-name') ?: 'microweber');

        $scopes = $this->csv((string) $this->option('scopes')) ?: ['mcp:access', 'mcp:admin'];

        // Resolve the token to embed in the snippets.
        $token = '<YOUR_TOKEN>';
        if (! $this->option('no-token')) {
            $client = $this->resolveClient($tokenManager, $scopes);
            if ($client === null) {
                return self::FAILURE;
            }

            $generated = $this->issueToken($tokenManager, $client, $scopes);
            $token = $generated->plainTextToken;
        }

        $this->renderOutput($serverName, $httpUrl, $artisanPath, $token, $baseUrl);

        return self::SUCCESS;
    }

    /**
     * @param list<string> $scopes
     */
    private function resolveClient(McpClientTokenManager $tokenManager, array $scopes): ?McpClient
    {
        $clientOption = (string) ($this->option('client') ?? '');

        if ($clientOption !== '') {
            $client = ctype_digit($clientOption)
                ? McpClient::find((int) $clientOption)
                : McpClient::where('name', $clientOption)->first();

            if ($client === null) {
                $this->error("No MCP client found for --client=\"{$clientOption}\" (by id or name).");
                return null;
            }

            if (! $client->isAvailable()) {
                $this->error("MCP client \"{$client->name}\" is disabled; enable it before issuing a token.");
                return null;
            }

            return $client;
        }

        // No --client: create a fresh one named after the server key.
        return $tokenManager->createClient([
            'name' => 'Agent connection (' . ((string) $this->option('server-name') ?: 'microweber') . ') '
                . CarbonImmutable::now()->toDateTimeString(),
            'allowed_scopes' => $scopes,
            'allowed_tools' => $this->allowList((string) $this->option('tools')),
            'allowed_modules' => $this->allowList((string) $this->option('modules')),
            'rate_limit_per_minute' => 120,
            'is_active' => true,
        ]);
    }

    /**
     * @param list<string> $scopes
     */
    private function issueToken(McpClientTokenManager $tokenManager, McpClient $client, array $scopes): GeneratedMcpClientToken
    {
        $ttlOption = $this->option('ttl-days');
        $expiresAt = null;
        if ($ttlOption !== null && $ttlOption !== '') {
            $days = (int) $ttlOption;
            $expiresAt = $days > 0 ? CarbonImmutable::now()->addDays($days) : null;
        }

        return $tokenManager->issueToken(
            client: $client,
            name: 'agent-connect',
            abilities: $scopes,
            expiresAt: $expiresAt,
        );
    }

    private function renderOutput(string $serverName, string $httpUrl, string $artisanPath, string $token, string $baseUrl): void
    {
        $config = app(McpConnectionConfig::class);

        $this->newLine();
        $this->info('MCP connection details for this install');
        $this->line('  Endpoint (HTTP / Streamable HTTP): ' . $httpUrl);
        $this->line('  Endpoint (legacy HTTP+SSE):        ' . $config->sseEndpointUrl($baseUrl));
        $this->line('  Transport over stdio:              php ' . $artisanPath . ' ai:mcp:serve');
        $this->line('  Bearer token:                      ' . $token);
        if ($token !== McpConnectionConfig::TOKEN_PLACEHOLDER) {
            $this->warn('  ⚠ This token is shown ONCE and cannot be recovered — store it securely. Revoke with ai:mcp:token:revoke.');
        }

        // The config blocks themselves come from the shared
        // McpConnectionConfig so the CLI and the Filament "Connection
        // setup" action can never print divergent snippets.
        foreach ($config->blocks($serverName, $token, $baseUrl) as $block) {
            $this->newLine();
            $this->info('── ' . $block['title'] . ' ──');
            if ($block['hint'] !== null) {
                $this->line($block['hint']);
            }
            $this->line($block['body']);
        }

        $this->newLine();
    }

    /**
     * Allow-list option → null (unrestricted) for "" / "*", else the
     * parsed list. Mirrors ai:mcp:client:create semantics.
     */
    private function allowList(string $raw): ?array
    {
        $raw = trim($raw);
        if ($raw === '' || $raw === '*') {
            return null;
        }

        return $this->csv($raw);
    }

    /**
     * @return list<string>
     */
    private function csv(string $raw): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $raw)), static fn ($v) => $v !== ''));
    }
}
