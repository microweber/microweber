<?php

declare(strict_types=1);

namespace ImageOptimizationStandalone\Tests;

use MicroweberPackages\ImageOptimization\ImageOptimizationServiceProvider;
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use Orchestra\Testbench\TestCase;

/**
 * Boots a bare Laravel app with only the image-optimization package
 * (and optional media-thumbnail / pixum / thumbnailer deps) to prove
 * the package works outside the Microweber CMS.
 */
class StandalonePackageTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        $providers = [
            ImageOptimizationServiceProvider::class,
        ];

        if (class_exists(\MicroweberPackages\MediaPixum\MediaPixumServiceProvider::class)) {
            $providers[] = \MicroweberPackages\MediaPixum\MediaPixumServiceProvider::class;
        }
        if (class_exists(\MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider::class)) {
            $providers[] = \MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider::class;
        }
        if (class_exists(\MicroweberPackages\MediaThumbnail\MediaThumbnailServiceProvider::class)) {
            $providers[] = \MicroweberPackages\MediaThumbnail\MediaThumbnailServiceProvider::class;
        }

        return $providers;
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => '/storage',
            'visibility' => 'public',
        ]);
    }

    public function test_service_resolves(): void
    {
        $service = $this->app->make(ImageOptimizationService::class);
        $this->assertInstanceOf(ImageOptimizationService::class, $service);
    }

    public function test_helpers_work(): void
    {
        $this->assertTrue(function_exists('lazy_image'));
        $html = lazy_image('/x.jpg', 'X');
        $this->assertStringContainsString('mw-lazy-image', $html);
    }

    public function test_routes_registered(): void
    {
        $this->assertTrue($this->app->make('router')->has('image-optimization.stats'));
        $response = $this->get('/image-optimization/stats');
        $response->assertOk();
        $response->assertJsonStructure(['total_files', 'enabled', 'supported']);
    }

    public function test_webp_conversion_standalone(): void
    {
        if (!function_exists('imagewebp')) {
            $this->markTestSkipped('WebP not supported');
        }

        $dir = storage_path('app/public/standalone-opt');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $src = $dir . '/s.png';
        $img = imagecreatetruecolor(10, 10);
        imagepng($img, $src);
        imagedestroy($img);

        /** @var ImageOptimizationService $service */
        $service = $this->app->make(ImageOptimizationService::class);
        $result = $service->convertToWebp($src);
        $this->assertIsArray($result);
        $this->assertFileExists((string) $result['full_path']);
        @unlink((string) $result['full_path']);
        @unlink($src);
    }

    public function test_sqlite_driver(): void
    {
        $this->assertEquals('sqlite', config('database.connections.testing.driver'));
        $this->assertInstanceOf(ImageOptimizationService::class, app(ImageOptimizationService::class));
    }
}
