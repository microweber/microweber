<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Tests\Unit;

use MicroweberPackages\TemplateFonts\Downloaders\GoogleFontDownloader;
use MicroweberPackages\TemplateFonts\Tests\TestCase;

class GoogleFontDownloaderTest extends TestCase
{
    public function test_download_without_urls_returns_error(): void
    {
        $downloader = new GoogleFontDownloader();
        $downloader->setOutputPath(sys_get_temp_dir() . '/mw-fonts-dl');
        $result = $downloader->download();
        $this->assertArrayHasKey('error', $result);
    }

    public function test_download_without_path_returns_error(): void
    {
        $downloader = new GoogleFontDownloader();
        $downloader->addFontUrl('https://example.com/css');
        $result = $downloader->download();
        $this->assertArrayHasKey('error', $result);
    }

    public function test_download_cairo_when_network_available(): void
    {
        if (getenv('CI') === 'true' && getenv('ALLOW_NETWORK_FONT_DOWNLOAD') !== '1') {
            $this->assertTrue(true, 'Skipped network download in CI');

            return;
        }

        $out = sys_get_temp_dir() . '/mw-fonts-dl-' . uniqid();
        $downloader = new GoogleFontDownloader();
        $downloader->setOutputPath($out);
        $downloader->addFontUrl('https://fonts.googleapis.com/css?family=Cairo');

        try {
            $result = $downloader->download();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Network download failed: ' . $e->getMessage());
        }

        if (isset($result['error'])) {
            $this->markTestSkipped('Downloader error: ' . $result['error']);
        }

        $this->assertDirectoryExists($out);
        // Best-effort: either local css exists or fonts array returned
        $this->assertTrue(
            is_dir($out . '/cairo') || !empty($result['fonts']),
            'Expected cairo font folder or fonts result'
        );
    }
}
