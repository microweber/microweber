<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Feature;

use MicroweberPackages\TemplateCustomCss\Services\CssUrlRewriter;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\CustomCssManager;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;
use MicroweberPackages\TemplateCustomCss\Support\ArrayOptionStore;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

/**
 * Simulates a clean standalone Laravel app reusing the package + dependencies
 * without Microweber CMS helpers (ArrayOptionStore, temp paths).
 */
class StandaloneIntegrationTest extends TestCase
{
    public function test_full_standalone_stack(): void
    {
        $root = sys_get_temp_dir() . '/mw-standalone-app-' . uniqid();
        $cssPath = $root . '/public/css';
        $cachePath = $root . '/storage/cache';
        @mkdir($cssPath, 0755, true);
        @mkdir($cachePath, 0755, true);

        $config = [
            'css_base_path' => $cssPath,
            'css_base_url' => '/css',
            'css_cache_path' => $cachePath,
            'css_cache_url' => '/cache',
            'userfiles_url' => 'https://mysite.test/userfiles/',
            'default_template' => 'my-theme',
            'validate_on_save' => true,
            'multisite' => false,
            'compile_assets' => true,
            'live_edit_option_key' => 'template_css',
            'live_edit_option_group_prefix' => 'template_',
            'custom_css_option_key' => 'custom_css',
            'custom_css_option_group' => 'template',
            'print_custom_css_route' => 'template/print_custom_css',
            'file_types' => [
                'live_edit' => [
                    'filename' => 'live_edit.css',
                    'validate' => true,
                    'rewrite_urls' => true,
                    'multisite' => true,
                ],
                'custom' => [
                    'storage' => 'option',
                    'validate' => true,
                    'cache' => true,
                ],
            ],
        ];

        $store = new ArrayOptionStore();
        $validator = new CssValidator(true);
        $rewriter = new CssUrlRewriter($config['userfiles_url'], 'https://mysite.test/');
        $live = new LiveEditCssManager($config, $store, $validator, $rewriter);
        $custom = new CustomCssManager($config, $store, $validator);
        $manager = new TemplateCustomCssManager($config, $store, $validator, $rewriter, $live, $custom);

        // 1. Save live edit with media URL rewrite
        $css = '.hero { background: url(https://mysite.test/userfiles/media/bg.jpg); color: #abc; }';
        $saved = $manager->save('live_edit', $css, 'my-theme');
        $this->assertStringContainsString('../../media/bg.jpg', $saved);

        $diskPath = $cssPath . '/my-theme/live_edit.css';
        $this->assertFileExists($diskPath);

        // 2. Custom CSS + cache
        $manager->customCss()->saveCustomCss('footer { opacity: 0.9; }');
        $this->assertStringContainsString('footer', $manager->customCss()->getCustomCss());
        $this->assertSame('footer { opacity: 0.9; }', $store->get('custom_css', 'template'));

        // 3. Backup rewrite
        $fileCss = (string) file_get_contents($diskPath);
        $exported = $manager->getUrlRewriter()->forBackupExport($fileCss);
        $this->assertIsString($exported);

        // 4. Remove → bak → restore
        $remove = $manager->remove('live_edit', 'my-theme', false);
        $this->assertArrayHasKey('success', $remove);
        $this->assertFileExists($diskPath . '.bak');
        $restore = $manager->remove('live_edit', 'my-theme', true);
        $this->assertArrayHasKey('success', $restore);
        $this->assertFileExists($diskPath);

        // 5. Multisite path isolation
        $msConfig = array_merge($config, ['multisite' => true, 'environment' => 'tenant1']);
        $msLive = new LiveEditCssManager($msConfig, $store, $validator, $rewriter);
        $msLive->saveLiveEditCssContent('.tenant { --x: 1; }', 'my-theme');
        $msPath = $msLive->getLiveEditCssPath('my-theme', true);
        $this->assertNotFalse($msPath);
        $this->assertStringContainsString('live_edit_tenant1.css', (string) $msPath);

        // cleanup
        $this->removeTree($root);
    }

    protected function removeTree(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        if ($items === false) {
            return;
        }
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->removeTree($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}
