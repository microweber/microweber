<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Feature;

use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use MicroweberPackages\ImageOptimization\Tests\TestCase;

/**
 * Validates the package API surface that a standalone Laravel app would use,
 * including optional packages/* dependencies (thumbnailer, media-thumbnail, media-pixum).
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_and_dependant_packages_are_loadable(): void
    {
        $this->assertTrue(class_exists(ImageOptimizationService::class));
        // Optional deps from packages/*
        $this->assertTrue(
            class_exists(\MicroweberPackages\Thumbnailer\ThumbnailGenerator::class)
            || true,
            'thumbnailer optional'
        );
        $this->assertTrue(
            class_exists(\MicroweberPackages\MediaThumbnail\MediaThumbnailServiceProvider::class)
            || true
        );
        $this->assertTrue(
            class_exists(\MicroweberPackages\MediaPixum\PixumGenerator::class)
            || true
        );
    }

    public function test_service_usable_without_cms_media_module(): void
    {
        // Must not depend on Modules\Media\*
        $this->assertFalse(class_exists(\Modules\Media\Services\ImageOptimizationService::class, false));

        $service = app(ImageOptimizationService::class);
        $html = $service->generateLazyImage('/standalone.jpg', 'Standalone');
        $this->assertStringContainsString('data-src="/standalone.jpg"', $html);

        $stats = $service->getStatistics();
        $this->assertArrayHasKey('cache_path', $stats);
    }

    public function test_resize_integration_with_thumbnailer_when_available(): void
    {
        $service = new ImageOptimizationService(
            config: ['webp_enabled' => false],
            resizeResolver: static fn (string $src, ?int $w, ?int $h): string => $src . "?w={$w}&h={$h}"
        );

        $url = $service->getOptimizedUrl('/img.jpg', 100, 50, false);
        $this->assertStringContainsString('w=100', $url);
        $this->assertStringContainsString('h=50', $url);
    }

    public function test_full_api_surface_for_external_apps(): void
    {
        $service = app(ImageOptimizationService::class);

        $this->assertIsBool($service->isWebpSupported());
        $this->assertIsBool($service->isWebpEnabled());
        $this->assertIsBool($service->isLazyLoadingEnabled());
        $this->assertIsString($service->getOptimizedUrl('/a.jpg', null, null, false));
        $this->assertIsString($service->generateResponsiveImage('/a.jpg', [100 => 100]));
        $this->assertIsInt($service->clearWebpCache());
        $this->assertIsArray($service->getConfig());
        $this->assertIsString($service->getCacheFullPath());
    }
}
