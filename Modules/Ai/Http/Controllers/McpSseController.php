<?php

declare(strict_types=1);

namespace Modules\Ai\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Ai\Services\Mcp\McpRequestContext;
use Modules\Ai\Services\Mcp\McpSseSessionStore;
use Modules\Ai\Services\McpServer;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Legacy MCP HTTP+SSE transport (protocol 2024-11-05).
 *
 * Two endpoints bridged by {@see McpSseSessionStore}:
 *
 *   GET  /api/mcp/sse       — open an `text/event-stream`. The first frame
 *                             is an `endpoint` event telling the client the
 *                             URL to POST messages to (carrying a freshly
 *                             minted session id). The stream then relays any
 *                             JSON-RPC responses enqueued by POSTs on that
 *                             session until a bounded deadline, after which
 *                             it closes and the client reconnects.
 *   POST /api/mcp/messages  — the client's JSON-RPC requests. Each is
 *                             dispatched through the same {@see McpServer}
 *                             as the Streamable-HTTP transport; the response
 *                             is pushed onto the session queue (delivered
 *                             over the open stream) and the POST itself
 *                             returns 202 Accepted.
 *
 * The modern Streamable-HTTP transport (POST /api/mcp) remains the default
 * and is unaffected; this is an opt-out compatibility shim for clients that
 * only speak the older two-endpoint transport. Both endpoints reuse the
 * `mcp.client` middleware for bearer-token auth and scope enforcement.
 */
class McpSseController extends Controller
{
    public function __construct(
        private readonly McpServer $mcpServer,
        private readonly McpSseSessionStore $sessions,
    ) {
    }

    public function sse(Request $request): BaseResponse
    {
        if (! $this->transportEnabled()) {
            return $this->disabledResponse();
        }

        $sessionId = $this->sessions->open();
        $endpoint = $this->messagesEndpoint($sessionId);

        $maxSeconds = max(0, (int) config('modules.ai.mcp.sse.max_seconds', 25));
        $pollMs = max(50, (int) config('modules.ai.mcp.sse.poll_ms', 250));
        $keepaliveSeconds = max(1, (int) config('modules.ai.mcp.sse.keepalive_seconds', 15));

        $response = new StreamedResponse(function () use ($sessionId, $endpoint, $maxSeconds, $pollMs, $keepaliveSeconds): void {
            // First frame: tell the client where to POST its requests.
            $this->emit($this->sessions->frame('endpoint', $endpoint));

            $deadline = microtime(true) + $maxSeconds;
            $lastKeepalive = microtime(true);

            do {
                foreach ($this->sessions->drain($sessionId) as $message) {
                    $this->emit($this->sessions->frame(
                        'message',
                        (string) json_encode($message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    ));
                }

                if (connection_aborted() || microtime(true) >= $deadline) {
                    break;
                }

                if (microtime(true) - $lastKeepalive >= $keepaliveSeconds) {
                    // A comment frame keeps proxies from dropping an idle stream.
                    $this->emit(": keepalive\n\n");
                    $lastKeepalive = microtime(true);
                }

                usleep($pollMs * 1000);
            } while (true);

            $this->sessions->close($sessionId);
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache, no-transform');
        $response->headers->set('Connection', 'keep-alive');
        // Disable nginx/FastCGI response buffering so frames flush immediately.
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }

    public function messages(Request $request): BaseResponse
    {
        if (! $this->transportEnabled()) {
            return $this->disabledResponse();
        }

        $sessionId = (string) $request->query('sessionId', '');
        if ($sessionId === '' || ! $this->sessions->exists($sessionId)) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Unknown or expired MCP SSE session. Open GET ' . url('api/mcp/sse') . ' first.',
            ], 404);
        }

        /** @var McpRequestContext|null $context */
        $context = $request->attributes->get(McpRequestContext::class);
        $payload = $request->json()->all();

        if (is_array($payload) && array_is_list($payload)) {
            // JSON-RPC batch: enqueue each non-notification response.
            foreach ($payload as $entry) {
                if (is_array($entry)) {
                    $this->dispatchToQueue($sessionId, $entry, $context);
                }
            }
        } elseif (is_array($payload) && $payload !== []) {
            $this->dispatchToQueue($sessionId, $payload, $context);
        }

        // The JSON-RPC response (if any) is delivered over the SSE stream,
        // so the POST itself just acknowledges receipt.
        return response()->noContent(202);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function dispatchToQueue(string $sessionId, array $payload, ?McpRequestContext $context): void
    {
        $response = $this->mcpServer->handle($payload, $context);

        // Notifications return null — nothing to deliver over the stream.
        if ($response !== null) {
            $this->sessions->enqueue($sessionId, $response);
        }
    }

    private function emit(string $chunk): void
    {
        echo $chunk;

        if (function_exists('ob_get_level') && ob_get_level() > 0) {
            @ob_flush();
        }

        @flush();
    }

    private function messagesEndpoint(string $sessionId): string
    {
        // Built from the named route so it follows app.url (repointed
        // per-site by the multisite package) — the client POSTs here.
        return route('api.ai.mcp.messages', ['sessionId' => $sessionId]);
    }

    private function transportEnabled(): bool
    {
        return (bool) config('modules.ai.mcp.enabled', false)
            && (bool) config('modules.ai.mcp.sse.enabled', true);
    }

    private function disabledResponse(): BaseResponse
    {
        return response()->json([
            'error' => 'Not Found',
            'message' => 'The MCP HTTP+SSE transport is disabled on this install. '
                . 'Use the Streamable-HTTP transport: POST ' . url('api/mcp') . '.',
        ], 404);
    }
}
