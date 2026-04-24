<?php

namespace Modules\WordPressMigration\Console\Commands;

use Illuminate\Console\Command;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\StagingCommitter;
use Modules\WordPressMigration\Services\WordPressContentMapper;

/**
 * Headless commit — promotes staged rows for a job onto live
 * `content` via the same {@see StagingCommitter} the Filament
 * preview page uses. Shares the chunked-transaction semantics,
 * whole-batch rollback on failure, and last-commit-error
 * persistence so `--retry-failed` lands on exactly the same code
 * path.
 *
 * Usage:
 *   php artisan microweber:import:wordpress:commit 42
 *   php artisan microweber:import:wordpress:commit 42 --yes
 *   php artisan microweber:import:wordpress:commit 42 --retry-failed --yes
 *
 * Exit codes:
 *   0  commit finished with zero failures
 *   2  validation error
 *   4  job not found
 *   5  commit finished with >= 1 failed row
 */
class ImportWordPressCommitCommand extends Command
{
    protected $signature = 'microweber:import:wordpress:commit
        {job : Migration job id whose staging rows to promote}
        {--yes : Skip the confirmation prompt}
        {--retry-failed : Retry only rows flagged with last_commit_error}';

    protected $description = 'Commit staged WordPress migration rows onto live content';

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

        $retryOnly = (bool) $this->option('retry-failed');

        $base = StagingContent::query()->where('job_id', $jobId)->where('excluded', false);
        $candidates = $retryOnly
            ? (clone $base)->whereNotNull('last_commit_error')->count()
            : (clone $base)->count();

        if ($candidates === 0) {
            $this->warn($retryOnly
                ? 'No staging rows flagged as failed — nothing to retry.'
                : 'No staging rows eligible for commit.');
            return 0;
        }

        $this->line(sprintf(
            '%s job <info>#%d</info> — %d %srow(s) eligible.',
            $retryOnly ? 'Retrying failed rows for' : 'Committing',
            $jobId,
            $candidates,
            $retryOnly ? 'failed ' : '',
        ));

        if (! $this->option('yes') && ! $this->confirm('Proceed?', true)) {
            $this->line('Aborted.');
            return 0;
        }

        $committer = app()->bound(StagingCommitter::class)
            ? app(StagingCommitter::class)
            : new StagingCommitter(new WordPressContentMapper());

        $report = $retryOnly
            ? $committer->commitFailedOnly($jobId)
            : $committer->commit($jobId);

        $committed = $report->committedCount();
        $failed = $report->failedCount();

        $this->info(sprintf(
            '%s: %d committed, %d skipped (excluded), %d failed.',
            $report->isSuccessful() ? 'Commit complete' : 'Commit finished with errors',
            $committed,
            $report->skipped,
            $failed,
        ));

        return $report->isSuccessful() ? 0 : 5;
    }
}
