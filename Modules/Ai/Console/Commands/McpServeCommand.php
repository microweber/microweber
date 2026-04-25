<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Illuminate\Console\Command;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use Modules\Ai\Services\Mcp\McpRequestContext;
use Modules\Ai\Services\McpServer;

/**
 * stdio transport for the MCP server. Reads JSON-RPC envelopes
 * (one per line) from STDIN, dispatches each through the same
 * `McpServer::handle()` pipeline the HTTP controller uses, and
 * writes the response envelope (one per line) to STDOUT.
 *
 * Use case: Claude Desktop / Cursor / Cline launch MCP servers
 * directly via a stdio command in their config; this lets
 * Microweber act as a stdio MCP server without requiring those
 * clients to support HTTP transport. Configure them with:
 *
 *   {
 *     "mcpServers": {
 *       "microweber": {
 *         "command": "php",
 *         "args": [
 *           "/path/to/microweber/artisan",
 *           "ai:mcp:serve",
 *           "--token=mcp_NN|secret..."
 *         ]
 *       }
 *     }
 *   }
 *
 * Authentication: the bearer token is supplied via --token (or the
 * MW_MCP_TOKEN env var). The stdio process runs under the operator's
 * shell so there's no HTTP layer for the AuthenticateMcpClient
 * middleware — the command resolves the token through the same
 * `McpClientTokenManager::findToken` helper the middleware uses.
 *
 * Notifications (`notifications/*`, requests without `id`) are
 * accepted silently; no STDOUT line is emitted, matching the HTTP
 * 204 behaviour.
 *
 * Caveats:
 *   * One request per line — no JSON-RPC batch support over stdio
 *     yet (clients that send batches will see a per-batch envelope
 *     wrapped to one line).
 *   * The command runs until STDIN EOF. A misconfigured client
 *     that opens but doesn't close the pipe will hold the process
 *     alive indefinitely; rely on the parent (Claude Desktop /
 *     Cursor) to manage the lifecycle.
 */
class McpServeCommand extends Command
{
    protected $signature = 'ai:mcp:serve
        {--token= : MCP bearer token. Falls back to the MW_MCP_TOKEN env var.}';

    protected $description = 'Run the MCP server over stdio so it can be launched as a subprocess by Claude Desktop / Cursor / etc.';

    public function handle(McpClientTokenManager $tokenManager, McpServer $server): int
    {
        if (! (bool) config('modules.ai.mcp.enabled', false)) {
            fwrite(STDERR, "MCP is disabled. Set AI_ENABLED=true and AI_MCP_ENABLED=true in .env, then re-run.\n");
            return self::FAILURE;
        }

        $rawToken = (string) ($this->option('token') ?: getenv('MW_MCP_TOKEN'));
        if ($rawToken === '') {
            fwrite(STDERR, "ai:mcp:serve requires a token. Pass --token=mcp_NN|secret or set MW_MCP_TOKEN.\n");
            return self::FAILURE;
        }

        $token = $tokenManager->findToken($rawToken);
        if ($token === null) {
            fwrite(STDERR, "Token did not resolve. Check the value or rotate via ai:mcp:token:rotate.\n");
            return self::FAILURE;
        }

        $token->loadMissing('client');
        if (! $token->isActive() || ! $token->client->isAvailable()) {
            fwrite(STDERR, "Token is revoked / expired or its client is disabled.\n");
            return self::FAILURE;
        }

        // STDIN line loop. Each line is one JSON-RPC envelope.
        while (! feof(STDIN)) {
            $line = fgets(STDIN);
            if ($line === false) {
                break;
            }
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $payload = json_decode($line, true);
            if (! is_array($payload)) {
                $this->writeResponse([
                    'jsonrpc' => '2.0',
                    'id' => null,
                    'error' => [
                        'code' => -32700,
                        'message' => 'Parse error: input must be a JSON object envelope.',
                    ],
                ]);
                continue;
            }

            $context = new McpRequestContext(
                client: $token->client,
                token: $token,
                method: (string) ($payload['method'] ?? ''),
                toolName: (string) data_get($payload, 'params.name', ''),
                moduleName: $this->extractModule((string) data_get($payload, 'params.name', '')),
            );

            $response = $server->handle($payload, $context);

            // null = notification — no STDOUT output (matches HTTP 204).
            if ($response === null) {
                continue;
            }

            $this->writeResponse($response);
        }

        return self::SUCCESS;
    }

    /**
     * @param array<string, mixed> $envelope
     */
    private function writeResponse(array $envelope): void
    {
        $encoded = json_encode($envelope, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            // Should not happen with a well-formed McpServer
            // response — fall back to a minimal error so the
            // client doesn't hang waiting for output.
            $encoded = '{"jsonrpc":"2.0","id":null,"error":{"code":-32603,"message":"Server response could not be encoded as JSON."}}';
        }
        fwrite(STDOUT, $encoded . "\n");
    }

    private function extractModule(string $toolName): string
    {
        if ($toolName === '' || ! str_contains($toolName, '.')) {
            return '';
        }

        return explode('.', $toolName, 2)[0];
    }
}
