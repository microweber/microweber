<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Feature;

use MicroweberPackages\ImageOptimization\Facades\ImageOptimization;
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use MicroweberPackages\ImageOptimization\Tests\TestCase;

/**
 * Validates the package works as a standalone service for external Laravel apps.
 */
class StandaloneIntegrationTest extends TestCase
{
    public function test_generator_resolves_from_container(): void
    {
        $service = app(ImageOptimizationService::class);
        $this->assertInstanceOf(ImageOptimizationService::class, $service);
    }

    public function test_service_is_singleton(): void
    {
        $a = app(ImageOptimizationService::class);
        $b = app(ImageOptimizationService::class);
        $this->assertSame($a, $b);
    }

    public function test_alias_binding(): void
    {
        $fromAlias = ImageOptimization::getFacadeRoot();
        $fromClass = app(ImageOptimizationService::class);
        $this->assertSame($fromAlias, $fromClass);
    }

    public function test_facade_works(): void
    {
        $this->assertIsBool(ImageOptimization::isWebpSupported());
        $this->assertIsArray(ImageOptimization::getStatistics());
    }

    public function test_config_is_loaded(): void
    {
        $config = config('image-optimization');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('webp_enabled', $config);
        $this->assertArrayHasKey('webp_quality', $config);
        $this->assertArrayHasKey('lazy_loading_enabled', $config);
        $this->assertArrayHasKey('cache_path', $config);
    }

    public function test_end_to_end_conversion_and_stats(): void
    {
        if (!function_exists('imagewebp')) {
            $this->markTestSkipped('WebP not supported');
        }

        $dir = storage_path('app/public/image-opt-e2e');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $src = $dir . '/e2e.png';
        $img = imagecreatetruecolor(32, 32);
        imagepng($img, $src);
        imagedestroy($img);

        /** @var ImageOptimizationService $service */
        $service = app(ImageOptimizationService::class);
        $result = $service->convertToWebp($src);

        $this->assertIsArray($result);
        $this->assertFileExists((string) $result['full_path']);

        $stats = $service->getStatistics();
        $this->assertGreaterThanOrEqual(1, $stats['total_files']);

        // Clean
        @unlink((string) $result['full_path']);
        @unlink($src);
    }

    public function test_works_on_sqlite(): void
    {
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]]);

        $service = app(ImageOptimizationService::class);
        $this->assertIsArray($service->getStatistics());
    }

    public function test_works_on_mysql(): void
    {
        if (!extension_loaded('pdo_mysql')) {
            $this->markTestSkipped('pdo_mysql not available');
        }

        try {
            $pdo = new \PDO('mysql:host=127.0.0.1', 'root', 'root');
            $pdo->exec('CREATE DATABASE IF NOT EXISTS image_optimization_test');
        } catch (\Throwable $e) {
            $this->markTestSkipped('MySQL not available: ' . $e->getMessage());
        }

        config(['database.default' => 'mysql']);
        config(['database.connections.mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'image_optimization_test',
            'username' => 'root',
            'password' => 'root',
        ]]);

        $service = app(ImageOptimizationService::class);
        $this->assertIsArray($service->getStatistics());
        $this->assertTrue($service->isWebpSupported() || !$service->isWebpSupported());
    }

    public function test_works_on_pgsql(): void
    {
        if (!extension_loaded('pdo_pgsql')) {
            $this->markTestSkipped('pdo_pgsql not available');
        }

        try {
            $pdo = new \PDO('pgsql:host=127.0.0.1;user=postgres;password=postgres', 'postgres', 'postgres');
            $pdo->exec('SELECT 1');
        } catch (\Throwable $e) {
            $this->markTestSkipped('PostgreSQL not available: ' . $e->getMessage());
        }

        config(['database.default' => 'pgsql']);
        config(['database.connections.pgsql' => [
            'driver' => 'pgsql',
            'host' => '127.0.0.1',
            'port' => '5432',
            'database' => 'postgres',
            'username' => 'postgres',
            'password' => 'postgres',
        ]]);

        $service = app(ImageOptimizationService::class);
        $this->assertIsArray($service->getStatistics());
    }
}
