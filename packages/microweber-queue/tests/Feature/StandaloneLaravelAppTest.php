<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Tests\Feature;

use MicroweberPackages\Queue\Providers\QueueServiceProvider;
use MicroweberPackages\Queue\Services\ChunkedDispatcher;
use MicroweberPackages\Queue\Services\QueueProcessor;
use MicroweberPackages\Queue\Tests\TestCase;

/**
 * Simulates a standalone Laravel app consuming the package.
 *
 * Verifies the public package surface that a path-repository install would expose.
 */
class StandaloneLaravelAppTest extends TestCase
{
    public function test_provider_registers_cleanly(): void
    {
        $this->assertTrue(
            $this->app->getProvider(QueueServiceProvider::class) !== null
            || $this->app->bound(ChunkedDispatcher::class)
        );
    }

    public function test_config_publish_path_exists(): void
    {
        $configFile = dirname(__DIR__, 2) . '/config/microweber-queue.php';
        $this->assertFileExists($configFile);
        $cfg = require $configFile;
        $this->assertIsArray($cfg);
        $this->assertArrayHasKey('chunk_size', $cfg);
        $this->assertArrayHasKey('process_limit', $cfg);
        $this->assertArrayHasKey('filament', $cfg);
    }

    public function test_services_are_bound(): void
    {
        $this->assertTrue($this->app->bound(ChunkedDispatcher::class));
        $this->assertTrue($this->app->bound(QueueProcessor::class));
        $this->assertInstanceOf(ChunkedDispatcher::class, app(ChunkedDispatcher::class));
        $this->assertInstanceOf(QueueProcessor::class, app(QueueProcessor::class));
    }

    public function test_no_cms_base_model_dependency_in_job_model(): void
    {
        $ref = new \ReflectionClass(\MicroweberPackages\Queue\Models\Job::class);
        $src = file_get_contents($ref->getFileName() ?: '');
        $this->assertIsString($src);
        $this->assertStringNotContainsString('BaseModel', $src);
        $this->assertStringNotContainsString('MicroweberPackages\\Database', $src);
    }

    public function test_chunked_dispatcher_usable_without_cms(): void
    {
        $d = app(ChunkedDispatcher::class);
        $this->assertSame(100, $d->chunkCount(10000, 100));
        $this->assertNull($d->dispatch([], fn (array $c) => new \MicroweberPackages\Queue\Tests\Fixtures\FakeChunkJob($c)));
    }

    public function test_helpers_available(): void
    {
        $this->assertTrue(function_exists('chunked_dispatch'));
        $this->assertTrue(function_exists('process_pending_queue'));
    }

    public function test_migrations_exist(): void
    {
        $dir = dirname(__DIR__, 2) . '/database/migrations';
        $this->assertDirectoryExists($dir);
        $files = glob($dir . '/*.php') ?: [];
        $this->assertNotEmpty($files);
    }
}
