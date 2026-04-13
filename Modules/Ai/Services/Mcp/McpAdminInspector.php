<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Mcp;

use Illuminate\Support\Collection;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Services\McpServer;
use Modules\Ai\Services\Secrets\PassSecretStore;

class McpAdminInspector
{
    public function __construct(
        private readonly McpToolCatalog $toolCatalog,
        private readonly PassSecretStore $passSecretStore,
        private readonly McpServer $mcpServer,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function scopeOptions(): array
    {
        $scopes = collect(array_merge(
            (array) config('modules.ai.mcp.auth.required_abilities', []),
            [(string) config('modules.ai.mcp.auth.admin_scope', 'mcp:admin')]
        ))
            ->filter()
            ->unique()
            ->values();

        return ['*' => 'All scopes (*)'] + $scopes
            ->mapWithKeys(fn (string $scope): array => [$scope => $scope])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function moduleOptions(): array
    {
        $modules = collect($this->toolCatalog->allDefinitions())
            ->pluck('module')
            ->merge((array) config('modules.ai.mcp.auth.admin_only_modules', []))
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return ['*' => 'All modules (*)'] + $modules
            ->mapWithKeys(fn (string $module): array => [$module => $module])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function toolOptions(): array
    {
        $definitions = collect($this->toolCatalog->allDefinitions())
            ->sortKeys();

        return ['*' => 'All mapped tools (*)'] + $definitions
            ->mapWithKeys(fn (array $definition, string $toolName): array => [
                $toolName => $toolName . ' — ' . $definition['title'],
            ])
            ->all();
    }

    /**
     * @return array<int, array{option_key: string, label: string, reference: string|null, status: string, summary: string, color: string}>
     */
    public function providerSecretStatuses(): array
    {
        return collect($this->passSecretStore->aiProviderSecretMap())
            ->map(function (string $provider, string $optionKey): array {
                $storedValue = get_option($optionKey, 'ai');
                $label = strtoupper($provider) . ' API Key';
                $reference = is_string($storedValue) && $this->passSecretStore->isReference($storedValue) ? $storedValue : null;

                if (blank($storedValue)) {
                    return [
                        'option_key' => $optionKey,
                        'label' => $label,
                        'reference' => null,
                        'status' => 'missing',
                        'summary' => 'Not configured.',
                        'color' => 'gray',
                    ];
                }

                if ($reference !== null) {
                    if (! $this->passSecretStore->isEnabled()) {
                        return [
                            'option_key' => $optionKey,
                            'label' => $label,
                            'reference' => $reference,
                            'status' => 'store_disabled',
                            'summary' => "Stored as {$reference}, but pass support is currently disabled.",
                            'color' => 'warning',
                        ];
                    }

                    $exists = $this->passSecretStore->exists($reference);

                    return [
                        'option_key' => $optionKey,
                        'label' => $label,
                        'reference' => $reference,
                        'status' => $exists ? 'present' : 'missing_in_pass',
                        'summary' => $exists
                            ? "Stored in pass at {$reference}."
                            : "Expected pass entry {$reference} is missing.",
                        'color' => $exists ? 'success' : 'danger',
                    ];
                }

                return [
                    'option_key' => $optionKey,
                    'label' => $label,
                    'reference' => null,
                    'status' => 'plaintext',
                    'summary' => 'Legacy plaintext value is still stored in settings. Re-save it from AI Settings to migrate into pass.',
                    'color' => 'warning',
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array{status: string, color: string, summary: string, active_tokens: int, visible_tools: int}
     */
    public function connectionHealth(McpClient $client): array
    {
        $client->loadMissing('tokens');

        $activeTokenCount = $client->tokens->filter(fn (McpClientToken $token): bool => $token->isActive())->count();
        $activeToken = $client->tokens
            ->sortByDesc('created_at')
            ->first(fn (McpClientToken $token): bool => $token->isActive());

        if (! (bool) config('modules.ai.mcp.enabled', false)) {
            return [
                'status' => 'disabled',
                'color' => 'danger',
                'summary' => 'The MCP server is disabled in configuration.',
                'active_tokens' => $activeTokenCount,
                'visible_tools' => 0,
            ];
        }

        if (! $client->isAvailable()) {
            return [
                'status' => 'inactive',
                'color' => 'warning',
                'summary' => 'This client is inactive or revoked.',
                'active_tokens' => $activeTokenCount,
                'visible_tools' => 0,
            ];
        }

        if (! $activeToken instanceof McpClientToken) {
            return [
                'status' => 'no_token',
                'color' => 'warning',
                'summary' => 'No active token is available for this client. Issue a key to complete the health check.',
                'active_tokens' => 0,
                'visible_tools' => 0,
            ];
        }

        $context = new McpRequestContext($client, $activeToken, 'tools/list', null, null);
        $initialize = $this->mcpServer->handle([
            'jsonrpc' => '2.0',
            'id' => 'health-init',
            'method' => 'initialize',
        ], $context);
        $toolsList = $this->mcpServer->handle([
            'jsonrpc' => '2.0',
            'id' => 'health-tools',
            'method' => 'tools/list',
        ], $context);

        $hasInitializeError = isset($initialize['error']);
        $hasToolsError = isset($toolsList['error']);
        $visibleTools = count((array) data_get($toolsList, 'result.tools', []));

        if ($hasInitializeError || $hasToolsError) {
            return [
                'status' => 'degraded',
                'color' => 'danger',
                'summary' => 'Initialize/tools discovery failed for the current MCP token.',
                'active_tokens' => $activeTokenCount,
                'visible_tools' => $visibleTools,
            ];
        }

        return [
            'status' => 'healthy',
            'color' => 'success',
            'summary' => "Healthy: initialize succeeded and this client can currently see {$visibleTools} tool(s).",
            'active_tokens' => $activeTokenCount,
            'visible_tools' => $visibleTools,
        ];
    }

    public function providerSecretSummary(string $optionKey): string
    {
        return $this->providerSecretStatusesCollection()
            ->firstWhere('option_key', $optionKey)['summary'] ?? 'Not configured.';
    }

    public function providerSecretColor(string $optionKey): string
    {
        return $this->providerSecretStatusesCollection()
            ->firstWhere('option_key', $optionKey)['color'] ?? 'gray';
    }

    /**
     * @return Collection<int, array{option_key: string, label: string, reference: string|null, status: string, summary: string, color: string}>
     */
    private function providerSecretStatusesCollection(): Collection
    {
        return collect($this->providerSecretStatuses());
    }
}
