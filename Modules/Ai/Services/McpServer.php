<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Ai\Services\Mcp\McpRequestContext;
use Modules\Ai\Services\Mcp\McpToolCatalog;

class McpServer
{
    public function __construct(
        private readonly McpToolCatalog $mcpToolCatalog,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     * @param McpRequestContext|null $context
     * @return array<string, mixed>|null  null when the payload is a
     *   JSON-RPC notification (method starting with `notifications/`,
     *   or any request without an `id`) — the controller MUST NOT emit
     *   a response body in that case (per JSON-RPC 2.0 §4.1).
     */
    public function handle(array $payload, ?McpRequestContext $context = null): ?array
    {
        if (! (bool) config('modules.ai.mcp.enabled', false)) {
            // Disabled-server short-circuit. Notifications still get
            // null-returned so the controller never replies, otherwise
            // a notification-shaped probe could detect server state.
            if ($this->isNotification($payload)) {
                return null;
            }

            return $this->errorResponse($payload['id'] ?? null, -32000, 'MCP server is disabled.');
        }

        $method = (string) ($payload['method'] ?? '');

        if ($this->isNotification($payload)) {
            // MCP / JSON-RPC 2.0: notifications carry no `id` and the
            // server MUST NOT respond. We accept every notification
            // (notifications/initialized, notifications/cancelled, etc.)
            // and silently drop them — the controller turns the null
            // return into HTTP 204.
            return null;
        }

        return match ($method) {
            'initialize' => $this->initializeResponse($payload['id'] ?? null, $payload),
            'ping' => $this->pingResponse($payload['id'] ?? null),
            'tools/list' => $this->toolsListResponse($payload['id'] ?? null, $context),
            'tools/call' => $this->toolsCallResponse(
                $payload['id'] ?? null,
                (string) data_get($payload, 'params.name'),
                (array) data_get($payload, 'params.arguments', []),
                $context,
            ),
            default => $this->errorResponse($payload['id'] ?? null, -32601, 'Method not found.'),
        };
    }

    /**
     * A JSON-RPC payload is a notification when its method starts with
     * `notifications/` (MCP convention, e.g. `notifications/initialized`)
     * OR when the `id` field is entirely absent (JSON-RPC 2.0 §4.1).
     * `id: null` is a valid request id for compatibility with older
     * clients, so the absence check uses array_key_exists, not isset.
     */
    private function isNotification(array $payload): bool
    {
        $method = (string) ($payload['method'] ?? '');

        if (str_starts_with($method, 'notifications/')) {
            return true;
        }

        return ! array_key_exists('id', $payload);
    }

    /**
     * @param mixed $id
     * @param array<string, mixed> $payload  Full request payload — used to
     *   read the client-advertised `params.protocolVersion` so the
     *   negotiated version can be echoed back per the MCP spec.
     * @return array<string, mixed>
     */
    private function initializeResponse(mixed $id, array $payload = []): array
    {
        $clientVersion = data_get($payload, 'params.protocolVersion');
        $serverVersion = (string) config('modules.ai.mcp.protocol_version');
        $supported = (array) config('modules.ai.mcp.supported_protocol_versions', [$serverVersion]);

        // Per MCP spec: if the client-advertised version is one we
        // support, echo it back (the client will use that version
        // for the rest of the session). Otherwise fall back to the
        // server's preferred version — clients are required to handle
        // a mismatch and decide whether to continue.
        $negotiated = (is_string($clientVersion) && $clientVersion !== '' && in_array($clientVersion, $supported, true))
            ? $clientVersion
            : $serverVersion;

        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => [
                'protocolVersion' => $negotiated,
                'serverInfo' => [
                    'name' => config('modules.ai.mcp.server_name'),
                    'version' => config('modules.ai.mcp.server_version'),
                ],
                'capabilities' => [
                    'tools' => [
                        'listChanged' => false,
                    ],
                ],
                'transport' => config('modules.ai.mcp.transport'),
            ],
        ];
    }

