<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Unit;

use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;
use MicroweberPackages\ImageOptimization\Tests\TestCase;

class ImageOptimizationServiceTest extends TestCase
{
    protected ImageOptimizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ImageOptimizationService([
            'webp_enabled' => true,
            'webp_quality' => 85,
            'lazy_loading_enabled' => true,
            'placeholder_url' => 'data:image/svg+xml,placeholder',
            'webp_cache' => true,
            'cache_path' => 'cache/webp',
            'disk' => 'public',
            'supported_formats' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'],
        ]);
    }

    public function test_can_instantiate_service(): void
    {
        $this->assertInstanceOf(ImageOptimizationService::class, $this->service);
    }

    public function test_service_has_expected_methods(): void
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
        $this->assertTrue(method_exists($this->service, 'convertToWebp'));
    }

    public function test_returns_original_path_for_invalid_image_files(): void
    {
        $this->assertEquals('file.pdf', $this->service->getWebpOrOriginal('file.pdf'));
        $this->assertEquals('file.txt', $this->service->getWebpOrOriginal('file.txt'));
        $this->assertEquals('file.docx', $this->service->getWebpOrOriginal('file.docx'));
    }

    public function test_returns_original_path_for_existing_webp_files(): void
    {
        $this->assertEquals('image.webp', $this->service->getWebpOrOriginal('image.webp'));
    }

    public function test_handles_various_image_extensions(): void
    {
        foreach (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'] as $ext) {
            $result = $this->service->getWebpOrOriginal("image.{$ext}");
            $this->assertIsString($result);
        }
    }

    public function test_can_generate_lazy_image_html(): void
    {
        $html = $this->service->generateLazyImage('/images/test.jpg', 'Test Image');

        $this->assertStringContainsString('<img', $html);
        $this->assertStringContainsString('data-src="/images/test.jpg"', $html);
        $this->assertStringContainsString('alt="Test Image"', $html);
        $this->assertStringContainsString('loading="lazy"', $html);
        $this->assertStringContainsString('decoding="async"', $html);
        $this->assertStringContainsString('class="mw-lazy-image"', $html);
    }

    public function test_includes_placeholder_in_lazy_image(): void
    {
        $html = $this->service->generateLazyImage('/images/test.jpg');
        $this->assertStringContainsString('src="data:image/svg+xml,placeholder"', $html);
        $this->assertStringContainsString('data-src="/images/test.jpg"', $html);
    }

    public function test_can_generate_lazy_image_with_custom_attributes(): void
    {
        $html = $this->service->generateLazyImage('/images/test.jpg', 'Test', [
            'width' => 800,
            'height' => 600,
            'class' => 'custom-class',
        ]);

        $this->assertStringContainsString('width="800"', $html);
        $this->assertStringContainsString('height="600"', $html);
        $this->assertStringContainsString('class="custom-class mw-lazy-image"', $html);
    }

    public function test_escapes_special_characters_in_lazy_image(): void
    {
        $html = $this->service->generateLazyImage('/images/test.jpg', 'Image with "quotes"');
        $this->assertStringContainsString('alt="Image with &quot;quotes&quot;"', $html);
    }

    public function test_returns_simple_lazy_image_when_sizes_empty(): void
    {
        $html = $this->service->generateResponsiveImage('/images/test.jpg', [], 'Test Image');
        $this->assertStringContainsString('<img', $html);
        $this->assertStringContainsString('data-src="/images/test.jpg"', $html);
        $this->assertStringNotContainsString('srcset="', $html);
    }

    public function test_generate_responsive_image_with_sizes(): void
    {
        $html = $this->service->generateResponsiveImage('/images/test.jpg', [
            320 => 320,
            640 => 640,
        ], 'Responsive');

        $this->assertStringContainsString('srcset="', $html);
        $this->assertStringContainsString('320w', $html);
        $this->assertStringContainsString('640w', $html);
        $this->assertStringContainsString('sizes="', $html);
        $this->assertStringContainsString('alt="Responsive"', $html);
    }

    public function test_returns_empty_statistics_for_nonexistent_cache(): void
    {
        $stats = $this->service->getStatistics();
        $this->assertArrayHasKey('total_files', $stats);
        $this->assertArrayHasKey('total_size', $stats);
        $this->assertArrayHasKey('total_size_human', $stats);
        $this->assertArrayHasKey('enabled', $stats);
        $this->assertArrayHasKey('supported', $stats);
        $this->assertArrayHasKey('cache_path', $stats);
    }

    public function test_returns_zero_when_clearing_nonexistent_cache(): void
    {
        // Use unique cache path that does not exist
        $service = new ImageOptimizationService([
            'cache_path' => 'cache/webp-nonexistent-' . uniqid(),
            'webp_enabled' => true,
        ]);
        $this->assertEquals(0, $service->clearWebpCache());
    }

    public function test_webp_enabled_flag(): void
    {
        $this->assertTrue($this->service->isWebpEnabled());

        $disabled = new ImageOptimizationService(['webp_enabled' => false]);
        $this->assertFalse($disabled->isWebpEnabled());
    }

    public function test_lazy_loading_enabled_flag(): void
    {
        $this->assertTrue($this->service->isLazyLoadingEnabled());

        $disabled = new ImageOptimizationService(['lazy_loading_enabled' => false]);
        $this->assertFalse($disabled->isLazyLoadingEnabled());
    }

    public function test_convert_to_webp_with_real_image(): void
    {
        if (!$this->service->isWebpSupported()) {
            $this->markTestSkipped('WebP not supported on this PHP build');
        }

        $dir = storage_path('app/public/image-opt-test');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $src = $dir . '/test-convert.png';
        $img = imagecreatetruecolor(50, 40);
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $white);
        imagepng($img, $src);
        imagedestroy($img);

        $result = $this->service->convertToWebp($src, ['quality' => 80]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('path', $result);
        $this->assertArrayHasKey('full_path', $result);
        $this->assertArrayHasKey('webp_size', $result);
        $this->assertFileExists((string) $result['full_path']);
        $this->assertEquals(50, $result['width']);
        $this->assertEquals(40, $result['height']);

        // Clean up
        @unlink((string) $result['full_path']);
        @unlink($src);
    }

    public function test_get_optimized_url_without_webp(): void
    {
        $url = $this->service->getOptimizedUrl('/images/photo.jpg', null, null, false);
        $this->assertEquals('/images/photo.jpg', $url);
    }

    public function test_resolve_full_path_for_existing_file(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'imgopt');
        file_put_contents($tmp, 'x');
        $this->assertEquals($tmp, $this->service->resolveFullPath($tmp));
        @unlink($tmp);
    }

    public function test_container_resolves_singleton(): void
    {
        $a = app(ImageOptimizationService::class);
        $b = app(ImageOptimizationService::class);
        $this->assertSame($a, $b);
        $this->assertSame($a, app('image-optimization'));
    }
}
