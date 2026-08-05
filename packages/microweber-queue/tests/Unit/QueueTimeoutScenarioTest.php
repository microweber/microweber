<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Tests\Unit;

use Illuminate\Support\Facades\Bus;
use MicroweberPackages\Queue\Services\ChunkedDispatcher;
use MicroweberPackages\Queue\Tests\Fixtures\FakeChunkJob;
use MicroweberPackages\Queue\Tests\TestCase;

/**
 * Special scenario: a huge newsletter-style workload must not be dispatched
 * as a single job (which would hit the worker timeout). Chunked bus dispatch
 * keeps each job under the soft timeout threshold.
 */
class QueueTimeoutScenarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        FakeChunkJob::reset();
        // Soft timeout: one job may only process 50 "email sends"
        FakeChunkJob::$softTimeoutUnits = 50;
    }

    public function test_single_huge_job_would_hit_timeout(): void
    {
        // Simulates the anti-pattern: 10_000 emails in one job
        $job = new FakeChunkJob(range(1, 10000), ['campaign' => 99]);
        $job->handle();

        $this->assertNotEmpty(
            FakeChunkJob::$timeoutRisks,
            'A single job with 10_000 items must be flagged as a timeout risk'
        );
        $this->assertSame(10000, FakeChunkJob::$workUnitsProcessed);
    }

    public function test_chunked_dispatch_keeps_each_job_under_timeout(): void
    {
        FakeChunkJob::reset();
        FakeChunkJob::$softTimeoutUnits = 50;

        $subscriberIds = range(1, 10000);
        $chunkSize = 50; // exactly at soft timeout boundary

        $dispatcher = app(ChunkedDispatcher::class);
        $expectedChunks = $dispatcher->chunkCount(count($subscriberIds), $chunkSize);
        $this->assertSame(200, $expectedChunks);

        // Run factories synchronously to simulate what each chunk job would do
        $timeoutRisks = 0;
        foreach (array_chunk($subscriberIds, $chunkSize) as $chunk) {
            $job = new FakeChunkJob($chunk, ['campaign' => 99]);
            $job->handle();
            $timeoutRisks += count(FakeChunkJob::$timeoutRisks);
            FakeChunkJob::$timeoutRisks = []; // count per-job
        }

        $this->assertSame(0, $timeoutRisks, 'No chunk job should exceed the soft timeout');
        $this->assertSame(10000, FakeChunkJob::$workUnitsProcessed);
        $this->assertCount(200, FakeChunkJob::$handled);
        foreach (FakeChunkJob::$handled as $handled) {
            $this->assertLessThanOrEqual(
                FakeChunkJob::$softTimeoutUnits,
                count($handled['items'])
            );
        }
    }

    public function test_chunked_dispatch_via_bus_creates_many_jobs_not_one(): void
    {
        Bus::fake();

        $items = range(1, 10000);
        $chunkSize = 100;

        app(ChunkedDispatcher::class)->dispatch(
            items: $items,
            jobFactory: fn (array $chunk) => new FakeChunkJob($chunk),
            chunkSize: $chunkSize,
            queue: 'newsletter',
            name: 'newsletter-campaign-timeout-safe',
            useBatch: false,
        );

        // 10_000 / 100 = 100 jobs, never 1
        Bus::assertDispatched(FakeChunkJob::class, 100);
        Bus::assertDispatched(FakeChunkJob::class, function (FakeChunkJob $job): bool {
            return count($job->items) <= 100;
        });
    }

    public function test_config_default_chunk_size_avoids_timeout_for_newsletter_scale(): void
    {
        $chunkSize = (int) config('microweber-queue.chunk_size', 100);
        $this->assertGreaterThan(0, $chunkSize);
        $this->assertLessThanOrEqual(
            FakeChunkJob::$softTimeoutUnits * 2,
            $chunkSize,
            'Default chunk size should be modest enough for typical worker timeouts'
        );

        $chunks = app(ChunkedDispatcher::class)->chunkCount(10000, $chunkSize);
        $this->assertGreaterThan(1, $chunks, '10k items must produce more than one job');
    }
}
