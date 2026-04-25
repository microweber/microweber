<?php

declare(strict_types=1);

namespace Modules\Ai\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use Modules\Ai\Services\Mcp\McpRequestContext;

class AuthenticateMcpClient
{
    public function __construct(
        private readonly McpClientTokenManager $tokenManager,
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        $plainTextToken = $request->bearerToken();

        if (! $plainTextToken) {
            return $this->unauthorized($request, 'Missing MCP bearer token.');
        }

        $token = $this->tokenManager->findToken($plainTextToken);

        if (! $token) {
            return $this->unauthorized($request, 'Invalid MCP bearer token.');
        }

        $token->loadMissing('client');
        $client = $token->client;

        if (! $client || ! $client->isAvailable() || ! $token->isActive()) {
            $this->auditDenied($request, $token, 'token_unavailable');

            return $this->unauthorized($request, 'MCP token is revoked or inactive.');
        }

        $payload = $request->json()->all();
        $method = is_array($payload) ? data_get($payload, 'method') : null;
        $toolName = $this->extractToolName($method, $payload);
        $moduleName = $this->extractModuleName($toolName);

        foreach ((array) config('modules.ai.mcp.auth.required_abilities', ['mcp:access']) as $scope) {
            if (! $token->allowsScope($scope) || ! $client->allowsScope($scope)) {
                $this->auditDenied($request, $token, 'missing_scope', [
                    'required_scope' => $scope,
                    'method' => $method,
                ]);

                return $this->forbidden('MCP token does not have the required scope.');
            }
        }

        if ($toolName !== null && ! $client->allowsTool($toolName)) {
            $this->auditDenied($request, $token, 'tool_not_allowed', [
                'method' => $method,
                'tool' => $toolName,
            ]);

            return $this->forbidden('MCP client is not allowed to call this tool.');
        }

        if ($moduleName !== null && ! $client->allowsModule($moduleName)) {
            $this->auditDenied($request, $token, 'module_not_allowed', [
                'method' => $method,
                'tool' => $toolName,
                'module' => $moduleName,
            ]);

            return $this->forbidden('MCP client is not allowed to access this module.');
        }

        if ($this->requiresAdminScope($toolName, $moduleName) && ! $token->allowsScope((string) config('modules.ai.mcp.auth.admin_scope', 'mcp:admin'))) {
            $this->auditDenied($request, $token, 'missing_admin_scope', [
                'method' => $method,
                'tool' => $toolName,
                'module' => $moduleName,
            ]);

            return $this->forbidden('MCP token lacks the admin scope required for this tool.');
        }

        // Per-tool rate-limit gate runs first so a tool-specific
        // cap (e.g. 10/min on analytics.traffic_summary) wins
        // over the token-level cap. A request that survives
        // both gates increments both buckets.
        if ($toolName !== null && $this->isToolRateLimited($token, $toolName)) {
            $retryAfter = RateLimiter::availableIn($this->toolRateLimitKey($token, $toolName));

            $this->tokenManager->recordAuditEvent(
                client: $client,
                token: $token,
                action: 'token.rate_limited',
                metadata: [
                    'retry_after' => $retryAfter,
                    'method' => $method,
                    'tool' => $toolName,
                    'scope' => 'per_tool',
                ],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );

            return response()->json([
                'error' => 'Too many requests',
                'message' => 'MCP per-tool rate limit exceeded for ' . $toolName . '.',
                'retry_after' => $retryAfter,
            ], 429);
        }

        if ($this->isRateLimited($request, $token)) {
            $retryAfter = RateLimiter::availableIn($this->rateLimitKey($token));

            $this->tokenManager->recordAuditEvent(
                client: $client,
                token: $token,
                action: 'token.rate_limited',
                metadata: [
                    'retry_after' => $retryAfter,
                    'method' => $method,
                    'tool' => $toolName,
                    'scope' => 'token',
                ],
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            );

            return response()->json([
                'error' => 'Too many requests',
                'message' => 'MCP client rate limit exceeded.',
                'retry_after' => $retryAfter,
            ], 429);
        }

        $this->hitRateLimiter($token);
        if ($toolName !== null) {
            $this->hitToolRateLimiter($token, $toolName);
        }
        $this->tokenManager->recordUsage($token, $request->ip(), $request->userAgent());

        $context = new McpRequestContext(
            client: $client,
            token: $token,
            method: is_string($method) ? $method : null,
            toolName: $toolName,
            moduleName: $moduleName,
        );

        $request->attributes->set(McpRequestContext::class, $context);
        app()->instance(McpRequestContext::class, $context);

        return $next($request);
    }

