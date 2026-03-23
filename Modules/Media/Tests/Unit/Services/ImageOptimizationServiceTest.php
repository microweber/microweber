<?php

namespace Modules\Media\Tests\Unit\Services;

use Modules\Media\Services\ImageOptimizationService;
use Tests\TestCase;

class ImageOptimizationServiceTest extends TestCase
{
    protected ImageOptimizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ImageOptimizationService();
    }

    public function test_can_instantiate_service()
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
        
        $result = $this->service->getWebpOrOriginal('file.docx');
        $this->assertEquals('file.docx', $result);
    }

    public function test_returns_original_path_for_existing_webp_files()
    {
        $result = $this->service->getWebpOrOriginal('image.webp');
        $this->assertEquals('image.webp', $result);
    }

    public function test_handles_various_image_extensions()
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'];
        
        foreach ($extensions as $ext) {
            $path = "image.{$ext}";
            $result = $this->service->getWebpOrOriginal($path);
            
            // Should return string path
            $this->assertIsString($result);
        }
    }

    public function test_can_generate_lazy_image_html()
    {
        $html = $this->service->generateLazyImage('/images/test.jpg', 'Test Image');
        
        $this->assertStringContainsString('<img', $html);
        $this->assertStringContainsString('data-src="/images/test.jpg"', $html);
        $this->assertStringContainsString('alt="Test Image"', $html);
        $this->assertStringContainsString('loading="lazy"', $html);
        $this->assertStringContainsString('decoding="async"', $html);
        $this->assertStringContainsString('class="mw-lazy-image"', $html);
    }

    public function test_includes_placeholder_in_lazy_image()
    {
        $html = $this->service->generateLazyImage('/images/test.jpg');
        
        $this->assertStringContainsString('src="', $html);
        $this->assertStringContainsString('data-src="/images/test.jpg"', $html);
    }

    public function test_can_generate_lazy_image_with_custom_attributes()
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

    public function test_escapes_special_characters_in_lazy_image()
    {
        $html = $this->service->generateLazyImage('/images/test.jpg', 'Image with "quotes"');
        
        $this->assertStringContainsString('alt="Image with &quot;quotes&quot;"', $html);
    }

    public function test_returns_simple_lazy_image_when_sizes_empty()
    {
        $html = $this->service->generateResponsiveImage('/images/test.jpg', [], 'Test Image');
        
        $this->assertStringContainsString('<img', $html);
        $this->assertStringContainsString('data-src="/images/test.jpg"', $html);
        $this->assertStringNotContainsString('srcset="', $html);
    }

    public function test_returns_empty_statistics_for_nonexistent_cache()
    {
        $stats = $this->service->getStatistics();
        
        $this->assertEquals(0, $stats['total_files']);
        $this->assertEquals(0, $stats['total_size']);
        $this->assertEquals('0 B', $stats['total_size_human']);
    }

    public function test_returns_zero_when_clearing_nonexistent_cache()
    {
        $count = $this->service->clearWebpCache();
        $this->assertEquals(0, $count);
    }

    public function test_prevents_duplicate_webp_extension()
    {
        $result = $this->service->getWebpOrOriginal('image.webp');
        $this->assertEquals('image.webp', $result);
    }
}
