<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Services;

use Illuminate\Support\Facades\Log;
use MicroweberPackages\Queue\Models\Job;
use Throwable;

/**
 * Processes pending rows from the jobs table (shared-hosting / cron style).
 *
 * Validates the job class name from the JSON payload before unserializing,
 * then runs the job synchronously so large batches can be drained in chunks.
 */
class QueueProcessor
{
    public function process(?int $limit = null): int
    {
        $limit = $limit ?? (int) config('microweber-queue.process_limit', 10);
        $maxAttempts = (int) config('microweber-queue.process_max_attempts', 10);

        if ($limit < 1) {
            return 0;
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, Job> $pending */
        $pending = Job::query()
            ->where(function ($query) use ($maxAttempts): void {
                $query->where('attempts', '<', $maxAttempts)
                    ->orWhereNull('attempts');
            })
            ->where(function ($query): void {
                $query->whereNull('reserved')
                    ->orWhere('reserved', 0);
            })
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($pending->isEmpty()) {
            return 0;
        }

        foreach ($pending as $job) {
            $job->reserved = 1;
            $job->save();
        }

        $processed = 0;

        foreach ($pending as $job) {
            if ($this->runJob($job)) {
                $processed++;
            }
        }

        return $processed;
    }

    /**
     * Run a single job row (used by admin "Run now" and process loop).
     */
    public function runJob(Job $job): bool
    {
        $payload = $job->decodedPayload();
        $data = $payload['data'] ?? null;
        $dataArr = is_array($data) ? $data : [];

        $commandData = $dataArr['command'] ?? null;
        $commandNameRaw = $dataArr['commandName'] ?? ($payload['displayName'] ?? null);
        $commandName = is_string($commandNameRaw) ? $commandNameRaw : null;

        if (! is_string($commandData) || $commandData === '') {
            $job->delete();

            return false;
        }

        if ($commandName !== null && $commandName !== '' && ! $this->isClassAllowed($commandName)) {
            Log::warning('microweber-queue: rejected job with disallowed class', [
                'job_id' => $job->id,
                'class' => $commandName,
            ]);
            $this->markFailedAttempt($job);

            return false;
        }

        $allowed = $this->buildAllowedClassesList($commandName);

        try {
            $command = @unserialize($commandData, ['allowed_classes' => $allowed]);
        } catch (Throwable $e) {
            Log::error('microweber-queue: failed to unserialize job payload', [
                'job_id' => $job->id,
                'error' => $e->getMessage(),
            ]);
            $this->markFailedAttempt($job);

            return false;
        }

        if (! is_object($command) || $command instanceof \__PHP_Incomplete_Class) {
            Log::warning('microweber-queue: incomplete or invalid job object', [
                'job_id' => $job->id,
            ]);
            $this->markFailedAttempt($job);

            return false;
        }

        if (! $this->isClassAllowed($command::class)) {
            Log::warning('microweber-queue: rejected unserialized class', [
                'job_id' => $job->id,
                'class' => $command::class,
            ]);
            $this->markFailedAttempt($job);

            return false;
        }

        try {
            $this->executeCommand($command);
            $job->delete();

            return true;
        } catch (Throwable $e) {
            Log::error('microweber-queue: job execution failed', [
                'job_id' => $job->id,
                'error' => $e->getMessage(),
            ]);
            $this->markFailedAttempt($job);

            return false;
        }
    }

    protected function executeCommand(object $command): void
    {
        if (function_exists('dispatch_sync')) {
            dispatch_sync($command);

            return;
        }

        if (method_exists($command, 'handle')) {
            app()->call([$command, 'handle']);

            return;
        }

        throw new \RuntimeException('Command is not executable: ' . $command::class);
    }

    protected function markFailedAttempt(Job $job): void
    {
        $job->reserved = null;
        $job->attempts = (int) $job->attempts + 1;
        $job->save();
    }

    public function isClassAllowed(string $class): bool
    {
        /** @var list<class-string|string> $exact */
        $exact = (array) config('microweber-queue.allowed_job_classes', []);
        if (in_array($class, $exact, true)) {
            return true;
        }

        /** @var list<string> $prefixes */
        $prefixes = (array) config('microweber-queue.allowed_job_class_prefixes', []);
        foreach ($prefixes as $prefix) {
            if (is_string($prefix) && $prefix !== '' && str_starts_with($class, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<class-string|string>
     */
    protected function buildAllowedClassesList(?string $commandName): array
    {
        /** @var list<class-string|string> $classes */
        $classes = (array) config('microweber-queue.allowed_job_classes', []);
        $classes[] = Job::class;

        if ($commandName !== null && $commandName !== '' && $this->isClassAllowed($commandName)) {
            $classes[] = $commandName;
        }

        return array_values(array_unique($classes));
    }
}
