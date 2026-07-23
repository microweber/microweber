<?php

namespace MicroweberPackages\MediaPixum\Tests\Feature;

use MicroweberPackages\MediaPixum\PixumGenerator;
use MicroweberPackages\MediaPixum\Tests\TestCase;

class PixumRoutesTest extends TestCase
{
    protected function tearDown(): void
    {
        $cachePath = app(PixumGenerator::class)->getCachePath();
        if (is_dir($cachePath)) {
            $files = glob($cachePath . '/*.png');
            if ($files) {
                foreach ($files as $f) {
                    @unlink($f);
                }
            }
            @rmdir($cachePath);
        }
        parent::tearDown();
    }

    public function test_pixum_route_exists(): void
    {
        $response = $this->get(route('media-pixum.serve', ['width' => 10, 'height' => 10]));

        $response->assertStatus(200);
    }

    public function test_pixum_route_returns_png(): void
    {
        $response = $this->get(route('media-pixum.serve', ['width' => 50, 'height' => 30]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_pixum_route_returns_cached_response(): void
    {
        $response = $this->get(route('media-pixum.serve', ['width' => 25, 'height' => 25]));

        $response->assertStatus(200);
        $response->assertHeader('Cache-Control');
        $cacheControl = $response->headers->get('Cache-Control');
        $this->assertStringContainsString('max-age=31536000', $cacheControl);
    }

    public function test_pixum_route_default_dimensions(): void
    {
        $response = $this->get('/pixum_img');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_pixum_route_with_invalid_dimensions_returns_200(): void
    {
        $response = $this->get('/pixum_img?width=abc&height=xyz');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_pixum_route_with_zero_dimensions_returns_200(): void
    {
        $response = $this->get('/pixum_img?width=0&height=0');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_pixum_route_returns_valid_png_content(): void
    {
        $response = $this->get('/pixum_img?width=20&height=15');

        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertNotEmpty($content);

        // PNG magic bytes: \x89PNG
        $this->assertStringStartsWith("\x89PNG", $content);
    }

    public function test_pixum_does_not_use_exit(): void
    {
        // This test verifies no exit() is called — if it were,
        // PHPUnit would terminate here and the test would not pass.
        $response = $this->get('/pixum_img?width=5&height=5');
        $response->assertStatus(200);

        // If we reach here, exit() was NOT called
        $this->assertTrue(true);
    }
}