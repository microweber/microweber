<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Feature;

use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use MicroweberPackages\ImageOptimization\Tests\TestCase;

/**
 * Tests that the package boots and operates correctly on SQLite, MySQL, and PostgreSQL.
 *
 * Driver selected by MW_TEST_DB_DRIVER env var (default: sqlite).
 */
class MultiDatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $driver = env('MW_TEST_DB_DRIVER', 'sqlite');

        if ($driver === 'mysql') {
            config([
                'database.default' => 'mysql',
                'database.connections.mysql' => [
                    'driver' => 'mysql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '3306'),
                    'database' => env('DB_DATABASE', 'image_optimization_test'),
                    'username' => env('DB_USERNAME', 'root'),
                    'password' => env('DB_PASSWORD', 'root'),
                    'charset' => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix' => '',
                ],
            ]);
        } elseif ($driver === 'pgsql') {
            config([
                'database.default' => 'pgsql',
                'database.connections.pgsql' => [
                    'driver' => 'pgsql',
                    'host' => env('DB_HOST', '127.0.0.1'),
                    'port' => env('DB_PORT', '5432'),
                    'database' => env('DB_DATABASE', 'postgres'),
                    'username' => env('DB_USERNAME', 'postgres'),
                    'password' => env('DB_PASSWORD', 'postgres'),
                    'charset' => 'utf8',
                    'prefix' => '',
                    'schema' => 'public',
                ],
            ]);
        } else {
            config([
                'database.default' => 'testing',
                'database.connections.testing' => [
                    'driver' => 'sqlite',
                    'database' => ':memory:',
                    'prefix' => '',
                ],
            ]);
        }
    }

    public function test_service_resolves_on_current_driver(): void
    {
        $service = app(ImageOptimizationService::class);
        $this->assertInstanceOf(ImageOptimizationService::class, $service);
    }

    public function test_stats_and_clear_on_current_driver(): void
    {
        $service = app(ImageOptimizationService::class);
        $stats = $service->getStatistics();
        $this->assertIsArray($stats);
        $this->assertIsInt($service->clearWebpCache());
    }

    public function test_routes_on_current_driver(): void
    {
        $response = $this->get(route('image-optimization.stats'));
        $response->assertStatus(200);
    }

    public function test_lazy_html_on_current_driver(): void
    {
        $html = app(ImageOptimizationService::class)->generateLazyImage('/x.jpg', 'x');
        $this->assertStringContainsString('mw-lazy-image', $html);
    }
}
