<?php

declare(strict_types=1);

namespace Modules\Ai\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Illuminate\Routing\Controller;
use Modules\Ai\Services\Mcp\McpRequestContext;
use Modules\Ai\Services\McpServer;

class McpController extends Controller
{
    public function __construct(
        private readonly McpServer $mcpServer
    ) {
    }

    public function handle(Request $request): BaseResponse
    {
        /** @var McpRequestContext|null $context */
        $context = $request->attributes->get(McpRequestContext::class);

        $payload = $request->json()->all();

        // Streamable HTTP (MCP 2025-03-26 / 2025-06-18) session handle.
        // Resolve it once up-front so every return path below carries the
        // `Mcp-Session-Id` header back to the client.
        $sessionId = $this->resolveSessionId($request, $payload);

        // JSON-RPC 2.0 §6 — batch request support. A request whose body
        // is an array of envelopes is dispatched as a batch; the
        // response is an array of the corresponding response envelopes
        // (or HTTP 204 if every entry was a notification).
        if (is_array($payload) && array_is_list($payload)) {
            return $this->withSession($this->handleBatch($payload, $context), $sessionId);
        }

        if (! is_array($payload)) {
            // Empty or non-array body — JSON-RPC 2.0 §4.2 invalid request.
            return $this->withSession(response()->json(
                $this->invalidRequestEnvelope(null, 'Request body must be a JSON object or array.'),
                400
            ), $sessionId);
        }

        $invalidReason = $this->validateEnvelopeShape($payload);
        if ($invalidReason !== null) {
            return $this->withSession(response()->json(
                $this->invalidRequestEnvelope($payload['id'] ?? null, $invalidReason),
                400
            ), $sessionId);
        }

        $response = $this->mcpServer->handle($payload, $context);

        // Notification — server MUST NOT respond (JSON-RPC 2.0 §4.1).
        if ($response === null) {
            return $this->withSession(response()->noContent(), $sessionId);
        }

        return $this->withSession(
            response()->json($response, $this->statusCodeFor($response)),
            $sessionId
        );
    }

    /**
     * Streamable HTTP: the optional server→client SSE channel, opened by
     * the client with `GET /api/mcp`. This server is stateless and
     * tools-only (`capabilities.tools.listChanged = false`), so it has no
     * server-initiated messages to stream. Per the MCP spec a server that
     * does not offer a GET stream MUST return 405 — remote clients
     * (OpenCode, mcp-remote, the official SDK transports) react by simply
     * not opening the notification channel and keep working over POST.
     *
     * Returning a clean 405 here also replaces the HTML 403 the generic
     * Microweber page router would otherwise serve for GET /api/mcp, which
     * strict clients could not parse.
     */
    public function stream(Request $request): BaseResponse
    {
        $sessionId = $request->header('Mcp-Session-Id');

        return $this->withSession(
            response()->json([
                'error' => 'Method Not Allowed',
                'message' => 'This MCP server does not offer a server-initiated SSE stream. '
                    . 'Send JSON-RPC 2.0 requests with POST ' . $request->path() . '.',
            ], 405)->withHeaders(['Allow' => 'POST, DELETE']),
            is_string($sessionId) ? $sessionId : null
        );
    }

    /**
     * Streamable HTTP session termination (`DELETE /api/mcp`). The server
     * keeps no per-session state — the `Mcp-Session-Id` is a stateless echo
     * handle — so there is nothing to tear down. Acknowledge with 204 and
     * echo back the id the client asked us to forget, which satisfies
     * spec-strict clients that expect a non-error response to DELETE.
     */
    public function terminate(Request $request): BaseResponse
    {
        $sessionId = $request->header('Mcp-Session-Id');

        return $this->withSession(
            response()->noContent(),
            is_string($sessionId) ? $sessionId : null
        );
    }

    /**
     * @param array<int, mixed> $payloads
     */
    private function handleBatch(array $payloads, ?McpRequestContext $context): BaseResponse
    {
        if ($payloads === []) {
            return response()->json(
                $this->invalidRequestEnvelope(null, 'Batch request must contain at least one envelope.'),
                400
            );
        }

        $responses = [];
        foreach ($payloads as $entry) {
            if (! is_array($entry)) {
                $responses[] = $this->invalidRequestEnvelope(null, 'Each batch entry must be a JSON object.');
                continue;
            }

            $invalidReason = $this->validateEnvelopeShape($entry);
            if ($invalidReason !== null) {
                $responses[] = $this->invalidRequestEnvelope($entry['id'] ?? null, $invalidReason);
                continue;
            }

            $entryResponse = $this->mcpServer->handle($entry, $context);
            if ($entryResponse !== null) {
                $responses[] = $entryResponse;
            }
        }

        // Per spec: if every entry was a notification (no responses
        // accumulated) the server MUST return an empty body.
        if ($responses === []) {
            return response()->noContent();
        }

        return response()->json($responses, 200);
    }

    /**
     * Resolve the Streamable-HTTP session id for a POST request.
     *
     * If the client supplied an `Mcp-Session-Id` we echo it back
     * unchanged. Otherwise, on `initialize` only, we mint a fresh
     * stateless uuid so strict Streamable-HTTP clients get a session
     * handle to reuse for the rest of the connection. We never *require*
     * the id on later requests (the server stays stateless and never
     * 404s an unknown session), so clients that ignore the header — and
     * the stdio transport, which has no headers at all — keep working
     * unchanged. Mirrors the proven approach in the SyntheticMem MCP
     * server.
     *
     * @param mixed $payload  Decoded JSON body (object, batch list, or null).
     */
    private function resolveSessionId(Request $request, mixed $payload): ?string
    {
        $supplied = $request->header('Mcp-Session-Id');
        if (is_string($supplied) && $supplied !== '') {
            return $supplied;
        }

        $isInitialize = is_array($payload)
            && ! array_is_list($payload)
            && ($payload['method'] ?? null) === 'initialize';

        return $isInitialize ? (string) Str::uuid() : null;
    }

    /**
     * Stamp the Streamable-HTTP session id onto a response when we have
     * one. No-op when $sessionId is null so the JSON transport stays
     * byte-for-byte unchanged for clients that never send the header.
     */
    private function withSession(BaseResponse $response, ?string $sessionId): BaseResponse
    {
        if ($sessionId !== null && $sessionId !== '') {
            $response->headers->set('Mcp-Session-Id', $sessionId);
        }

        return $response;
    }

    /**
     * @param array<string, mixed> $response
     */
    private function statusCodeFor(array $response): int
    {
        $errorCode = data_get($response, 'error.code');

        return match (true) {
            $errorCode === -32000 => 503, // server disabled — non-2xx so callers retry
            default => 200,
        };
    }

    /**
     * Reject envelopes that violate JSON-RPC 2.0 §4 before they reach
     * the server's match() — gives clients a proper -32600 envelope
     * instead of Laravel's default 422 redirect.
     *
     * @param array<string, mixed> $payload
     */
    private function validateEnvelopeShape(array $payload): ?string
    {
        $jsonrpc = $payload['jsonrpc'] ?? null;
        if ($jsonrpc !== '2.0') {
            return 'Field `jsonrpc` must be the string "2.0".';
        }

        $method = $payload['method'] ?? null;
        if (! is_string($method) || $method === '') {
            return 'Field `method` is required and must be a non-empty string.';
        }

        if (isset($payload['params']) && ! is_array($payload['params'])) {
            return 'Field `params`, if present, must be an object or array.';
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function invalidRequestEnvelope(mixed $id, string $message): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'error' => [
                'code' => -32600,
                'message' => $message,
            ],
        ];
    }
}
