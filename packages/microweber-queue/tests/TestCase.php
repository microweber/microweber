<?php

declare(strict_types=1);

namespace MicroweberPackages\Queue\Tests;

/**
 * Base test case for the microweber-queue package.
 *
 * Uses the full CMS application when available (Microweber monorepo),
 * otherwise Orchestra Testbench for standalone package testing.
 */
if (class_exists(\Orchestra\Testbench\TestCase::class) && ! trait_exists(\Tests\CreatesApplication::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                \MicroweberPackages\Queue\Providers\QueueServiceProvider::class,
                \MicroweberPackages\Queue\Providers\QueueEventServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
            $app['config']->set('app.url', 'http://localhost');
            $app['config']->set('database.default', 'testing');
            $app['config']->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
            $app['config']->set('queue.default', 'sync');
            $app['config']->set('queue.batching.database', 'testing');
            $app['config']->set('queue.batching.table', 'job_batches');
            $app['config']->set('queue.failed.database', 'testing');
            $app['config']->set('queue.failed.table', 'failed_jobs');
            $app['config']->set('microweber-queue.chunk_size', 100);
            $app['config']->set('microweber-queue.process_limit', 10);
            $app['config']->set('microweber-queue.allowed_job_class_prefixes', [
                'App\\Jobs\\',
                'Modules\\',
                'MicroweberPackages\\',
                'MicroweberPackages\\Queue\\Tests\\',
            ]);
        }

        protected function setUp(): void
        {
            parent::setUp();
            $this->loadPackageMigrations();
        }

        protected function loadPackageMigrations(): void
        {
            $migrations = dirname(__DIR__) . '/database/migrations';
            if (is_dir($migrations)) {
                $this->loadMigrationsFrom($migrations);
            }

            // job_batches is required for Bus::batch
            if (! \Illuminate\Support\Facades\Schema::hasTable('job_batches')) {
                \Illuminate\Support\Facades\Schema::create('job_batches', function ($table): void {
                    $table->string('id')->primary();
                    $table->string('name');
                    $table->integer('total_jobs');
                    $table->integer('pending_jobs');
                    $table->integer('failed_jobs');
                    $table->longText('failed_job_ids');
                    $table->mediumText('options')->nullable();
                    $table->integer('cancelled_at')->nullable();
                    $table->integer('created_at');
                    $table->integer('finished_at')->nullable();
                });
            }
        }
    }
} else {
    abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
    {
        use \Tests\CreatesApplication;

        protected function setUp(): void
        {
            parent::setUp();

            if (config('microweber-queue') === null) {
                config(['microweber-queue' => require __DIR__ . '/../config/microweber-queue.php']);
            }

            config([
                'microweber-queue.chunk_size' => 100,
                'microweber-queue.process_limit' => 10,
                'microweber-queue.allowed_job_class_prefixes' => array_values(array_unique(array_merge(
                    (array) config('microweber-queue.allowed_job_class_prefixes', []),
                    ['MicroweberPackages\\Queue\\Tests\\'],
                ))),
            ]);

            if (! $this->app->bound(\MicroweberPackages\Queue\Services\ChunkedDispatcherService::class)) {
                $this->app->register(\MicroweberPackages\Queue\Providers\QueueServiceProvider::class);
                $this->app->register(\MicroweberPackages\Queue\Providers\QueueEventServiceProvider::class);
            }
        }
    }
}
