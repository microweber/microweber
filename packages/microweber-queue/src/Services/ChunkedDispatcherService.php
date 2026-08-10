<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Services;

use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use InvalidArgumentException;
use Throwable;

/**
 * Splits large workloads into chunk jobs and dispatches them via Laravel Bus.
 *
 * Prevents single-job timeouts when processing huge queues (e.g. newsletter
 * bulk email of 10_000 recipients) by turning one logical job into many
 * smaller jobs, optionally wrapped in a Bus batch.
 */
class ChunkedDispatcherService
{
    /**
     * Dispatch items as chunked jobs.
     *
     * @param  iterable<int, mixed>  $items
     * @param  callable(array<int, mixed>): ShouldQueue  $jobFactory
     *                                                                 Receives a chunk of items and must return a queueable job instance.
     * @param  int|null  $chunkSize  Items per job (defaults to config microweber-queue.chunk_size)
     * @param  string|null  $queue  Queue connection name / queue name
     * @param  string|null  $name  Optional batch name
     * @param  bool  $useBatch  When true (default) uses Bus::batch(); otherwise dispatches each job individually
     *
     * @throws InvalidArgumentException
     * @throws Throwable
     */
    public function dispatch(
        iterable $items,
        callable $jobFactory,
        ?int $chunkSize = null,
        ?string $queue = null,
        ?string $name = null,
        bool $useBatch = true,
    ): ?Batch {
        $chunkSize = $chunkSize ?? (int) config('microweber-queue.chunk_size', 100);
        if ($chunkSize < 1) {
            throw new InvalidArgumentException('chunkSize must be at least 1.');
        }

        $queue = $queue ?? (string) config('microweber-queue.default_queue', 'default');

        $collection = $this->toCollection($items);
        if ($collection->isEmpty()) {
            return null;
        }

        /** @var list<ShouldQueue> $jobs */
        $jobs = [];

        foreach ($collection->chunk($chunkSize) as $chunk) {
            /** @var array<int, mixed> $chunkItems */
            $chunkItems = $chunk->values()->all();
            $job = $jobFactory($chunkItems);

            if (! $job instanceof ShouldQueue) {
                throw new InvalidArgumentException(
                    'jobFactory must return an instance of ' . ShouldQueue::class
                );
            }

            if (method_exists($job, 'onQueue') && $queue !== '') {
                $job->onQueue($queue);
            }

            $jobs[] = $job;
        }

        if ($jobs === []) {
            return null;
        }

        if (! $useBatch) {
            foreach ($jobs as $job) {
                dispatch($job);
            }

            return null;
        }

        $pending = Bus::batch($jobs);

        if ($name !== null && $name !== '') {
            $pending->name($name);
        }

        if ($queue !== '') {
            $pending->onQueue($queue);
        }

        return $pending->dispatch();
    }

    /**
     * Dispatch a list of already-built jobs in Bus batches of $batchSize.
     *
     * Useful when each item is already a job (e.g. ProcessCampaignSubscriber)
     * and you only need to group them so a batch of 10_000 is not one fire-and-forget
     * loop without bus tracking.
     *
     * @param  list<ShouldQueue>|Collection<int, ShouldQueue>  $jobs
     * @return list<Batch>
     *
     * @throws Throwable
     */
    public function dispatchJobs(
        array|Collection $jobs,
        int $batchSize = 500,
        ?string $queue = null,
        ?string $name = null,
    ): array {
        if ($batchSize < 1) {
            throw new InvalidArgumentException('batchSize must be at least 1.');
        }

        $queue = $queue ?? (string) config('microweber-queue.default_queue', 'default');
        $collection = $jobs instanceof Collection ? $jobs->values() : Collection::make($jobs)->values();

        if ($collection->isEmpty()) {
            return [];
        }

        $batches = [];
        $index = 0;

        foreach ($collection->chunk($batchSize) as $chunk) {
            /** @var list<ShouldQueue> $chunkJobs */
            $chunkJobs = [];
            foreach ($chunk as $job) {
                if (! $job instanceof ShouldQueue) {
                    throw new InvalidArgumentException('All jobs must implement ' . ShouldQueue::class);
                }
                if (method_exists($job, 'onQueue') && $queue !== '') {
                    $job->onQueue($queue);
                }
                $chunkJobs[] = $job;
            }

            $pending = Bus::batch($chunkJobs);
            $batchName = $name !== null && $name !== ''
                ? $name . '-' . $index
                : null;
            if ($batchName !== null) {
                $pending->name($batchName);
            }
            if ($queue !== '') {
                $pending->onQueue($queue);
            }

            $batches[] = $pending->dispatch();
            $index++;
        }

        return $batches;
    }

    /**
     * Count how many chunk jobs would be created for a given item count.
     */
    public function chunkCount(int $itemCount, ?int $chunkSize = null): int
    {
        if ($itemCount <= 0) {
            return 0;
        }

        $chunkSize = $chunkSize ?? (int) config('microweber-queue.chunk_size', 100);
        if ($chunkSize < 1) {
            return 0;
        }

        return (int) ceil($itemCount / $chunkSize);
    }

    /**
     * @param  iterable<int, mixed>  $items
     * @return Collection<int, mixed>
     */
    protected function toCollection(iterable $items): Collection
    {
        if ($items instanceof Collection) {
            return $items->values();
        }

        if (is_array($items)) {
            return Collection::make($items)->values();
        }

        return Collection::make(iterator_to_array($items, false))->values();
    }
}
