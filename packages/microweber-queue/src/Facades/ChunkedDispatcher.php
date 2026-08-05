<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Facades;

use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use MicroweberPackages\Queue\Services\ChunkedDispatcher as ChunkedDispatcherService;

/**
 * @method static Batch|null dispatch(iterable<int, mixed> $items, callable(array<int, mixed>): \Illuminate\Contracts\Queue\ShouldQueue $jobFactory, ?int $chunkSize = null, ?string $queue = null, ?string $name = null, bool $useBatch = true)
 * @method static list<Batch> dispatchJobs(list<\Illuminate\Contracts\Queue\ShouldQueue>|Collection<int, \Illuminate\Contracts\Queue\ShouldQueue> $jobs, int $batchSize = 500, ?string $queue = null, ?string $name = null)
 * @method static int chunkCount(int $itemCount, ?int $chunkSize = null)
 *
 * @see ChunkedDispatcherService
 */
class ChunkedDispatcher extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ChunkedDispatcherService::class;
    }
}
