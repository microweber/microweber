<?php

namespace Modules\Media\Tests\Unit\Services;

use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use Tests\TestCase;

/**
 * CMS integration tests for the standalone image-optimization package.
 * Full package coverage lives in packages/microweber-image-optimization/tests.
 */
class ImageOptimizationServiceTest extends TestCase
{
    protected ImageOptimizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ImageOptimizationService::class);
    }

    public function test_can_resolve_service_from_container()
    {
        $this->assertInstanceOf(ImageOptimizationService::class, $this->service);
    }

    public function test_service_has_expected_methods()
    {
        $this->assertTrue(method_exists($this->service, 'isWebpSupported'));
        $this->assertTrue(method_exists($this->service, 'isWebpEnabled'));
        $this->assertTrue(method_exists($this->service, 'isLazyLoadingEnabled'));
        $this->assertTrue(method_exists($this->service, 'getWebpOrOriginal'));
        $this->assertTrue(method_exists($this->service, 'getOptimizedUrl'));
        $this->assertTrue(method_exists($this->service, 'generateLazyImage'));
        $this->assertTrue(method_exists($this->service, 'generateResponsiveImage'));
        $this->assertTrue(method_exists($this->service, 'getStatistics'));
        $this->assertTrue(method_exists($this->service, 'clearWebpCache'));
        $this->assertTrue(method_exists($this->service, 'clientSupportsWebp'));
    }

    public function test_returns_original_path_for_invalid_image_files()
    {
        $result = $this->service->getWebpOrOriginal('file.pdf');
        $this->assertEquals('file.pdf', $result);

        $result = $this->service->getWebpOrOriginal('file.txt');
        $this->assertEquals('file.txt', $result);
    }

    public function test_returns_original_path_for_existing_webp_files()
    {
        $result = $this->service->getWebpOrOriginal('image.webp');
        $this->assertEquals('image.webp', $result);
    }

    public function test_can_generate_lazy_image_html()
    {
        $html = $this->service->generateLazyImage('/images/test.jpg', 'Test Image');

        $this->assertStringContainsString('<img', $html);
        $this->assertStringContainsString('data-src="/images/test.jpg"', $html);
        $this->assertStringContainsString('alt="Test Image"', $html);
        $this->assertStringContainsString('loading="lazy"', $html);
        $this->assertStringContainsString('class="mw-lazy-image"', $html);
    }

    public function test_helpers_are_available()
    {
        $this->assertTrue(function_exists('optimized_image_url'));
        $this->assertTrue(function_exists('lazy_image'));
        $this->assertTrue(function_exists('image_optimization_stats'));
    }
}