    private function extractToolName(mixed $method, array $payload): ?string
    {
        if ($method !== 'tools/call') {
            return null;
        }

        $toolName = data_get($payload, 'params.name');

        return is_string($toolName) && $toolName !== '' ? $toolName : null;
    }

    private function extractModuleName(?string $toolName): ?string
    {
        if (! $toolName) {
            return null;
        }

        $parts = preg_split('/[.\/:]/', $toolName);

        return is_array($parts) && isset($parts[0]) && $parts[0] !== '' ? $parts[0] : null;
    }

    private function requiresAdminScope(?string $toolName, ?string $moduleName): bool
    {
        $adminOnlyTools = (array) config('modules.ai.mcp.auth.admin_only_tools', []);
        $adminOnlyModules = (array) config('modules.ai.mcp.auth.admin_only_modules', []);

        return ($toolName !== null && in_array($toolName, $adminOnlyTools, true))
            || ($moduleName !== null && in_array($moduleName, $adminOnlyModules, true));
    }

    private function auditDenied(Request $request, McpClientToken $token, string $reason, array $metadata = []): void
    {
        $token->loadMissing('client');

        $this->tokenManager->recordAuditEvent(
            client: $token->client,
            token: $token,
            action: 'token.denied',
            metadata: array_merge($metadata, ['reason' => $reason]),
            ipAddress: $request->ip(),
            userAgent: $request->userAgent(),
        );
    }

    private function unauthorized(Request $request, string $message): JsonResponse
    {
        Log::warning('mcp.auth.unauthorized', [
            'message' => $message,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'path' => $request->path(),
        ]);

        return response()->json([
            'error' => 'Unauthorized',
            'message' => $message,
        ], 401);
    }

    private function forbidden(string $message): JsonResponse
    {
        return response()->json([
            'error' => 'Forbidden',
            'message' => $message,
        ], 403);
    }

    private function isRateLimited(Request $request, McpClientToken $token): bool
    {
        $maxAttempts = $token->effectiveRateLimitPerMinute();

        if ($maxAttempts === null) {
            return false;
        }

        return RateLimiter::tooManyAttempts($this->rateLimitKey($token), $maxAttempts);
    }

    private function hitRateLimiter(McpClientToken $token): void
    {
        $maxAttempts = $token->effectiveRateLimitPerMinute();

        if ($maxAttempts === null) {
            return;
        }

        RateLimiter::hit($this->rateLimitKey($token), 60);
    }

    private function rateLimitKey(McpClientToken $token): string
    {
        return 'mcp-client-token:' . $token->id;
    }

    /**
     * Per-tool rate-limit gate. Reads the tool's cap from the
     * `per_tool_rate_limits` config map; returns false (= no limit)
     * when the tool isn't listed.
     */
    private function isToolRateLimited(McpClientToken $token, string $toolName): bool
    {
        $maxAttempts = $this->perToolRateLimit($toolName);
        if ($maxAttempts === null) {
            return false;
        }

        return RateLimiter::tooManyAttempts($this->toolRateLimitKey($token, $toolName), $maxAttempts);
    }

    private function hitToolRateLimiter(McpClientToken $token, string $toolName): void
    {
        if ($this->perToolRateLimit($toolName) === null) {
            return;
        }

        RateLimiter::hit($this->toolRateLimitKey($token, $toolName), 60);
    }

    private function toolRateLimitKey(McpClientToken $token, string $toolName): string
    {
        return 'mcp-client-token:' . $token->id . ':tool:' . $toolName;
    }

    private function perToolRateLimit(string $toolName): ?int
    {
        $map = (array) config('modules.ai.mcp.per_tool_rate_limits', []);
        if (! isset($map[$toolName])) {
            return null;
        }

        $limit = (int) $map[$toolName];
        return $limit > 0 ? $limit : null;
    }
}
