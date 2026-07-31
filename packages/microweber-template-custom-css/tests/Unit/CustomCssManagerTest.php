<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Unit;

use MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\CustomCssManager;
use MicroweberPackages\TemplateCustomCss\Support\ArrayOptionStore;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

class CustomCssManagerTest extends TestCase
{
    protected function makeManager(array $overrides = [], ?ArrayOptionStore $store = null): CustomCssManager
    {
        $base = $this->tempCssPath !== '' ? $this->tempCssPath : sys_get_temp_dir() . '/mw-cc-' . uniqid();
        @mkdir($base . '/cache', 0755, true);

        $config = array_merge([
            'css_cache_path' => $base . '/cache',
            'css_cache_url' => '/storage/cache',
            'compile_assets' => false,
            'validate_on_save' => true,
            'custom_css_option_key' => 'custom_css',
            'custom_css_option_group' => 'template',
            'print_custom_css_route' => 'template/print_custom_css',
            'file_types' => [
                'custom' => ['validate' => true, 'storage' => 'option'],
            ],
        ], $overrides);

        return new CustomCssManager($config, $store ?? new ArrayOptionStore(), new CssValidator(true));
    }

    public function test_save_and_get_custom_css(): void
    {
        $m = $this->makeManager();
        $m->saveCustomCss('body { font-size: 18px; }');
        $this->assertStringContainsString('font-size: 18px', $m->getCustomCssContent());
    }

    public function test_rejects_invalid_css(): void
    {
        $m = $this->makeManager();
        $this->expectException(InvalidCssException::class);
        $m->saveCustomCss('.x { color: ');
    }

    public function test_url_when_content_present(): void
    {
        $m = $this->makeManager();
        $this->assertFalse($m->getCustomCssUrl());
        $m->saveCustomCss('a { color: red; }');
        $url = $m->getCustomCssUrl();
        $this->assertNotFalse($url);
        $this->assertStringContainsString('print_custom_css', (string) $url);
    }

    public function test_clear_cache(): void
    {
        $base = $this->tempCssPath !== '' ? $this->tempCssPath : sys_get_temp_dir() . '/mw-cc2-' . uniqid();
        @mkdir($base . '/cache', 0755, true);
        $cacheFile = $base . '/cache/custom_css.123.1.0.0.css';
        file_put_contents($cacheFile, 'x');
        $m = $this->makeManager([
            'css_cache_path' => $base . '/cache',
            'compile_assets' => true,
        ]);
        $m->clearCache();
        // glob clear should remove custom_css*.css
        $this->assertFileDoesNotExist($cacheFile);
    }

    public function test_content_hooks(): void
    {
        $m = $this->makeManager();
        $m->addContentHook(static function (): void {
            echo '/* hook */';
        });
        $m->saveCustomCss('/* main */');
        $content = $m->getCustomCssContent();
        $this->assertStringContainsString('/* hook */', $content);
        $this->assertStringContainsString('/* main */', $content);
    }
}
