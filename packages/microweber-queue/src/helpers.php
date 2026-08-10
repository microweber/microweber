<?php

declare(strict_types=1);

use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use MicroweberPackages\Queue\Services\ChunkedDispatcherService;
use MicroweberPackages\Queue\Services\QueueProcessor;

if (! function_exists('chunked_dispatch')) {
    /**
     * Dispatch a large collection of items as chunked queue jobs via the bus.
     *
     * @param  iterable<int, mixed>  $items
     * @param  callable(array<int, mixed>): ShouldQueue  $jobFactory
     */
    function chunked_dispatch(
        iterable $items,
        callable $jobFactory,
        ?int $chunkSize = null,
        ?string $queue = null,
        ?string $name = null,
        bool $useBatch = true,
    ): ?Batch {
        /** @var ChunkedDispatcher $dispatcher */
        $dispatcher = app(ChunkedDispatcher::class);

        return $dispatcher->dispatch($items, $jobFactory, $chunkSize, $queue, $name, $useBatch);
    }
}

if (! function_exists('process_pending_queue')) {
    /**
     * Process a batch of pending jobs from the jobs table.
     */
    function process_pending_queue(?int $limit = null): int
    {
        /** @var QueueProcessor $processor */
        $processor = app(QueueProcessor::class);

        return $processor->process($limit);
    }
}
