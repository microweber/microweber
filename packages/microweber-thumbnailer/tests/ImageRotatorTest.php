<?php

namespace MicroweberPackages\Thumbnailer\Tests;

use MicroweberPackages\Thumbnailer\Support\ImageRotator;

class ImageRotatorTest extends TestCase
{
    private string $fixtureDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixtureDir = sys_get_temp_dir() . '/thumbnailer-rotator-test';
        if (!is_dir($this->fixtureDir)) {
            mkdir($this->fixtureDir, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        if (is_dir($this->fixtureDir)) {
            foreach (scandir($this->fixtureDir) as $f) {
                if ($f !== '.' && $f !== '..') @unlink($this->fixtureDir . '/' . $f);
            }
            @rmdir($this->fixtureDir);
        }
        parent::tearDown();
    }

    private function createTestImage(): string
    {
        $img = imagecreatetruecolor(200, 100);
        $red = imagecolorallocate($img, 255, 0, 0);
        imagefill($img, 0, 0, $red);
        $path = $this->fixtureDir . '/rotate-test.jpg';
        imagejpeg($img, $path, 90);
        imagedestroy($img);
        return $path;
    }

    public function test_rotate_and_save(): void
    {
        $src = $this->createTestImage();
        $sizeBefore = getimagesize($src);

        $rotator = new ImageRotator($src);
        $rotator->rotateAndSave(90);

        $sizeAfter = getimagesize($src);

        // After 90 degree rotation, width and height should be swapped
        $this->assertEquals($sizeBefore[0], $sizeAfter[1]);
        $this->assertEquals($sizeBefore[1], $sizeAfter[0]);
    }

    public function test_rotate_does_nothing_for_empty_image(): void
    {
        $rotator = new ImageRotator('');
        $rotator->rotateAndSave(90);
        // Should not throw
        $this->assertTrue(true);
    }

    public function test_rotate_ignores_unsupported_formats(): void
    {
        $txtFile = $this->fixtureDir . '/test.txt';
        file_put_contents($txtFile, 'not an image');

        $rotator = new ImageRotator($txtFile);
        $rotator->rotateAndSave(90);

        // File should be unchanged
        $this->assertEquals('not an image', file_get_contents($txtFile));
    }
}