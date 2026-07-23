<?php

namespace MicroweberPackages\MediaPixum\Tests\Feature;

use MicroweberPackages\MediaPixum\PixumGenerator;
use MicroweberPackages\MediaPixum\Tests\TestCase;

class PixumGeneratorTest extends TestCase
{
    protected PixumGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = app(PixumGenerator::class);
    }

    protected function tearDown(): void
    {
        // Clean up generated files
        $cachePath = $this->generator->getCachePath();
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

    public function test_generate_creates_png_file(): void
    {
        $path = $this->generator->generate(100, 50);

        $this->assertFileExists($path);
        $this->assertStringEndsWith('.png', $path);

        // Verify it is a valid PNG
        $info = getimagesize($path);
        $this->assertNotFalse($info);
        $this->assertEquals(100, $info[0]); // width
        $this->assertEquals(50, $info[1]);  // height
        $this->assertEquals(IMAGETYPE_PNG, $info[2]);
    }

    public function test_generate_returns_cached_file_on_second_call(): void
    {
        $path1 = $this->generator->generate(80, 60);
        $mtime1 = filemtime($path1);

        // Second call should return the same file
        $path2 = $this->generator->generate(80, 60);

        $this->assertEquals($path1, $path2);
        $this->assertEquals($mtime1, filemtime($path2));
    }

    public function test_generate_defaults_height_to_width(): void
    {
        $path = $this->generator->generate(120);

        $info = getimagesize($path);
        $this->assertNotFalse($info);
        $this->assertEquals(120, $info[0]);
        $this->assertEquals(120, $info[1]);
    }

    public function test_generate_clamps_zero_to_one(): void
    {
        $path = $this->generator->generate(0, 0);

        $info = getimagesize($path);
        $this->assertNotFalse($info);
        $this->assertEquals(1, $info[0]);
        $this->assertEquals(1, $info[1]);
    }

    public function test_generate_clamps_negative_to_one(): void
    {
        $path = $this->generator->generate(-5, -10);

        $info = getimagesize($path);
        $this->assertNotFalse($info);
        $this->assertEquals(1, $info[0]);
        $this->assertEquals(1, $info[1]);
    }

    public function test_generate_clamps_to_max_dimensions(): void
    {
        $generator = new PixumGenerator(
            $this->generator->getCachePath(),
            maxWidth: 100,
            maxHeight: 100
        );

        $path = $generator->generate(500, 500);

        $info = getimagesize($path);
        $this->assertNotFalse($info);
        $this->assertEquals(100, $info[0]);
        $this->assertEquals(100, $info[1]);
    }

    public function test_url_returns_string(): void
    {
        $url = $this->generator->url(200, 150);

        $this->assertIsString($url);
        $this->assertStringContainsString('200', $url);
        $this->assertStringContainsString('150', $url);
    }

    public function test_url_defaults_height_to_width(): void
    {
        $url = $this->generator->url(300);

        $this->assertIsString($url);
        $this->assertStringContainsString('300', $url);
    }

    public function test_different_dimensions_produce_different_files(): void
    {
        $path1 = $this->generator->generate(50, 50);
        $path2 = $this->generator->generate(100, 100);

        $this->assertNotEquals($path1, $path2);
    }

    public function test_custom_background_color(): void
    {
        $generator = new PixumGenerator(
            $this->generator->getCachePath() . '/custom',
            bgColor: ['r' => 255, 'g' => 0, 'b' => 0, 'a' => 0]
        );

        $path = $generator->generate(10, 10);
        $this->assertFileExists($path);

        // Cleanup custom dir
        @unlink($path);
        @rmdir($generator->getCachePath());
    }
}