<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Illuminate\Console\Command;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;

/**
 * List MCP clients + their token state in tabular form. Mirrors the
 * Filament McpClientResource overview but lives in the CLI so it
 * can be inspected during deploy / smoke runs without an admin
 * session.
 */
class McpClientListCommand extends Command
{
    protected $signature = 'ai:mcp:client:list
        {--all : Include disabled clients (default: only is_active=true)}';

    protected $description = 'List MCP clients with token counts and last-used timestamps.';

    public function handle(): int
    {
        $query = McpClient::query()
            ->withCount([
                'tokens',
                'tokens as active_tokens_count' => fn ($q) => $q->whereNull('revoked_at'),
            ])
            ->orderBy('id');

        if (! $this->option('all')) {
            $query->where('is_active', true);
        }

        $clients = $query->get();

        if ($clients->isEmpty()) {
            $this->warn('No MCP clients registered yet — create one via `php artisan ai:mcp:client:create`.');
            return self::SUCCESS;
        }

        $rows = $clients->map(fn (McpClient $client) => [
            'id' => $client->id,
            'slug' => $client->slug,
            'name' => $client->name,
            'active' => $client->is_active ? 'yes' : 'no',
            'tokens' => sprintf('%d/%d', $client->active_tokens_count, $client->tokens_count),
            'rate' => $client->rate_limit_per_minute === null ? '∞' : ((string) $client->rate_limit_per_minute) . '/min',
            'last_used' => $client->last_used_at?->toIso8601String() ?? 'never',
        ])->all();

        $this->table(
            ['id', 'slug', 'name', 'active', 'tokens (active/total)', 'rate', 'last_used'],
            $rows,
        );

        $this->info(sprintf(
            '%d client%s shown%s.',
            $clients->count(),
            $clients->count() === 1 ? '' : 's',
            $this->option('all') ? '' : ' (only active — pass --all to include disabled)',
        ));

        return self::SUCCESS;
    }
}
