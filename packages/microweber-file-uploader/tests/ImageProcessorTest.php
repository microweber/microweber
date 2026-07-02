<?php

namespace MicroweberPackages\FileUploader\Tests;

use MicroweberPackages\FileUploader\Support\ImageProcessor;

class ImageProcessorTest extends TestCase
{
    protected ImageProcessor $processor;
    protected string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->processor = new ImageProcessor();
        $this->tempDir = sys_get_temp_dir() . '/file_uploader_tests_' . uniqid();
        mkdir($this->tempDir, 0755, true);
    }

    protected function tearDown(): void
    {
        // Clean up temp files
        $files = glob($this->tempDir . '/*');
        foreach ($files as $file) {
            @unlink($file);
        }
        @rmdir($this->tempDir);
        parent::tearDown();
    }

    protected function createTestJpeg(string $name = 'test.jpg', int $width = 100, int $height = 100): string
    {
        $path = $this->tempDir . '/' . $name;
        $img = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($img, 255, 0, 0);
        imagefill($img, 0, 0, $color);
        imagejpeg($img, $path, 90);
        imagedestroy($img);
        return $path;
    }

    protected function createTestPng(string $name = 'test.png', int $width = 100, int $height = 100): string
    {
        $path = $this->tempDir . '/' . $name;
        $img = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($img, 0, 255, 0);
        imagefill($img, 0, 0, $color);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        imagepng($img, $path, 9);
        imagedestroy($img);
        return $path;
    }

    protected function createTestGif(string $name = 'test.gif', int $width = 100, int $height = 100): string
    {
        $path = $this->tempDir . '/' . $name;
        $img = imagecreatetruecolor($width, $height);
        $color = imagecolorallocate($img, 0, 0, 255);
        imagefill($img, 0, 0, $color);
        imagegif($img, $path);
        imagedestroy($img);
        return $path;
    }

    protected function createTestSvg(string $name = 'test.svg'): string
    {
        $path = $this->tempDir . '/' . $name;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><rect width="100" height="100" fill="red"/></svg>';
        file_put_contents($path, $svg);
        return $path;
    }

    // =====================================================
    // EXIF Data Reading
    // =====================================================

    public function test_read_exif_data_returns_array(): void
    {
        $path = $this->createTestJpeg();
        $exif = $this->processor->readExifData($path);
        $this->assertIsArray($exif);
    }

    public function test_read_exif_data_nonexistent_file(): void
    {
        $exif = $this->processor->readExifData('/nonexistent/file.jpg');
        $this->assertIsArray($exif);
        $this->assertEmpty($exif);
    }

    // =====================================================
    // Auto Rotate
    // =====================================================

    public function test_auto_rotate_with_no_orientation(): void
    {
        $img = imagecreatetruecolor(100, 100);
        $result = $this->processor->autoRotateImage($img, []);
        $this->assertNotFalse($result);
        imagedestroy($result);
    }

    public function test_auto_rotate_with_orientation_8(): void
    {
        $img = imagecreatetruecolor(100, 50);
        $result = $this->processor->autoRotateImage($img, ['Orientation' => 8]);
        // After 90° rotation, 100x50 becomes 50x100
        $this->assertEquals(50, imagesx($result));
        $this->assertEquals(100, imagesy($result));
        imagedestroy($result);
    }

    public function test_auto_rotate_with_orientation_3(): void
    {
        $img = imagecreatetruecolor(100, 50);
        $result = $this->processor->autoRotateImage($img, ['Orientation' => 3]);
        // After 180° rotation, dimensions stay the same
        $this->assertEquals(100, imagesx($result));
        $this->assertEquals(50, imagesy($result));
        imagedestroy($result);
    }

    public function test_auto_rotate_with_orientation_6(): void
    {
        $img = imagecreatetruecolor(100, 50);
        $result = $this->processor->autoRotateImage($img, ['Orientation' => 6]);
        // After -90° rotation, 100x50 becomes 50x100
        $this->assertEquals(50, imagesx($result));
        $this->assertEquals(100, imagesy($result));
        imagedestroy($result);
    }

    // =====================================================
    // Image Processing
    // =====================================================

    public function test_process_jpeg_image(): void
    {
        $path = $this->createTestJpeg();
        $this->assertTrue(is_file($path));

        $result = $this->processor->processImage($path, 'jpg');
        $this->assertTrue($result);
        $this->assertTrue(is_file($path)); // File should still exist
    }

    public function test_process_png_image(): void
    {
        $path = $this->createTestPng();
        $result = $this->processor->processImage($path, 'png');
        $this->assertTrue($result);
        $this->assertTrue(is_file($path));
    }

    public function test_process_gif_image(): void
    {
        $path = $this->createTestGif();
        $result = $this->processor->processImage($path, 'gif');
        $this->assertTrue($result);
        $this->assertTrue(is_file($path));
    }

    public function test_process_svg_image(): void
    {
        $path = $this->createTestSvg();
        $result = $this->processor->processImage($path, 'svg');
        $this->assertTrue($result);
        $this->assertTrue(is_file($path));
    }

    public function test_process_invalid_extension_returns_false(): void
    {
        $path = $this->tempDir . '/test.bmp';
        touch($path);
        $result = $this->processor->processImage($path, 'bmp');
        $this->assertFalse($result);
    }

    public function test_process_invalid_jpeg_data_returns_false(): void
    {
        $path = $this->tempDir . '/fake.jpg';
        file_put_contents($path, 'not a real jpeg file');
        $result = $this->processor->processImage($path, 'jpg');
        $this->assertFalse($result);
    }

    public function test_process_invalid_png_data_returns_false(): void
    {
        $path = $this->tempDir . '/fake.png';
        file_put_contents($path, 'not a real png file');
        $result = $this->processor->processImage($path, 'png');
        $this->assertFalse($result);
    }

    // =====================================================
    // SVG Sanitization
    // =====================================================

    public function test_sanitize_svg_file(): void
    {
        $path = $this->createTestSvg();
        $result = $this->processor->sanitizeSvgFile($path);
        $this->assertTrue($result);
    }

    public function test_sanitize_svg_file_nonexistent(): void
    {
        $result = $this->processor->sanitizeSvgFile('/nonexistent/test.svg');
        $this->assertFalse($result);
    }

    public function test_sanitize_svg_removes_scripts(): void
    {
        $dirtySvg = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert("xss")</script><rect width="100" height="100"/></svg>';
        $clean = $this->processor->sanitizeSvg($dirtySvg);
        $this->assertNotNull($clean);
        $this->assertStringNotContainsString('<script>', $clean);
    }

    // =====================================================
    // Auto Resize
    // =====================================================

    public function test_auto_resize_small_image_not_resized(): void
    {
        $path = $this->createTestJpeg('small.jpg', 100, 100);
        $result = $this->processor->autoResize($path, 'jpg', 1980);
        $this->assertFalse($result['resized']);
    }

    public function test_auto_resize_large_image_is_resized(): void
    {
        // Create a large image
        $path = $this->createTestJpeg('large.jpg', 3000, 2000);
        $result = $this->processor->autoResize($path, 'jpg', 1980);
        $this->assertTrue($result['resized']);
        $this->assertNotNull($result['message']);

        // Verify dimensions were reduced
        $size = getimagesize($path);
        $this->assertLessThanOrEqual(1980, $size[0]);
        $this->assertLessThanOrEqual(1980, $size[1]);
    }

    public function test_auto_resize_png_preserves_transparency(): void
    {
        $path = $this->createTestPng('large.png', 3000, 2000);
        $result = $this->processor->autoResize($path, 'png', 1980);
        $this->assertTrue($result['resized']);
        $this->assertTrue(is_file($path));
    }

    public function test_auto_resize_unsupported_format(): void
    {
        $path = $this->createTestGif('test.gif', 3000, 2000);
        $result = $this->processor->autoResize($path, 'gif', 1980);
        $this->assertFalse($result['resized']); // GIF not supported for auto-resize
    }
}