<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

/**
 * Covers the S3 "mirror + serve" capability of TemplateFontsManager.
 *
 * All disk access is routed through Storage::fake('s3'), so no real network
 * or bucket is touched. The compiled stylesheet is still written to the local
 * cache; the remote mirror only changes which URL is served.
 */
class TemplateFontsManagerS3MirrorTest extends TestCase
{
    protected string $tempCachePath = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempCachePath = sys_get_temp_dir() . '/mw-fonts-s3-' . uniqid();
        @mkdir($this->tempCachePath, 0755, true);
    }

    protected function tearDown(): void
    {
        if ($this->tempCachePath !== '' && is_dir($this->tempCachePath)) {
            foreach (glob($this->tempCachePath . '/*') ?: [] as $f) {
                @unlink($f);
            }
            @rmdir($this->tempCachePath);
        }
        parent::tearDown();
    }

    protected function makeManager(array $overrides = []): TemplateFontsManager
    {
        return new TemplateFontsManager(array_merge([
            'compile_assets' => true,
            'css_cache_path' => $this->tempCachePath,
            'css_cache_url' => '/storage/cache',
            'download_google_fonts_locally' => false,
            'migrate_legacy_options' => false,
        ], $overrides));
    }

    public function test_remote_disk_mirrors_compiled_stylesheet_to_s3_and_returns_bucket_url(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => true,
            'css_cache_relpath' => 'userfiles/cache',
        ]);

        $url = $manager->getFontsStylesheetCssUrl();

        // The compiled filename carries a content signature; derive it the same
        // way the manager does so the assertion targets the exact object.
        $filename = $manager->getFontsStylesheetFilename();
        $expectedRel = 'userfiles/cache/' . $filename;

        // The compiled stylesheet is mirrored onto the fake s3 disk.
        Storage::disk('s3')->assertExists($expectedRel);

        // The compiled file is still written to the local cache as well.
        $this->assertFileExists($this->tempCachePath . DIRECTORY_SEPARATOR . $filename);

        // getFontsStylesheetCssUrl() serves from the bucket.
        $this->assertStringStartsWith('https://bucket.example.test', $url);
        $this->assertStringContainsString($expectedRel, $url);
    }

    public function test_local_only_returns_local_cache_url_and_writes_nothing_to_s3(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => false,
            'css_cache_relpath' => 'userfiles/cache',
        ]);

        $url = $manager->getFontsStylesheetCssUrl();
        $filename = $manager->getFontsStylesheetFilename();

        // Nothing is mirrored to the remote disk when disk_is_remote=false.
        $this->assertSame([], Storage::disk('s3')->allFiles());
        Storage::disk('s3')->assertMissing('userfiles/cache/' . $filename);

        // The compiled file is still written to the local cache.
        $this->assertFileExists($this->tempCachePath . DIRECTORY_SEPARATOR . $filename);

        // The returned URL is the local cache URL, not a bucket URL.
        $this->assertSame('/storage/cache/' . $filename, $url);
        $this->assertStringNotContainsString('bucket.example.test', $url);
    }
}
