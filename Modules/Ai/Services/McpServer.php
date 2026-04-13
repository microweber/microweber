<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

class McpServer
{
    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function handle(array $payload): array
    {
        if (! (bool) config('modules.ai.mcp.enabled', false)) {
            return $this->errorResponse($payload['id'] ?? null, -32000, 'MCP server is disabled.');
        }

        return match ($payload['method']) {
            'initialize' => $this->initializeResponse($payload['id'] ?? null),
            'tools/list' => $this->toolsListResponse($payload['id'] ?? null),
            default => $this->errorResponse($payload['id'] ?? null, -32601, 'Method not found.'),
        };
    }

    /**
     * @param mixed $id
     * @return array<string, mixed>
     */
    private function initializeResponse(mixed $id): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => [
                'protocolVersion' => config('modules.ai.mcp.protocol_version'),
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
     * @param mixed $id
     * @return array<string, mixed>
     */
    private function toolsListResponse(mixed $id): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $id,
            'result' => [
                'tools' => [],
            ],
        ];
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
}
