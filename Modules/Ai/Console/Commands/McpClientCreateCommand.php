<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Services\Mcp\McpClientTokenManager;

/**
 * Create an MCP client + an initial token in one shot, no Filament UI
 * or tinker required. The plain-text token is printed on stdout once
 * (matches Laravel Sanctum's `passport:client` UX) — operators must
 * capture it on the spot, the hash is all that's persisted.
 */
class McpClientCreateCommand extends Command
{
    protected $signature = 'ai:mcp:client:create
        {--name= : Human-readable client name (required)}
        {--scopes=mcp:access : Comma-separated abilities for the issued token (default: mcp:access)}
        {--tools= : Comma-separated tool allow-list ("*" to allow any; omit for unrestricted)}
        {--modules= : Comma-separated module allow-list ("*" to allow any; omit for unrestricted)}
        {--rate-limit=60 : Requests per minute per token (set 0 for unlimited)}
        {--token-name=initial : Display name for the issued token}
        {--token-ttl-days= : Token expiry in days (omit for no expiry)}
        {--print-token : Print the plain-text token on stdout (default: print only)}';

    protected $description = 'Create an MCP client and issue an initial bearer token.';

    public function handle(McpClientTokenManager $tokenManager): int
    {
        $name = (string) $this->option('name');
        if ($name === '') {
            $this->error('--name is required.');
            return self::FAILURE;
        }

        $scopes = $this->parseList((string) $this->option('scopes'));
        if ($scopes === []) {
            $this->error('--scopes must declare at least one ability (e.g. mcp:access).');
            return self::FAILURE;
        }

        $tools = $this->parseAllowList($this->option('tools'));
        $modules = $this->parseAllowList($this->option('modules'));

        $rateLimit = (int) $this->option('rate-limit');
        $rateLimitNullable = $rateLimit > 0 ? $rateLimit : null;

        $client = $tokenManager->createClient([
            'name' => $name,
            'allowed_scopes' => $scopes,
            'allowed_tools' => $tools,
            'allowed_modules' => $modules,
            'rate_limit_per_minute' => $rateLimitNullable,
            'is_active' => true,
        ]);

        $expiresAt = null;
        $ttl = $this->option('token-ttl-days');
        if ($ttl !== null && $ttl !== '') {
            $expiresAt = CarbonImmutable::now()->addDays((int) $ttl);
        }

        $generated = $tokenManager->issueToken(
            client: $client,
            name: (string) $this->option('token-name'),
            abilities: $scopes,
            expiresAt: $expiresAt,
        );

        $this->info("Created MCP client #{$client->id} '{$client->name}' (slug: {$client->slug}).");
        $this->line('Token:           ' . $generated->plainTextToken);
        $this->line('Token id:        ' . $generated->token->id);
        $this->line('Token name:      ' . $generated->token->name);
        $this->line('Token expires:   ' . ($expiresAt?->toIso8601String() ?? 'never'));
        $this->line('Allowed scopes:  ' . $this->renderAllowList($scopes));
        $this->line('Allowed tools:   ' . $this->renderAllowList($tools));
        $this->line('Allowed modules: ' . $this->renderAllowList($modules));
        $this->line('Rate limit:      ' . ($rateLimitNullable === null ? 'unlimited' : $rateLimitNullable . '/min'));
        $this->newLine();
        $this->warn(
            'The plain-text token is shown ONLY on this line — it is hashed in the '
            . 'database and cannot be recovered later. Capture it now or rotate the '
            . 'token via `php artisan ai:mcp:token:rotate`.'
        );

        return self::SUCCESS;
    }

    /**
     * Allow-list semantics on McpClient::allowsValue:
     *   - omitted / null  → unrestricted
     *   - "*"             → wildcard (same as null but explicit)
     *   - "foo,bar"       → exact-match list
     */
    private function parseAllowList(?string $raw): ?array
    {
        if ($raw === null || $raw === '') {
            return null;
        }

        $parsed = $this->parseList($raw);

        return $parsed === [] ? null : $parsed;
    }

    /**
     * @return list<string>
     */
    private function parseList(string $raw): array
    {
        $items = array_map('trim', explode(',', $raw));
        $items = array_values(array_filter($items, static fn (string $v): bool => $v !== ''));

        return array_values(array_unique($items));
    }

    private function renderAllowList(?array $list): string
    {
        if ($list === null) {
            return '<unrestricted>';
        }

        if ($list === []) {
            return '<deny-all>';
        }

        return implode(', ', $list);
    }
}
