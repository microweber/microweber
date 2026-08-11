<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Standalone;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use MicroweberPackages\ModuleRegistry\ModuleRegistryManager;
use MicroweberPackages\ModuleRegistry\ModuleRegistryServiceProvider;
use MicroweberPackages\ModuleRegistry\Support\CmsHelpers;
use MicroweberPackages\ModuleRegistry\Support\ScanForBladeTemplates;
use MicroweberPackages\ModuleRegistry\Tests\Fixtures\ExampleModule;
use MicroweberPackages\ModuleRegistry\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Validates that microweber-packages/module-registry can be used without
 * depending on CMS-specific services for core registry operations.
 */
class StandaloneLaravelUsageTest extends TestCase
{
    #[Test]
    public function package_classes_autoload_without_cms(): void
    {
        $this->assertTrue(class_exists(ModuleRegistryManager::class));
        $this->assertTrue(class_exists(ModuleRegistryServiceProvider::class));
        $this->assertTrue(class_exists(BaseModule::class));
        $this->assertTrue(class_exists(ScanForBladeTemplates::class));
        $this->assertTrue(class_exists(CmsHelpers::class));
    }

    #[Test]
    public function full_standalone_module_workflow(): void
    {
        $this->app['view']->addNamespace(
            'module-registry-test',
            __DIR__ . '/../Fixtures/views'
        );

        // Use a fresh manager — does not require CMS option/template services
        $registry = new ModuleRegistryManager();
        $registry->module(ExampleModule::class);

        $this->assertTrue($registry->hasModule('example'));

        $html = (string) $registry->render('example', [
            'id' => 'standalone-1',
            'template' => 'default',
        ]);

        $this->assertStringContainsString('Example default skin', $html);

        $skins = $registry->getTemplates('example');
        $this->assertIsArray($skins);

        $details = $registry->getModulesDetails();
        $this->assertNotEmpty($details);
    }

    #[Test]
    public function cms_helpers_have_safe_fallbacks(): void
    {
        // When CMS helpers exist they return CMS values; when not, safe defaults.
        $this->assertIsString(CmsHelpers::templateName());
        $this->assertNotSame('', CmsHelpers::templateName());
        $this->assertIsString(CmsHelpers::templateParent('foo'));
        $this->assertIsArray(CmsHelpers::getModuleOptions('group'));
        $this->assertIsArray(CmsHelpers::allTemplates());
        $this->assertIsString(CmsHelpers::normalizePath('/tmp/foo', true));
    }

    #[Test]
    public function content_and_url_traits_do_not_throw_without_optional_services(): void
    {
        $registry = new ModuleRegistryManager();

        // May return null (no content manager) or data (CMS present) — must not throw
        $registry->contentGetById(1);
        $registry->contentGet(['limit' => 1]);
        $this->assertIsString($registry->siteUrl());
        $this->assertIsString($registry->siteHostname());
    }

    #[Test]
    public function shared_facade_is_usable_for_module_registration(): void
    {
        ModuleRegistry::module(ExampleModule::class);
        $this->assertTrue(ModuleRegistry::hasModule('example'));
        $this->assertIsArray(ModuleRegistry::getModules());
    }
}
