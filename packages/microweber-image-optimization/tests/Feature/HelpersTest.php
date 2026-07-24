<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Feature;

use MicroweberPackages\ImageOptimization\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_helper_functions_exist(): void
    {
        $this->assertTrue(function_exists('optimized_image_url'));
        $this->assertTrue(function_exists('webp_image'));
        $this->assertTrue(function_exists('lazy_image'));
        $this->assertTrue(function_exists('responsive_image'));
        $this->assertTrue(function_exists('webp_supported'));
        $this->assertTrue(function_exists('webp_enabled'));
        $this->assertTrue(function_exists('lazy_loading_enabled'));
        $this->assertTrue(function_exists('client_supports_webp'));
        $this->assertTrue(function_exists('clear_webp_cache'));
        $this->assertTrue(function_exists('image_optimization_stats'));
    }

    public function test_optimized_image_url_helper(): void
    {
        $url = optimized_image_url('/images/a.jpg', null, null, false);
        $this->assertEquals('/images/a.jpg', $url);
    }

    public function test_lazy_image_helper(): void
    {
        $html = lazy_image('/images/b.jpg', 'Alt');
        $this->assertStringContainsString('data-src="/images/b.jpg"', $html);
        $this->assertStringContainsString('mw-lazy-image', $html);
    }

    public function test_responsive_image_helper(): void
    {
        $html = responsive_image('/images/c.jpg', [100 => 100], 'C');
        $this->assertStringContainsString('srcset="', $html);
    }

    public function test_stats_helper(): void
    {
        $stats = image_optimization_stats();
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_files', $stats);
    }

    public function test_webp_supported_helper(): void
    {
        $this->assertIsBool(webp_supported());
    }

    public function test_webp_enabled_helper(): void
    {
        $this->assertIsBool(webp_enabled());
    }
}
