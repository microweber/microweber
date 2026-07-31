<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Feature;

use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

/**
 * CMS integration: TemplateManager adapters, routes, option persistence.
 */
class CmsIntegrationTest extends TestCase
{
    public function test_template_manager_uses_package_live_edit_adapter(): void
    {
        if (!app()->bound('template_manager')) {
            $this->markTestSkipped('CMS template_manager not bound');
        }

        $tm = app()->template_manager;
        $this->assertNotNull($tm->liveEditCssAdapter);
        $this->assertTrue(
            $tm->liveEditCssAdapter instanceof LiveEditCssManager
            || method_exists($tm->liveEditCssAdapter, 'saveLiveEditCssContent'),
            'liveEditCssAdapter must expose saveLiveEditCssContent'
        );
        $this->assertNotNull($tm->customCssAdapter);
        $this->assertTrue(method_exists($tm->customCssAdapter, 'getCustomCssContent'));
    }

    public function test_package_manager_resolves_in_cms(): void
    {
        $manager = app(TemplateCustomCssManager::class);
        $this->assertInstanceOf(TemplateCustomCssManager::class, $manager);

        // CMS should point css_base_path at userfiles/css
        if (function_exists('userfiles_path')) {
            $path = $manager->liveEdit()->getLiveEditCssSaveFolder('Bootstrap');
            $this->assertStringContainsString('css', $path);
            $this->assertStringContainsString('Bootstrap', $path);
        }
    }

    public function test_save_live_edit_and_read_back_via_adapter(): void
    {
        if (!app()->bound('template_manager')) {
            $this->markTestSkipped('CMS template_manager not bound');
        }

        $marker = '/* tcc-cms-test-' . uniqid() . ' */';
        $css = $marker . "\n.tcc-test { color: #c0ffee; }\n";
        $template = 'Bootstrap';

        $adapter = app()->template_manager->liveEditCssAdapter;
        $adapter->saveLiveEditCssContent($css, $template);

        $path = $adapter->getLiveEditCssPath($template, true);
        $this->assertNotFalse($path);
        $this->assertFileExists((string) $path);
        $disk = (string) file_get_contents((string) $path);
        $this->assertStringContainsString('tcc-test', $disk);
        $this->assertStringContainsString('#c0ffee', $disk);

        $url = $adapter->getLiveEditCssUrl($template);
        $this->assertNotEmpty($url);
        $this->assertStringContainsString('live_edit', (string) $url);

        // cleanup — write empty to avoid polluting shared env
        $adapter->saveLiveEditCssContent('', $template);
    }

    public function test_get_custom_css_methods_on_template_manager(): void
    {
        if (!app()->bound('template_manager')) {
            $this->markTestSkipped('CMS template_manager not bound');
        }

        $tm = app()->template_manager;
        $content = $tm->get_custom_css_content();
        $this->assertIsString($content);
        // URL may be false when empty
        $url = $tm->get_custom_css_url();
        $this->assertTrue(is_string($url) || $url === false);
    }

    public function test_layouts_manager_template_check_for_custom_css(): void
    {
        if (!app()->bound('layouts_manager')) {
            $this->markTestSkipped('layouts_manager not bound');
        }

        $layouts = app()->layouts_manager;
        $this->assertTrue(method_exists($layouts, 'template_check_for_custom_css'));
        $this->assertTrue(method_exists($layouts, 'template_save_css'));
        $this->assertTrue(method_exists($layouts, 'template_remove_custom_css'));
    }
}
