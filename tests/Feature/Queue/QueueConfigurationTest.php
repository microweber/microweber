<?php

namespace Tests\Feature\Queue;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class QueueConfigurationTest extends TestCase
{
    /**
     * Test that queue configuration is loaded correctly
     */
    public function test_queue_configuration_is_loaded(): void
    {
        $config = config('queue');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('default', $config);
        $this->assertArrayHasKey('connections', $config);
        $this->assertArrayHasKey('failed', $config);
        $this->assertArrayHasKey('batching', $config);
    }

    /**
     * Test database queue connection configuration
     */
    public function test_database_queue_connection_is_configured(): void
    {
        $config = config('queue.connections.database');

        $this->assertIsArray($config);
        $this->assertEquals('database', $config['driver']);
        $this->assertArrayHasKey('table', $config);
        $this->assertArrayHasKey('queue', $config);
        $this->assertArrayHasKey('retry_after', $config);
    }

    /**
     * Test Redis queue connection configuration
     */
    public function test_redis_queue_connection_is_configured(): void
    {
        $config = config('queue.connections.redis');

        $this->assertIsArray($config);
        $this->assertEquals('redis', $config['driver']);
        $this->assertArrayHasKey('connection', $config);
        $this->assertArrayHasKey('queue', $config);
        $this->assertArrayHasKey('retry_after', $config);
    }

    /**
     * Test failed jobs configuration
     */
    public function test_failed_jobs_configuration_is_set(): void
    {
        $config = config('queue.failed');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('driver', $config);
        $this->assertArrayHasKey('database', $config);
        $this->assertArrayHasKey('table', $config);
        $this->assertEquals('failed_jobs', $config['table']);
    }

    /**
     * Test job batches configuration
     */
    public function test_job_batches_configuration_is_set(): void
    {
        $config = config('queue.batching');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('database', $config);
        $this->assertArrayHasKey('table', $config);
        $this->assertEquals('job_batches', $config['table']);
    }

    /**
     * Test that jobs table exists in database
     */
    public function test_jobs_table_exists_in_database(): void
    {
        $this->assertTrue(
            Schema::hasTable('jobs'),
            'The jobs table should exist in the database'
        );
    }

    /**
     * Test that failed_jobs table exists in database
     */
    public function test_failed_jobs_table_exists_in_database(): void
    {
        $this->assertTrue(
            Schema::hasTable('failed_jobs'),
            'The failed_jobs table should exist in the database'
        );
    }

    /**
     * Test that job_batches table exists in database
     */
    public function test_job_batches_table_exists_in_database(): void
    {
        $this->assertTrue(
            Schema::hasTable('job_batches'),
            'The job_batches table should exist in the database'
        );
    }

    /**
     * Test that queue facade can be resolved
     */
    public function test_queue_facade_is_available(): void
    {
        $this->assertInstanceOf(
            \Illuminate\Queue\QueueManager::class,
            Queue::getFacadeRoot()
        );
    }

    /**
     * Test database queue has required columns
     */
    public function test_jobs_table_has_required_columns(): void
    {
        $columns = Schema::getColumnListing('jobs');

        $requiredColumns = ['id', 'queue', 'payload', 'attempts', 'reserved_at', 'available_at', 'created_at'];

        foreach ($requiredColumns as $column) {
            $this->assertContains(
                $column,
                $columns,
                "The jobs table should have a '{$column}' column"
            );
        }
    }

    /**
     * Test failed_jobs table has required columns
     */
    public function test_failed_jobs_table_has_required_columns(): void
    {
        $columns = Schema::getColumnListing('failed_jobs');

        $requiredColumns = ['id', 'uuid', 'connection', 'queue', 'payload', 'exception', 'failed_at'];

        foreach ($requiredColumns as $column) {
            $this->assertContains(
                $column,
                $columns,
                "The failed_jobs table should have a '{$column}' column"
            );
        }
    }
}
