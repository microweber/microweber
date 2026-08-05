<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Tests\Feature;

use MicroweberPackages\Queue\Facades\ChunkedDispatcher as ChunkedDispatcherFacade;
use MicroweberPackages\Queue\Services\ChunkedDispatcher;
use MicroweberPackages\Queue\Services\QueueProcessor;
use MicroweberPackages\Queue\Tests\TestCase;

/**
 * Validates the package API surface that a standalone Laravel app would use.
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_classes_are_loadable(): void
    {
        $this->assertTrue(class_exists(ChunkedDispatcher::class));
        $this->assertTrue(class_exists(QueueProcessor::class));
        $this->assertTrue(class_exists(\MicroweberPackages\Queue\Providers\QueueServiceProvider::class));
        $this->assertTrue(class_exists(\MicroweberPackages\Queue\Models\Job::class));
        $this->assertTrue(class_exists(\MicroweberPackages\Queue\Models\FailedJob::class));
        $this->assertTrue(class_exists(\MicroweberPackages\Queue\Jobs\ProcessChunkJob::class));
    }

    public function test_facade_works(): void
    {
        $count = ChunkedDispatcherFacade::chunkCount(250, 100);
        $this->assertSame(3, $count);
    }


    public function test_process_queue_event_and_listener_exist(): void
    {
        $this->assertTrue(class_exists(\MicroweberPackages\Queue\Events\ProcessQueueEvent::class));
        $this->assertTrue(class_exists(\MicroweberPackages\Queue\Listeners\ProcessQueueListener::class));
    }
}
