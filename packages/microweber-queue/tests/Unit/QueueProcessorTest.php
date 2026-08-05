<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Tests\Unit;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Queue\Models\Job;
use MicroweberPackages\Queue\Services\QueueProcessor;
use MicroweberPackages\Queue\Tests\TestCase;

class QueueProcessorTest extends TestCase
{
    public function test_process_returns_zero_when_empty(): void
    {
        if (! Schema::hasTable('jobs')) {
            $this->markTestSkipped('jobs table not available');
        }

        Job::query()->delete();
        $count = app(QueueProcessor::class)->process();
        $this->assertSame(0, $count);
    }

    public function test_is_class_allowed_respects_prefixes(): void
    {
        $processor = app(QueueProcessor::class);

        $this->assertTrue($processor->isClassAllowed('MicroweberPackages\\Queue\\Jobs\\ProcessChunkJob'));
        $this->assertTrue($processor->isClassAllowed('Modules\\Newsletter\\Jobs\\ProcessCampaignSubscriber'));
        $this->assertFalse($processor->isClassAllowed('Evil\\MaliciousJob'));
    }

    public function test_job_model_display_name(): void
    {
        $job = new Job();
        $job->payload = json_encode([
            'displayName' => 'App\\Jobs\\ExampleJob',
            'data' => ['commandName' => 'App\\Jobs\\ExampleJob'],
        ], JSON_THROW_ON_ERROR);

        $this->assertSame('App\\Jobs\\ExampleJob', $job->display_name);
        $this->assertSame('App\\Jobs\\ExampleJob', $job->decodedPayload()['displayName']);
    }
}
