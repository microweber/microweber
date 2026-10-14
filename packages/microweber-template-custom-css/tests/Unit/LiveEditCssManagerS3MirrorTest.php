<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Support\ArrayOptionStore;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

/**
 * Covers the S3 "mirror + serve" capability of LiveEditCssManager.
 *
 * All disk access is routed through Storage::fake('s3'), so no real network
 * or bucket is touched. The local file remains the source of truth and is
 * always written regardless of the remote-disk setting.
 */
class LiveEditCssManagerS3MirrorTest extends TestCase
{
    /**
     * Build a manager with an isolated local css base and the given config overrides.
     */
    protected function makeManager(array $overrides = []): LiveEditCssManager
    {
        $base = $this->tempCssPath !== '' ? $this->tempCssPath : sys_get_temp_dir() . '/mw-le-s3-' . uniqid();
        @mkdir($base . '/css', 0755, true);

        $config = array_merge([
            'css_base_path' => $base . '/css',
            'css_base_url' => '/storage/css',
            'userfiles_url' => 'http://example.test/userfiles/',
            'default_template' => 'test-theme',
            'validate_on_save' => true,
            'multisite' => false,
            'live_edit_option_key' => 'template_css',
            'live_edit_option_group_prefix' => 'template_',
            'file_types' => [
                'live_edit' => [
                    'filename' => 'live_edit.css',
                    'validate' => true,
                    'rewrite_urls' => true,
                    'multisite' => true,
                ],
            ],
        ], $overrides);

        return new LiveEditCssManager(
            $config,
            new ArrayOptionStore(),
            new CssValidator(true),
            new CssUrlRewriter((string) $config['userfiles_url']),
        );
    }

    public function test_remote_disk_save_mirrors_css_to_s3_and_url_points_at_bucket(): void
    {
        // Fake s3 disk with a public base URL so url() resolves to a bucket link.
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => true,
            'css_base_relpath' => 'userfiles/css',
        ]);

        $css = '.palette { --mw-primary: #112233; }';
        $manager->saveLiveEditCssContent($css, 'Bootstrap');

        $expectedRel = 'userfiles/css/Bootstrap/live_edit.css';

        // (a) The object is mirrored onto the fake s3 disk at the expected path
        // with the saved CSS content.
        Storage::disk('s3')->assertExists($expectedRel);
        $this->assertStringContainsString('--mw-primary', Storage::disk('s3')->get($expectedRel));

        // The local file remains the source of truth and is still written.
        $localPath = $manager->getLiveEditCssPath('Bootstrap', true);
        $this->assertNotFalse($localPath);
        $this->assertFileExists((string) $localPath);

        // getUrl() serves from the bucket: it contains the remote relative path
        // and still carries the local-mtime cache-buster.
        $url = $manager->getUrl('Bootstrap');
        $this->assertNotNull($url);
        $this->assertStringContainsString($expectedRel, (string) $url);
        $this->assertStringStartsWith('https://bucket.example.test', (string) $url);
        $this->assertStringContainsString('?version=', (string) $url);
    }

    public function test_local_only_save_writes_nothing_to_s3_and_url_is_local(): void
    {
        Storage::fake('s3', ['url' => 'https://bucket.example.test']);

        $manager = $this->makeManager([
            'disk' => 's3',
            'disk_is_remote' => false,
            'css_base_relpath' => 'userfiles/css',
        ]);

        $css = '.palette { --mw-primary: #445566; }';
        $manager->saveLiveEditCssContent($css, 'Bootstrap');

        // (b) Nothing is mirrored to the remote disk when disk_is_remote=false.
        Storage::disk('s3')->assertMissing('userfiles/css/Bootstrap/live_edit.css');
        $this->assertSame([], Storage::disk('s3')->allFiles());

        // The local file is still written (unchanged local-only behaviour).
        $localPath = $manager->getLiveEditCssPath('Bootstrap', true);
        $this->assertNotFalse($localPath);
        $this->assertFileExists((string) $localPath);

        // getUrl() returns the legacy local URL, not a bucket URL.
        $url = $manager->getUrl('Bootstrap');
        $this->assertNotNull($url);
        $this->assertStringContainsString('/storage/css/Bootstrap/live_edit.css', (string) $url);
        $this->assertStringNotContainsString('bucket.example.test', (string) $url);
        $this->assertStringContainsString('?version=', (string) $url);
    }
}
