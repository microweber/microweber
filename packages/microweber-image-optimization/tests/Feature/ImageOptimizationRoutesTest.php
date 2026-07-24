<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Tests\Feature;

use MicroweberPackages\ImageOptimization\Tests\TestCase;

class ImageOptimizationRoutesTest extends TestCase
{
    public function test_route_names_exist(): void
    {
        $this->assertTrue(app('router')->has('image-optimization.webp'));
        $this->assertTrue(app('router')->has('image-optimization.stats'));
        $this->assertTrue(app('router')->has('image-optimization.clear-cache'));
        $this->assertTrue(app('router')->has('image-optimization.convert'));
    }

    public function test_stats_route_returns_json(): void
    {
        $response = $this->get(route('image-optimization.stats'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_files',
            'total_size',
            'total_size_human',
            'enabled',
            'supported',
        ]);
    }

    public function test_stats_route_via_path(): void
    {
        $response = $this->get('/image-optimization/stats');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
    }

    public function test_webp_route_requires_src(): void
    {
        $response = $this->get(route('image-optimization.webp'));
        $response->assertStatus(422);
        $response->assertJsonFragment(['error' => 'Missing src parameter']);
    }

    public function test_webp_route_rejects_path_traversal(): void
    {
        $response = $this->get(route('image-optimization.webp', ['src' => '../etc/passwd']));
        $response->assertStatus(400);
    }

    public function test_convert_api_requires_src(): void
    {
        $response = $this->get(route('image-optimization.convert'));
        $response->assertStatus(422);
    }

    public function test_convert_api_rejects_path_traversal(): void
    {
        $response = $this->get(route('image-optimization.convert', ['src' => '../../secret.jpg']));
        $response->assertStatus(400);
    }

    public function test_convert_api_with_real_image(): void
    {
        if (!function_exists('imagewebp')) {
            $this->markTestSkipped('WebP not supported');
        }

        $dir = storage_path('app/public/image-opt-route-test');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $src = $dir . '/route-test.png';
        $img = imagecreatetruecolor(20, 20);
        $c = imagecolorallocate($img, 100, 150, 200);
        imagefill($img, 0, 0, $c);
        imagepng($img, $src);
        imagedestroy($img);

        $response = $this->get(route('image-optimization.convert', ['src' => $src]));

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure(['success', 'data' => ['path', 'full_path', 'webp_size']]);

        $fullPath = $response->json('data.full_path');
        if (is_string($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
        @unlink($src);
    }

    public function test_webp_serve_with_real_image(): void
    {
        if (!function_exists('imagewebp')) {
            $this->markTestSkipped('WebP not supported');
        }

        $dir = storage_path('app/public/image-opt-serve-test');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $src = $dir . '/serve-test.png';
        $img = imagecreatetruecolor(16, 16);
        $c = imagecolorallocate($img, 10, 20, 30);
        imagefill($img, 0, 0, $c);
        imagepng($img, $src);
        imagedestroy($img);

        $response = $this->get(route('image-optimization.webp', ['src' => $src]));

        $response->assertStatus(200);
        $contentType = $response->headers->get('Content-Type');
        $this->assertTrue(
            str_contains((string) $contentType, 'image/webp') || str_contains((string) $contentType, 'image/png'),
            'Expected image content type, got: ' . $contentType
        );

        @unlink($src);
    }

    public function test_clear_cache_route(): void
    {
        $response = $this->post(route('image-optimization.clear-cache'));
        // May be 200 or 403 depending on is_admin()
        $this->assertContains($response->status(), [200, 403]);
    }
}
