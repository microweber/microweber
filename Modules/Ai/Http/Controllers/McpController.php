<?php

declare(strict_types=1);

namespace Modules\Ai\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        // JSON-RPC 2.0 §6 — batch request support. A request whose body
        // is an array of envelopes is dispatched as a batch; the
        // response is an array of the corresponding response envelopes
        // (or HTTP 204 if every entry was a notification).
        if (is_array($payload) && array_is_list($payload)) {
            return $this->handleBatch($payload, $context);
        }

        if (! is_array($payload)) {
            // Empty or non-array body — JSON-RPC 2.0 §4.2 invalid request.
            return response()->json(
                $this->invalidRequestEnvelope(null, 'Request body must be a JSON object or array.'),
                400
            );
        }

        $invalidReason = $this->validateEnvelopeShape($payload);
        if ($invalidReason !== null) {
            return response()->json(
                $this->invalidRequestEnvelope($payload['id'] ?? null, $invalidReason),
                400
            );
        }

        $response = $this->mcpServer->handle($payload, $context);

        // Notification — server MUST NOT respond (JSON-RPC 2.0 §4.1).
        if ($response === null) {
            return response()->noContent();
        }

        return response()->json($response, $this->statusCodeFor($response));
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
