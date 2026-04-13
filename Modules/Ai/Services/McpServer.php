<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

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
     * @return array<string, mixed>
     */
    public function handle(array $payload, ?McpRequestContext $context = null): array
    {
        if (! (bool) config('modules.ai.mcp.enabled', false)) {
            return $this->errorResponse($payload['id'] ?? null, -32000, 'MCP server is disabled.');
        }

        return match ($payload['method']) {
            'initialize' => $this->initializeResponse($payload['id'] ?? null),
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

        try {
            $rawResult = $this->mcpToolCatalog->callTool($toolName, $arguments);
            $isError = str_contains($rawResult, 'alert-danger');

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
            return $this->errorResponse($id, -32603, $exception->getMessage());
        }
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
