<?php

declare(strict_types=1);

namespace Modules\Ai\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Prune old `mcp_client_token_events` rows. The middleware records
 * one event per request (token.used) plus per-denial events
 * (token.denied, rate.limited, etc.); for a busy AI integration
 * the table grows by tens of thousands of rows per day. This
 * command lets operators set a retention horizon.
 *
 * Defaults to 90 days — matches the suggested
 * `AI_MCP_TOKEN_DEFAULT_TTL_DAYS` so audit retention can't be
 * shorter than typical token lifetime.
 *
 * Use `--dry-run` to preview the cut without writing.
 */
class McpPruneAuditCommand extends Command
{
    protected $signature = 'ai:mcp:prune-audit
        {--older-than=90 : Retention horizon in days (default: 90)}
        {--dry-run : Print how many rows would be deleted without deleting}';

    protected $description = 'Delete old mcp_client_token_events rows beyond a retention horizon.';

    public function handle(): int
    {
        $days = (int) $this->option('older-than');
        if ($days < 1) {
            $this->error('--older-than must be a positive integer (days).');
            return self::FAILURE;
        }

        $cutoff = CarbonImmutable::now()->subDays($days);
        $query = DB::table('mcp_client_token_events')->where('created_at', '<', $cutoff);

        $count = (int) $query->count();
        if ($count === 0) {
            $this->info(
                "No mcp_client_token_events rows older than {$days} day(s) "
                . "({$cutoff->toIso8601String()}). Nothing to prune."
            );
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info("Dry run — {$count} mcp_client_token_events row(s) would be deleted (older than {$cutoff->toIso8601String()}).");
            return self::SUCCESS;
        }

        $deleted = (int) $query->delete();
        $this->info("Pruned {$deleted} mcp_client_token_events row(s) (older than {$cutoff->toIso8601String()}).");

        return self::SUCCESS;
    }
}
