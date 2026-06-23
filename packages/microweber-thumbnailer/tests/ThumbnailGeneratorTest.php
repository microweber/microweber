<?php

namespace MicroweberPackages\Thumbnailer\Tests;

use MicroweberPackages\Thumbnailer\ThumbnailGenerator;

class ThumbnailGeneratorTest extends TestCase
{
    private string $fixtureDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixtureDir = sys_get_temp_dir() . '/thumbnailer-fixtures';
        if (!is_dir($this->fixtureDir)) {
            mkdir($this->fixtureDir, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (is_dir($this->fixtureDir)) {
            $this->recursiveDeleteDir($this->fixtureDir);
        }
        parent::tearDown();
    }

    private function recursiveDeleteDir(string $dir): void
    {
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->recursiveDeleteDir($path) : @unlink($path);
        }
        @rmdir($dir);
    }

    private function createTestImage(int $width = 400, int $height = 300, string $ext = 'jpg'): string
    {
        $img = imagecreatetruecolor($width, $height);
        $red = imagecolorallocate($img, 255, 0, 0);
        imagefill($img, 0, 0, $red);
        $path = $this->fixtureDir . '/test-image.' . $ext;

        switch ($ext) {
            case 'png':
                imagepng($img, $path);
                break;
            case 'gif':
                imagegif($img, $path);
                break;
            case 'webp':
                if (function_exists('imagewebp')) {
                    imagewebp($img, $path);
                } else {
                    imagejpeg($img, $path);
                    $path = str_replace('.webp', '.jpg', $path);
                }
                break;
            default:
                imagejpeg($img, $path, 90);
                break;
        }
        imagedestroy($img);

        return $path;
    }

    public function test_service_provider_registers_singleton(): void
    {
        $generator = app(ThumbnailGenerator::class);
        $this->assertInstanceOf(ThumbnailGenerator::class, $generator);
        $this->assertSame($generator, app(ThumbnailGenerator::class));
    }

    public function test_facade_works(): void
    {
        $generator = \MicroweberPackages\Thumbnailer\Facades\Thumbnailer::getFacadeRoot();
        $this->assertInstanceOf(ThumbnailGenerator::class, $generator);
    }

    public function test_generate_creates_thumbnail_jpg(): void
    {
        $src = $this->createTestImage(800, 600, 'jpg');
        $generator = app(ThumbnailGenerator::class);
        $result = $generator->generate($src, 200, 150);

        $this->assertNotNull($result);
        $this->assertFileExists($result);

        $size = getimagesize($result);
        $this->assertNotFalse($size);
        // Width should be 200 (may differ slightly due to aspect ratio)
        $this->assertLessThanOrEqual(200, $size[0]);
    }

    public function test_generate_creates_thumbnail_png(): void
    {
        $src = $this->createTestImage(600, 400, 'png');
        $generator = app(ThumbnailGenerator::class);
        $result = $generator->generate($src, 100, 100);

        $this->assertNotNull($result);
        $this->assertFileExists($result);
    }

    public function test_generate_caches_result(): void
    {
        $src = $this->createTestImage(400, 300, 'jpg');
        $generator = app(ThumbnailGenerator::class);

        $result1 = $generator->generate($src, 200, 150);
        $result2 = $generator->generate($src, 200, 150);

        $this->assertEquals($result1, $result2);
    }

    public function test_generate_returns_null_for_missing_file(): void
    {
        $generator = app(ThumbnailGenerator::class);
        $result = $generator->generate('/nonexistent/image.jpg', 200);

        $this->assertNull($result);
    }

    public function test_generate_returns_src_for_svg(): void
    {
        $svgPath = $this->fixtureDir . '/test.svg';
        file_put_contents($svgPath, '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><rect width="100" height="100" fill="red"/></svg>');

        $generator = app(ThumbnailGenerator::class);
        $result = $generator->generate($svgPath, 50);

        $this->assertEquals($svgPath, $result);
    }

    public function test_generate_returns_null_for_unsupported_ext(): void
    {
        $txtPath = $this->fixtureDir . '/test.txt';
        file_put_contents($txtPath, 'not an image');

        $generator = app(ThumbnailGenerator::class);
        $result = $generator->generate($txtPath, 100);

        $this->assertNull($result);
    }

    public function test_generate_with_crop(): void
    {
        $src = $this->createTestImage(800, 600, 'jpg');
        $generator = app(ThumbnailGenerator::class);
        $result = $generator->generate($src, 200, 200, true);

        $this->assertNotNull($result);
        $this->assertFileExists($result);
    }

    public function test_pixum_creates_placeholder(): void
    {
        $generator = app(ThumbnailGenerator::class);
        $result = $generator->pixum(100, 100);

        $this->assertFileExists($result);

        $size = getimagesize($result);
        $this->assertNotFalse($size);
        $this->assertEquals(100, $size[0]);
        $this->assertEquals(100, $size[1]);
    }

    public function test_pixum_caches_result(): void
    {
        $generator = app(ThumbnailGenerator::class);
        $result1 = $generator->pixum(50, 50);
        $result2 = $generator->pixum(50, 50);

        $this->assertEquals($result1, $result2);
    }

    public function test_pixum_uses_width_for_height_when_zero(): void
    {
        $generator = app(ThumbnailGenerator::class);
        $result = $generator->pixum(75, 0);

        $size = getimagesize($result);
        $this->assertEquals(75, $size[0]);
        $this->assertEquals(75, $size[1]);
    }

    public function test_build_cache_id_is_deterministic(): void
    {
        $generator = app(ThumbnailGenerator::class);
        $id1 = $generator->buildCacheId('/path/to/img.jpg', 200, 150, null, 'jpg');
        $id2 = $generator->buildCacheId('/path/to/img.jpg', 200, 150, null, 'jpg');

        $this->assertEquals($id1, $id2);
    }

    public function test_build_cache_id_differs_for_different_params(): void
    {
        $generator = app(ThumbnailGenerator::class);
        $id1 = $generator->buildCacheId('/path/to/img.jpg', 200, 150, null, 'jpg');
        $id2 = $generator->buildCacheId('/path/to/img.jpg', 300, 150, null, 'jpg');

        $this->assertNotEquals($id1, $id2);
    }

    public function test_helper_function_thumbnailer_generate(): void
    {
        $src = $this->createTestImage(400, 300, 'jpg');
        $result = thumbnailer_generate($src, 100, 100);

        $this->assertNotNull($result);
        $this->assertFileExists($result);
    }

    public function test_helper_function_thumbnailer_pixum(): void
    {
        $result = thumbnailer_pixum(50, 50);

        $this->assertFileExists($result);
        $size = getimagesize($result);
        $this->assertEquals(50, $size[0]);
        $this->assertEquals(50, $size[1]);
    }

    public function test_different_dimensions_produce_different_thumbnails(): void
    {
        $src = $this->createTestImage(800, 600, 'jpg');
        $generator = app(ThumbnailGenerator::class);

        $result1 = $generator->generate($src, 100, 100);
        $result2 = $generator->generate($src, 200, 200);

        $this->assertNotEquals($result1, $result2);
    }

    public function test_is_webp_supported_returns_bool(): void
    {
        $result = ThumbnailGenerator::isWebpSupported();
        $this->assertIsBool($result);
    }
}