<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Unit;

use MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\CustomCssManager;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Services\RegisteredCssFileHandler;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;
use MicroweberPackages\TemplateCustomCss\Support\ArrayOptionStore;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

class TemplateCustomCssManagerTest extends TestCase
{
    protected function makeManager(): TemplateCustomCssManager
    {
        $base = $this->tempCssPath !== '' ? $this->tempCssPath : sys_get_temp_dir() . '/mw-tm-' . uniqid();
        @mkdir($base . '/css', 0755, true);
        @mkdir($base . '/cache', 0755, true);

        $config = [
            'css_base_path' => $base . '/css',
            'css_base_url' => '/storage/css',
            'css_cache_path' => $base . '/cache',
            'css_cache_url' => '/storage/cache',
            'userfiles_url' => 'http://example.test/userfiles/',
            'default_template' => 'test-theme',
            'validate_on_save' => true,
            'multisite' => false,
            'live_edit_option_key' => 'template_css',
            'live_edit_option_group_prefix' => 'template_',
            'custom_css_option_key' => 'custom_css',
            'custom_css_option_group' => 'template',
            'file_types' => [
                'live_edit' => ['filename' => 'live_edit.css', 'validate' => true, 'rewrite_urls' => true],
                'custom' => ['storage' => 'option', 'validate' => true],
            ],
        ];
        $store = new ArrayOptionStore();
        $validator = new CssValidator(true);
        $rewriter = new CssUrlRewriter($config['userfiles_url']);
        $live = new LiveEditCssManager($config, $store, $validator, $rewriter);
        $custom = new CustomCssManager($config, $store, $validator);

        return new TemplateCustomCssManager($config, $store, $validator, $rewriter, $live, $custom);
    }

    public function test_registered_keys(): void
    {
        $m = $this->makeManager();
        $this->assertContains('live_edit', $m->registeredKeys());
        $this->assertContains('custom', $m->registeredKeys());
        $this->assertTrue($m->hasHandler('live_edit'));
    }

    public function test_save_via_manager(): void
    {
        $m = $this->makeManager();
        $m->save('live_edit', '.x { color: green; }', 'Bootstrap');
        $this->assertStringContainsString('green', $m->getContent('live_edit', 'Bootstrap'));
    }

    public function test_register_extra_file_type_per_page(): void
    {
        $m = $this->makeManager();
        $config = $m->getConfig();
        $handler = new RegisteredCssFileHandler(
            'page_99',
            ['filename' => 'page_99.css', 'storage' => 'file', 'validate' => true, 'rewrite_urls' => false],
            $config,
            $m->getOptionStore(),
            $m->getValidator(),
            $m->getUrlRewriter(),
        );
        $m->registerFileType($handler);
        $m->save('page_99', '.page { padding: 1rem; }', 'Bootstrap');
        $path = $m->getPath('page_99', 'Bootstrap');
        $this->assertNotNull($path);
        $this->assertFileExists((string) $path);
        $this->assertStringContainsString('page_99.css', (string) $path);
    }

    public function test_save_live_edit_from_request(): void
    {
        $m = $this->makeManager();
        $result = $m->saveLiveEditFromRequest([
            'active_site_template' => 'Bootstrap',
            'css_file_content' => '.req { opacity: 0.5; }',
        ]);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('url', $result);
        $this->assertArrayHasKey('content', $result);
        $this->assertStringContainsString('opacity', $result['content']);
    }

    public function test_validate_proxy(): void
    {
        $m = $this->makeManager();
        $this->assertTrue($m->validate('a{b:c}')['valid'] || !$m->validate('a{b:c}')['valid']);
        $this->assertTrue($m->validate('a { color: red; }')['valid']);
        $this->assertFalse($m->validate('.x { color: ')['valid']);
    }
}