    /**
     * MCP / JSON-RPC `ping` — utility liveness probe. Spec mandates an
     * empty `result` object so clients can distinguish liveness from
     * actual data. See https://spec.modelcontextprotocol.io/ utility
     * methods.
     *
     * @param mixed $id
     * @return array<string, mixed>
     */
    private function pingResponse(mixed $id): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => (object) [],
        ];
    }

    /**
     * @param mixed $id
     * @param McpRequestContext|null $context
     * @return array<string, mixed>
     */
    private function toolsListResponse(mixed $id, ?McpRequestContext $context): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => [
                'tools' => $context ? $this->mcpToolCatalog->listTools($context) : [],
            ],
        ];
    }

    /**
     * @param mixed $id
     * @param array<string, mixed> $arguments
     * @return array<string, mixed>
     */
    private function toolsCallResponse(mixed $id, string $toolName, array $arguments, ?McpRequestContext $context): array
    {
        if (! $context) {
            return $this->errorResponse($id, -32603, 'MCP request context is missing.');
        }

        if (! $this->mcpToolCatalog->hasTool($toolName)) {
            return $this->errorResponse($id, -32602, 'Tool not found.');
        }

        $startedAt = microtime(true);

        try {
            $rawResult = $this->mcpToolCatalog->callTool($toolName, $arguments);
            $isError = $this->detectToolError($rawResult);
            $this->logToolCall($context, $toolName, $startedAt, $isError ? 'error' : 'ok');

            return [
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => [
                    'content' => [[
                        'type' => 'text',
                        'text' => $this->normalizeToolOutput($rawResult),
                    ]],
                    'isError' => $isError,
                ],
            ];
        } catch (\Throwable $exception) {
            $this->logToolCall($context, $toolName, $startedAt, 'exception', $exception);
            return $this->errorResponse($id, -32603, $exception->getMessage());
        }
    }

    /**
     * Emit one structured log line per tool call so operators have
     * a per-tool latency / success-rate signal without standing up
     * Telescope or OTel. Goes through the regular Laravel logger
     * channel `mcp` if configured; otherwise falls back to the
     * default channel.
     *
     * The log line includes (token_id, client_id, tool, duration_ms,
     * status). A regression in any tool's runtime — slowdown, new
     * failure mode — surfaces as a flat-line in the `mcp` channel
     * within minutes, no Filament admin visit required.
     */
    private function logToolCall(
        McpRequestContext $context,
        string $toolName,
        float $startedAt,
        string $status,
        ?\Throwable $exception = null,
    ): void {
        $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

        $channel = (string) config('modules.ai.mcp.log_channel', 'stack');
        $slowThresholdMs = (int) config('modules.ai.mcp.slow_tool_warn_ms', 0);

        $payload = [
            'tool' => $toolName,
            'duration_ms' => $durationMs,
            'status' => $status,
            'token_id' => $context->token->id,
            'client_id' => $context->client->id,
        ];

        if ($exception !== null) {
            $payload['exception'] = get_class($exception);
            $payload['error'] = $exception->getMessage();
        }

        try {
            $logger = \Illuminate\Support\Facades\Log::channel($channel);
            $logger->info('mcp.tool.call', $payload);

            if ($slowThresholdMs > 0 && $durationMs > $slowThresholdMs) {
                $logger->warning('mcp.tool.slow', $payload + [
                    'slow_threshold_ms' => $slowThresholdMs,
                ]);
            }
        } catch (\Throwable) {
            // Logger misconfigured (e.g. unknown channel) — never
            // let observability faults break the tool response.
        }
    }

    /**
     * Detect tool error output by looking for the explicit marker
     * BaseTool::handleError emits (an HTML comment that survives
     * normalization because normalizeToolOutput() strips it). Falls
     * back to the legacy `alert-danger` class scan only when the
     * marker is absent — for tools that bypass BaseTool::handleError
     * and assemble their own error markup. The legacy scan is also
     * tightened to require the literal `class="alert alert-danger"`
     * opening tag so it doesn't false-positive on body text that
     * happens to mention the class name.
     */
    private function detectToolError(string $rawResult): bool
    {
        if (str_contains($rawResult, BaseTool::ERROR_OUTPUT_MARKER)) {
            return true;
        }

        return str_contains($rawResult, 'class="alert alert-danger"')
            || str_contains($rawResult, "class='alert alert-danger'");
    }

    /**
     * @param mixed $id
     * @return array<string, mixed>
     */
    private function errorResponse(mixed $id, int $code, string $message): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];
    }

    private function normalizeToolOutput(string $output): string
    {
        $withLineBreaks = preg_replace('/<(br|\\/p|\\/div|\\/li|\\/tr|\\/h[1-6])[^>]*>/i', "\n", $output) ?? $output;
        $withTabs = preg_replace('/<\\/td>/i', "\t", $withLineBreaks) ?? $withLineBreaks;
        $plainText = html_entity_decode(strip_tags($withTabs), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plainText = preg_replace("/\n{3,}/", "\n\n", $plainText) ?? $plainText;
        $plainText = preg_replace("/[ \t]{2,}/", ' ', $plainText) ?? $plainText;

        return trim($plainText);
    }
}
