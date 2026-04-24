<?php

namespace Modules\WordPressMigration\Console\Commands;

use Illuminate\Console\Command;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;

/**
 * Headless status probe for a WordPress migration job.
 *
 * Usage:
 *   php artisan microweber:import:wordpress:status 42
 *   php artisan microweber:import:wordpress:status 42 --json
 *
 * Prints the job's status, mode, source URL, progress counters
 * (processed / imported / failed / total), and staging snapshot
 * counts (staged / excluded / last-error) so a CI pipeline can
 * decide whether to proceed to commit.
 *
 * Exit codes:
 *   0  job exists (regardless of status — the status itself is
 *      the signal, not an error)
 *   2  validation error (missing / malformed argument)
 *   4  job not found
 */
class ImportWordPressStatusCommand extends Command
{
    protected $signature = 'microweber:import:wordpress:status
        {job : Migration job id}
        {--json : Emit machine-readable JSON instead of human summary}';

    protected $description = 'Show the status and counters for a WordPress migration job';

    public function handle(): int
    {
        $raw = (string) $this->argument('job');
        if (! ctype_digit($raw) || (int) $raw <= 0) {
            $this->error('Job id must be a positive integer — got: ' . $raw);
            return 2;
        }

        $jobId = (int) $raw;
        $job = WordPressMigrationJob::query()->find($jobId);
        if ($job === null) {
            $this->error("Job #{$jobId} not found.");
            return 4;
        }

        $progress = $job->progress?->getArrayCopy() ?? [];
        $stagingBase = StagingContent::query()->where('job_id', $jobId);
        $stagedCount = (clone $stagingBase)->where('excluded', false)->count();
        $excludedCount = (clone $stagingBase)->where('excluded', true)->count();
        $failedStaging = (clone $stagingBase)->whereNotNull('last_commit_error')->count();

        $payload = [
            'job_id' => $jobId,
            'status' => (string) $job->status,
            'mode' => $job->mode,
            'source_url' => (string) $job->source_url,
            'source_host' => (string) $job->source_host,
            'started_at' => $job->started_at?->toIso8601String(),
            'finished_at' => $job->finished_at?->toIso8601String(),
            'progress' => [
                'processed' => (int) ($progress['processed'] ?? $progress['imported'] ?? $progress['done'] ?? 0),
                'imported' => (int) ($progress['imported'] ?? 0),
                'failed' => (int) ($progress['failed'] ?? 0),
                'total' => isset($progress['total']) ? (int) $progress['total'] : null,
                'stop_reason' => $progress['stop_reason'] ?? null,
            ],
            'staging' => [
                'staged' => $stagedCount,
                'excluded' => $excludedCount,
                'last_commit_error_rows' => $failedStaging,
            ],
            'last_error' => $job->last_error,
        ];

        if ($this->option('json')) {
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return 0;
        }

        $this->line("Job <info>#{$jobId}</info> · <info>{$payload['source_host']}</info>");
        $this->line("  Status:   {$payload['status']}" . ($payload['mode'] ? " ({$payload['mode']})" : ''));
        $this->line("  URL:      {$payload['source_url']}");
        $this->line("  Started:  " . ($payload['started_at'] ?? '—'));
        $this->line("  Finished: " . ($payload['finished_at'] ?? '—'));
        $this->line('');
        $this->line('  Progress:');
        $this->line("    processed: {$payload['progress']['processed']}");
        $this->line("    imported:  {$payload['progress']['imported']}");
        $this->line("    failed:    {$payload['progress']['failed']}");
        $this->line('    total:     ' . ($payload['progress']['total'] ?? '—'));
        if ($payload['progress']['stop_reason']) {
            $this->line('    stop:      ' . $payload['progress']['stop_reason']);
        }
        $this->line('');
        $this->line('  Staging:');
        $this->line("    staged:    {$payload['staging']['staged']}");
        $this->line("    excluded:  {$payload['staging']['excluded']}");
        $this->line("    failed:    {$payload['staging']['last_commit_error_rows']}");

        if ($payload['last_error']) {
            $this->line('');
            $this->warn('  Last error: ' . $payload['last_error']);
        }

        return 0;
    }
}
