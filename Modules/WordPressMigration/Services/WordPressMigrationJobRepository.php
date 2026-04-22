<?php

namespace Modules\WordPressMigration\Services;

use Illuminate\Support\Carbon;
use Modules\WordPressMigration\Models\WordPressMigrationJob;

/**
 * Persistence seam for wp_migration_jobs.
 *
 * Callers should never touch `source_url_hash` or the Eloquent
 * model constants directly — routing all writes through this
 * class keeps the idempotency rule ("one row per normalized
 * source URL") in one place and makes the status transitions
 * testable in isolation.
 *
 * Credential lifecycle (docs/adr/wordpress-migration.md §1):
 *   storeProbeResult($result, $password) sets a 24h expiry;
 *   pruneExpiredCredentials() NULLs both columns past that point
 *   and is called by a scheduled command (Phase 2 task).
 */
class WordPressMigrationJobRepository
{
    private const CREDENTIALS_TTL_HOURS = 24;

    /**
     * Upsert a probe result onto the job row keyed by the probe's
     * normalized source URL. Returns the persisted job.
     *
     * If a job already exists AND is in a running/terminal state,
     * the probe is recorded onto `probe_result`/`last_probed_at`
     * but status/progress are preserved — we don't want a
     * mid-import re-probe to reset the worker's cursor.
     *
     * @param string|null $wpApplicationPassword When non-null, stored
     *        encrypted with a 24h expiry. When null, existing
     *        credentials are left untouched (caller uses
     *        `clearCredentials()` to remove explicitly).
     * @param array<string, mixed> $options Operator-chosen overrides
     *        for the import run (mode override, filters, rate-limit
     *        knobs). Merged onto any existing options.
     */
    public function storeProbeResult(
        WordPressSiteProbeResult $result,
        ?string $wpApplicationPassword = null,
        array $options = [],
    ): WordPressMigrationJob {
        $hash = self::hashUrl($result->sourceUrl);

        $job = WordPressMigrationJob::query()
            ->where('source_url_hash', $hash)
            ->first();

        $probeArray = $result->toArray();
        $now = Carbon::now();

        if ($job === null) {
            $job = new WordPressMigrationJob();
            $job->source_url = $result->sourceUrl;
            $job->source_url_hash = $hash;
        }

        $job->source_host = $result->sourceHost;
        $job->probe_result = $probeArray;
        $job->last_probed_at = $now;

        if (!in_array($job->status, [
            WordPressMigrationJob::STATUS_RUNNING,
            ...WordPressMigrationJob::TERMINAL_STATUSES,
        ], true)) {
            $job->mode = $result->mode;
            $job->status = $result->mode === WordPressSiteProbeResult::MODE_UNREACHABLE
                ? WordPressMigrationJob::STATUS_UNREACHABLE
                : WordPressMigrationJob::STATUS_READY;
        }

        if ($options !== []) {
            $existing = $job->options?->getArrayCopy() ?? [];
            $job->options = array_replace($existing, $options);
        }

        if ($wpApplicationPassword !== null && $wpApplicationPassword !== '') {
            $job->encrypted_credentials = $wpApplicationPassword;
            $job->credentials_expire_at = $now->copy()->addHours(self::CREDENTIALS_TTL_HOURS);
        }

        $job->save();
        return $job;
    }

    public function findByUrl(string $sourceUrl): ?WordPressMigrationJob
    {
        $normalized = WordPressSiteProbe::normalizeUrl($sourceUrl);
        if ($normalized === null) {
            return null;
        }
        return WordPressMigrationJob::query()
            ->where('source_url_hash', self::hashUrl($normalized))
            ->first();
    }

    /**
     * @param array<string, mixed> $progress
     */
    public function updateProgress(WordPressMigrationJob $job, array $progress): WordPressMigrationJob
    {
        $existing = $job->progress?->getArrayCopy() ?? [];
        $job->progress = array_replace($existing, $progress);
        $job->save();
        return $job;
    }

    public function markRunning(WordPressMigrationJob $job): WordPressMigrationJob
    {
        $job->status = WordPressMigrationJob::STATUS_RUNNING;
        $job->started_at ??= Carbon::now();
        $job->last_error = null;
        $job->save();
        return $job;
    }

    public function markFinished(WordPressMigrationJob $job): WordPressMigrationJob
    {
        $job->status = WordPressMigrationJob::STATUS_FINISHED;
        $job->finished_at = Carbon::now();
        $job->last_error = null;
        $job->save();
        return $job;
    }

    public function markFailed(WordPressMigrationJob $job, string $error): WordPressMigrationJob
    {
        $job->status = WordPressMigrationJob::STATUS_FAILED;
        $job->finished_at = Carbon::now();
        $job->last_error = $error;
        $job->save();
        return $job;
    }

    public function markCanceled(WordPressMigrationJob $job): WordPressMigrationJob
    {
        $job->status = WordPressMigrationJob::STATUS_CANCELED;
        $job->finished_at = Carbon::now();
        $job->save();
        return $job;
    }

    public function clearCredentials(WordPressMigrationJob $job): WordPressMigrationJob
    {
        $job->encrypted_credentials = null;
        $job->credentials_expire_at = null;
        $job->save();
        return $job;
    }

    /**
     * NULL out encrypted_credentials and credentials_expire_at on
     * every row whose expiry has passed. Returns the number of
     * rows updated so the scheduled command can log a count.
     */
    public function pruneExpiredCredentials(): int
    {
        return WordPressMigrationJob::query()
            ->whereNotNull('encrypted_credentials')
            ->where('credentials_expire_at', '<', Carbon::now())
            ->update([
                'encrypted_credentials' => null,
                'credentials_expire_at' => null,
            ]);
    }

    public static function hashUrl(string $sourceUrl): string
    {
        return hash('sha256', $sourceUrl);
    }
}
