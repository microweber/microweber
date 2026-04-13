<?php

declare(strict_types=1);

namespace Modules\Ai\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Ai\Http\Requests\McpRequest;
use Modules\Ai\Services\Mcp\McpRequestContext;
use Modules\Ai\Services\McpServer;

class McpController extends Controller
{
    public function __construct(
        private readonly McpServer $mcpServer
    ) {
    }

    public function handle(McpRequest $request): JsonResponse
    {
        /** @var McpRequestContext|null $context */
        $context = $request->attributes->get(McpRequestContext::class);
        $response = $this->mcpServer->handle($request->validated(), $context);

        $statusCode = isset($response['error']) && ($response['error']['code'] ?? 0) === -32000
            ? 503
            : 200;

        return response()->json($response, $statusCode);
    }
}
