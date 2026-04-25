<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;

/**
 * Health check for the local MCP HTTP endpoint. Issues an ephemeral
 * client + token, runs the canonical handshake (`initialize` → `ping`
 * → `tools/list`), reports every step's verdict, and revokes the
 * ephemeral token on the way out so no audit residue is left behind.
 *
 * Use cases:
 *   - Smoke test after a deploy / configuration change.
 *   - Verify an `AI_MCP_ENABLED=true` flip actually took effect.
 *   - Debug a broken auth chain by running the same tokens-issued
 *     pipeline an MCP client would, but locally and verbosely.
 */
class McpHealthCommand extends Command
{
    protected $signature = 'ai:mcp:health
        {--base-url= : Base URL the MCP endpoint is served from (default: app.url)}';

    protected $description = 'Probe the local MCP HTTP endpoint with a freshly-issued ephemeral token and report verdict.';

    private const EPHEMERAL_CLIENT_NAME = 'MCP Health Probe (ephemeral)';

    public function handle(McpClientTokenManager $tokenManager): int
    {
        if (! (bool) config('modules.ai.mcp.enabled', false)) {
            $this->error(
                'MCP is disabled: set AI_ENABLED=true and AI_MCP_ENABLED=true in '
                . '.env, then re-run this command. The endpoint will return 503 '
                . 'until the flag is on.'
            );
            return self::FAILURE;
        }

        $baseUrl = (string) ($this->option('base-url') ?: config('app.url'));
        if ($baseUrl === '') {
            $this->error('No base URL configured. Pass --base-url or set APP_URL in .env.');
            return self::FAILURE;
        }
        $endpoint = (string) config('modules.ai.mcp.endpoint', '/api/mcp');
        $url = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $this->info('Probing ' . $url);

        $generated = $this->issueEphemeralToken($tokenManager);
        $token = $generated->plainTextToken;

        try {
            $verdicts = [];
            $verdicts[] = $this->probe('initialize', $url, $token, [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'initialize',
                'params' => [
                    'protocolVersion' => (string) config('modules.ai.mcp.protocol_version'),
                    'clientInfo' => ['name' => 'mcp-health', 'version' => '1.0.0'],
                ],
            ]);

            $verdicts[] = $this->probe('ping', $url, $token, [
                'jsonrpc' => '2.0',
                'id' => 2,
                'method' => 'ping',
            ]);

            $verdicts[] = $this->probe('tools/list', $url, $token, [
                'jsonrpc' => '2.0',
                'id' => 3,
                'method' => 'tools/list',
            ]);

            $allOk = ! in_array(false, $verdicts, true);
            $this->newLine();
            if ($allOk) {
                $this->info('✔ MCP health: PASS');
                return self::SUCCESS;
            }

            $this->error('✘ MCP health: FAIL — see verdicts above.');
            return self::FAILURE;
        } finally {
            $tokenManager->revokeToken($generated->token, null, 'mcp-health probe finished');
        }
    }

    private function issueEphemeralToken(McpClientTokenManager $tokenManager): GeneratedMcpClientToken
    {
        $client = $tokenManager->createClient([
            'name' => self::EPHEMERAL_CLIENT_NAME . ' ' . CarbonImmutable::now()->toIso8601String(),
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            // Health probe needs full visibility but won't run any
            // write tools — the catalog is read-only today anyway.
            'allowed_tools' => null,
            'allowed_modules' => null,
            'rate_limit_per_minute' => 600,
            'is_active' => true,
        ]);

        return $tokenManager->issueToken(
            client: $client,
            name: 'health-probe',
            abilities: ['mcp:access', 'mcp:admin'],
            expiresAt: CarbonImmutable::now()->addMinutes(5),
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function probe(string $label, string $url, string $token, array $payload): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->timeout(10)->post($url, $payload);

        $status = $response->status();
        $body = $response->json();

        if ($status >= 200 && $status < 300 && is_array($body) && ! isset($body['error'])) {
            $this->line(sprintf('  [✔ %s] HTTP %d', $label, $status));
            return true;
        }

        $error = is_array($body) ? json_encode($body) : (string) $response->body();
        $this->line(sprintf('  [✘ %s] HTTP %d — %s', $label, $status, $error));

        return false;
    }
}
