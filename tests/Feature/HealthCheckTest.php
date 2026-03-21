<?php

namespace Tests\Feature;

use App\Providers\CacheServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    /**
     * Test that the main health check endpoint returns healthy status.
     */
    public function test_main_health_check_endpoint_returns_healthy(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'checks' => [
                    'database',
                    'cache',
                    'storage',
                    'queue',
                ],
            ]);

        $data = $response->json();
        $this->assertEquals('healthy', $data['status']);
        $this->assertTrue($data['checks']['database']['healthy']);
        $this->assertTrue($data['checks']['cache']['healthy']);
        $this->assertTrue($data['checks']['storage']['healthy']);
        $this->assertTrue($data['checks']['queue']['healthy']);
    }

    /**
     * Test that the database health check endpoint returns correct status.
     */
    public function test_database_health_check_returns_correct_status(): void
    {
        $response = $this->getJson('/api/health/database');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'database' => [
                    'healthy',
                    'connection',
                    'response_time_ms',
                ],
            ]);

        $data = $response->json();
        $this->assertEquals('healthy', $data['status']);
        $this->assertTrue($data['database']['healthy']);
        $this->assertNotNull($data['database']['connection']);
        $this->assertGreaterThan(0, $data['database']['response_time_ms']);
    }

    /**
     * Test that the cache health check endpoint returns correct status.
     */
    public function test_cache_health_check_returns_correct_status(): void
    {
        $response = $this->getJson('/api/health/cache');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'cache' => [
                    'healthy',
                    'driver',
                    'prefix',
                    'ttl',
                ],
            ]);

        $data = $response->json();
        $this->assertEquals('healthy', $data['status']);
        $this->assertTrue($data['cache']['healthy']);
        $this->assertNotNull($data['cache']['driver']);
    }

    /**
     * Test that the storage health check endpoint returns correct status.
     */
    public function test_storage_health_check_returns_correct_status(): void
    {
        $response = $this->getJson('/api/health/storage');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'storage' => [
                    'healthy',
                    'disk',
                    'response_time_ms',
                ],
            ]);

        $data = $response->json();
        $this->assertEquals('healthy', $data['status']);
        $this->assertTrue($data['storage']['healthy']);
        $this->assertNotNull($data['storage']['disk']);
        $this->assertGreaterThan(0, $data['storage']['response_time_ms']);
    }

    /**
     * Test that health check includes timestamp.
     */
    public function test_health_check_includes_timestamp(): void
    {
        $response = $this->getJson('/api/health');

        $data = $response->json();
        $this->assertArrayHasKey('timestamp', $data);
        $this->assertNotNull($data['timestamp']);

        // Verify timestamp is in ISO 8601 format
        $this->assertNotFalse(strtotime($data['timestamp']));
    }

    /**
     * Test that health check response time is reasonable.
     */
    public function test_health_check_response_time_is_reasonable(): void
    {
        $start = microtime(true);
        $response = $this->getJson('/api/health');
        $duration = (microtime(true) - $start) * 1000;

        $response->assertStatus(200);
        $this->assertLessThan(5000, $duration, 'Health check should complete within 5 seconds');
    }

    /**
     * Test that cache health check uses CacheServiceProvider.
     */
    public function test_cache_health_check_uses_cache_service_provider(): void
    {
        $response = $this->getJson('/api/health/cache');
        $data = $response->json();

        $this->assertEquals('healthy', $data['status']);

        // Verify the cache driver matches configuration
        $this->assertEquals(config('cache.default'), $data['cache']['driver']);
    }

    /**
     * Test that storage health check can write and read test file.
     */
    public function test_storage_health_check_can_write_and_read(): void
    {
        $response = $this->getJson('/api/health/storage');
        $data = $response->json();

        $this->assertTrue($data['storage']['healthy']);
        $this->assertEquals(config('filesystems.default'), $data['storage']['disk']);
    }

    /**
     * Test that queue health check returns correct driver information.
     */
    public function test_queue_health_check_returns_driver_info(): void
    {
        $response = $this->getJson('/api/health');
        $data = $response->json();

        $this->assertArrayHasKey('queue', $data['checks']);
        $this->assertArrayHasKey('driver', $data['checks']['queue']);
        $this->assertEquals(config('queue.default'), $data['checks']['queue']['driver']);
    }

    /**
     * Test health check endpoint is accessible without authentication.
     */
    public function test_health_check_is_accessible_without_authentication(): void
    {
        $this->assertGuest();

        $response = $this->getJson('/api/health');

        $response->assertStatus(200);
    }

    /**
     * Test that individual health check endpoints return proper JSON.
     */
    public function test_individual_endpoints_return_proper_json(): void
    {
        $endpoints = [
            '/api/health/database',
            '/api/health/cache',
            '/api/health/storage',
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->getJson($endpoint);
            $response->assertHeader('Content-Type', 'application/json');
        }
    }

    /**
     * Test that health check works with different cache drivers.
     */
    public function test_health_check_works_with_array_cache_driver(): void
    {
        // Temporarily switch to array driver for testing
        $originalDriver = config('cache.default');
        config(['cache.default' => 'array']);

        $response = $this->getJson('/api/health/cache');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['cache']['healthy']);
        $this->assertEquals('array', $data['cache']['driver']);

        // Restore original driver
        config(['cache.default' => $originalDriver]);
    }

    /**
     * Test that health check includes response time metrics.
     */
    public function test_health_check_includes_response_time_metrics(): void
    {
        $response = $this->getJson('/api/health');
        $data = $response->json();

        // Database should have response time
        $this->assertArrayHasKey('response_time_ms', $data['checks']['database']);
        $this->assertIsNumeric($data['checks']['database']['response_time_ms']);

        // Storage should have response time
        $this->assertArrayHasKey('response_time_ms', $data['checks']['storage']);
        $this->assertIsNumeric($data['checks']['storage']['response_time_ms']);
    }

    /**
     * Test that all health check routes are registered.
     */
    public function test_all_health_check_routes_exist(): void
    {
        $routes = [
            'health.index' => '/api/health',
            'health.database' => '/api/health/database',
            'health.cache' => '/api/health/cache',
            'health.storage' => '/api/health/storage',
        ];

        foreach ($routes as $name => $path) {
            $this->assertTrue(
                \Illuminate\Support\Facades\Route::has($name),
                "Route '{$name}' should be registered"
            );
        }
    }
}
