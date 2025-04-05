<?php

namespace Modules\Billing\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Billing\Providers\BillingServiceProvider;

class BillingTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Use temporary SQLite database file
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => tempnam(sys_get_temp_dir(), 'testdb')]);
        
        // Run migrations
        $this->artisan('migrate:fresh', [
            '--path' => 'database/migrations',
            '--realpath' => true
        ]);
        
        $this->app->register(BillingServiceProvider::class);
    }

    protected function tearDown(): void
    {
        // Clean up database file
        if (file_exists(config('database.connections.sqlite.database'))) {
            unlink(config('database.connections.sqlite.database'));
        }
        parent::tearDown();
    }
}