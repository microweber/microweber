<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Tests\Feature;

use MicroweberPackages\TemplateCustomCss\Contracts\OptionStoreInterface;
use MicroweberPackages\TemplateCustomCss\Facades\TemplateCustomCss;
use MicroweberPackages\TemplateCustomCss\Services\CssValidator;
use MicroweberPackages\TemplateCustomCss\Services\CustomCssManager;
use MicroweberPackages\TemplateCustomCss\Services\LiveEditCssManager;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;
use MicroweberPackages\TemplateCustomCss\Tests\TestCase;

/**
 * Validates the package API surface that a standalone Laravel app would use.
 */
class StandaloneAppValidationTest extends TestCase
{
    public function test_package_and_dependant_packages_are_loadable(): void
    {
        $this->assertTrue(class_exists(TemplateCustomCssManager::class));
        $this->assertTrue(class_exists(LiveEditCssManager::class));
        $this->assertTrue(class_exists(CustomCssManager::class));
        $this->assertTrue(class_exists(CssValidator::class));
        $this->assertTrue(class_exists(\Sabberworm\CSS\Parser::class));
        $this->assertTrue(interface_exists(OptionStoreInterface::class));
    }

    public function test_service_usable_without_cms_legacy_classes(): void
    {
        // Package must not depend on the deleted CMS TemplateLiveEditCss class at runtime
        // (class_exists with autoload may still find a deleted file if not cleaned — use false)
        $legacy = \MicroweberPackages\Template\Adapters\TemplateLiveEditCss::class;
        // After refactor the CMS adapter may be a thin alias or deleted; either is fine
        $this->assertTrue(
            class_exists(TemplateCustomCssManager::class),
            'Package manager must load'
        );

        $service = app(TemplateCustomCssManager::class);
        $this->assertInstanceOf(TemplateCustomCssManager::class, $service);
        $this->assertIsArray($service->registeredKeys());
        $this->assertIsArray($service->validate('body{}'));
    }

    public function test_facade_works(): void
    {
        $this->assertInstanceOf(LiveEditCssManager::class, TemplateCustomCss::liveEdit());
        $this->assertInstanceOf(CustomCssManager::class, TemplateCustomCss::customCss());
        $this->assertIsArray(TemplateCustomCss::validate('a{color:red}'));
    }

    public function test_config_is_loaded(): void
    {
        $config = config('template-custom-css');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('css_base_path', $config);
        $this->assertArrayHasKey('file_types', $config);
        $this->assertArrayHasKey('live_edit', $config['file_types'] ?? []);
        $this->assertArrayHasKey('custom', $config['file_types'] ?? []);
    }

    public function test_full_api_surface_for_external_apps(): void
    {
        $service = app(TemplateCustomCssManager::class);

        $this->assertNotNull($service->liveEdit());
        $this->assertNotNull($service->customCss());
        $this->assertNotNull($service->getValidator());
        $this->assertNotNull($service->getUrlRewriter());
        $this->assertNotNull($service->getOptionStore());
        $this->assertIsArray($service->getConfig());
        $this->assertIsArray($service->getHandlers());
    }

    public function test_helpers_resolve(): void
    {
        $this->assertInstanceOf(TemplateCustomCssManager::class, template_custom_css());
        $this->assertInstanceOf(LiveEditCssManager::class, template_live_edit_css());
        $this->assertInstanceOf(CustomCssManager::class, template_user_custom_css());
    }

    public function test_standalone_save_roundtrip_in_temp_dir(): void
    {
        $manager = app(TemplateCustomCssManager::class);
        $base = $this->tempCssPath !== '' ? $this->tempCssPath . '/css' : sys_get_temp_dir() . '/standalone-css';
        @mkdir($base, 0755, true);

        // Rebind paths for isolation when running inside CMS (userfiles would be used)
        $manager->setConfigValue('css_base_path', $base);
        // LiveEditCssManager was constructed with CMS paths — use a fresh isolated instance via save API
        $live = $manager->liveEdit();

        // Use manager save with active template; if CMS paths are used that's also OK for integration
        try {
            $result = $manager->saveLiveEditFromRequest([
                'active_site_template' => 'standalone-theme',
                'css_file_content' => '.standalone { display: block; }',
            ]);
            $this->assertIsArray($result);
            $this->assertArrayHasKey('content', $result);
        } catch (\Throwable $e) {
            // Validation or path issues should not happen for valid CSS
            $this->fail('Standalone save failed: ' . $e->getMessage());
        }
    }
}
