<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Unit;

use MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException;
use MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Support\ArrayOptionStore;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

class LiveEditCssManagerTest extends TestCase
{
    protected function makeManager(array $overrides = []): LiveEditCssManager
    {
        $base = $this->tempCssPath !== '' ? $this->tempCssPath : sys_get_temp_dir() . '/mw-le-' . uniqid();
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

    public function test_save_and_read_live_edit_css(): void
    {
        $m = $this->makeManager();
        $css = '.palette { --mw-primary: #112233; }';
        $saved = $m->saveLiveEditCssContent($css, 'Bootstrap');
        $this->assertStringContainsString('--mw-primary', $saved);

        $path = $m->getLiveEditCssPath('Bootstrap', true);
        $this->assertNotFalse($path);
        $this->assertFileExists((string) $path);
        $this->assertStringEndsWith('live_edit.css', (string) $path);
        $this->assertStringContainsString('Bootstrap', (string) $path);

        $content = $m->getContent('Bootstrap');
        $this->assertStringContainsString('--mw-primary', $content);

        $url = $m->getLiveEditCssUrl('Bootstrap');
        $this->assertNotNull($url);
        $this->assertStringContainsString('live_edit.css', (string) $url);
        $this->assertStringContainsString('version=', (string) $url);
    }

    public function test_file_location_matches_cms_convention(): void
    {
        $m = $this->makeManager();
        $folder = $m->getLiveEditCssSaveFolder('MyTemplate');
        $this->assertStringContainsString('MyTemplate', $folder);
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $folder);
        $this->assertSame('live_edit.css', $m->getLiveEditCssFilename());
    }

    public function test_rejects_broken_css(): void
    {
        $m = $this->makeManager();
        $this->expectException(InvalidCssException::class);
        $m->saveLiveEditCssContent('.broken { color: ', 'Bootstrap');
    }

    public function test_allows_empty_css_to_clear(): void
    {
        $m = $this->makeManager();
        $m->saveLiveEditCssContent('.x { color: red; }', 'Bootstrap');
        $m->saveLiveEditCssContent('', 'Bootstrap');
        $path = $m->getLiveEditCssPath('Bootstrap', true);
        $this->assertNotFalse($path);
        $this->assertSame('', trim((string) file_get_contents((string) $path)));
    }

    public function test_url_rewrite_on_save(): void
    {
        $m = $this->makeManager();
        $css = '.bg { background-image: url(http://example.test/userfiles/media/logo.png); }';
        $saved = $m->saveLiveEditCssContent($css, 'Bootstrap');
        $this->assertStringContainsString('../../media/logo.png', $saved);
    }

    public function test_multisite_filename(): void
    {
        $m = $this->makeManager([
            'multisite' => true,
            'environment' => 'site2',
        ]);
        $this->assertSame('live_edit_site2.css', $m->getLiveEditCssFilenameMultisite());
        $m->saveLiveEditCssContent('.a { color: blue; }', 'Bootstrap');
        $path = $m->getLiveEditCssPath('Bootstrap', true);
        $this->assertNotFalse($path);
        $this->assertStringContainsString('live_edit_site2.css', (string) $path);
    }

    public function test_remove_creates_bak(): void
    {
        $m = $this->makeManager();
        $m->saveLiveEditCssContent('.a { color: red; }', 'Bootstrap');
        $result = $m->remove('Bootstrap', false);
        $this->assertArrayHasKey('success', $result);

        $bak = $m->checkForCustomCss('Bootstrap', true);
        $this->assertNotNull($bak);
        $this->assertStringEndsWith('.bak', (string) $bak);
    }

    public function test_option_store_receives_template_css(): void
    {
        $store = new ArrayOptionStore();
        $base = $this->tempCssPath !== '' ? $this->tempCssPath : sys_get_temp_dir() . '/mw-le2-' . uniqid();
        @mkdir($base . '/css', 0755, true);
        $m = new LiveEditCssManager(
            [
                'css_base_path' => $base . '/css',
                'css_base_url' => '/css',
                'default_template' => 't',
                'validate_on_save' => false,
                'multisite' => false,
                'live_edit_option_key' => 'template_css',
                'live_edit_option_group_prefix' => 'template_',
                'file_types' => ['live_edit' => ['filename' => 'live_edit.css', 'validate' => false, 'rewrite_urls' => false]],
            ],
            $store,
            new CssValidator(true),
            new CssUrlRewriter(''),
        );
        $m->saveLiveEditCssContent('.z{color:1}', 'Bootstrap');
        $this->assertSame('.z{color:1}', $store->get('template_css', 'template_Bootstrap'));
    }
}
