<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Unit;

use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
use MicroweberPackages\ModuleRegistry\ModuleRegistryManager;
use MicroweberPackages\ModuleRegistry\Tests\Fixtures\ExampleModule;
use MicroweberPackages\ModuleRegistry\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleRegistryManagerTest extends TestCase
{
    private ModuleRegistryManager $registry;

    protected function setUp(): void
    {
        parent::setUp();
        // Fresh instance so CMS-preloaded modules do not leak into unit assertions
        $this->registry = new ModuleRegistryManager();
    }

    #[Test]
    public function it_registers_and_resolves_modules(): void
    {
        $this->registry->module(ExampleModule::class);

        $this->assertTrue($this->registry->hasModule('example'));
        $this->assertSame(ExampleModule::class, $this->registry->getModuleClass('example'));
        $this->assertArrayHasKey('example', $this->registry->getModules());
    }

    #[Test]
    public function it_ignores_missing_classes(): void
    {
        $this->registry->module('Does\\Not\\Exist\\Module');
        $this->assertFalse($this->registry->hasModule('example'));
        $this->assertSame([], $this->registry->getModules());
    }

    #[Test]
    public function it_returns_module_details_sorted_by_position(): void
    {
        $this->registry->module(ExampleModule::class);

        $details = $this->registry->getModulesDetails();
        $this->assertCount(1, $details);
        $this->assertSame('example', $details[0]['module']);
        $this->assertSame('Example Module', $details[0]['name']);
        $this->assertSame(10, $details[0]['position']);
        $this->assertTrue($details[0]['registers_in_navigation']);
    }

    #[Test]
    public function it_returns_settings_components_and_translatable_keys(): void
    {
        $this->registry->module(ExampleModule::class);

        $settings = $this->registry->getSettingsComponents();
        $this->assertArrayHasKey('example', $settings);

        $keys = $this->registry->getTranslatableOptionKeys();
        $this->assertSame(['title'], $keys['example']);
    }

    #[Test]
    public function it_makes_and_renders_registered_module(): void
    {
        $this->app['view']->addNamespace(
            'module-registry-test',
            __DIR__ . '/../Fixtures/views'
        );

        $this->registry->module(ExampleModule::class);

        $instance = $this->registry->make('example', ['id' => 'mod-1', 'template' => 'default']);
        $this->assertInstanceOf(ExampleModule::class, $instance);

        $html = (string) $this->registry->render('example', ['id' => 'mod-1', 'template' => 'default']);
        $this->assertStringContainsString('Example default skin', $html);
        $this->assertStringContainsString('mod-1', $html);
    }

    #[Test]
    public function render_returns_empty_string_for_unknown_module(): void
    {
        $this->assertSame('', $this->registry->render('missing', ['id' => 'x']));
    }

    #[Test]
    public function manager_is_bound_as_microweber_and_class(): void
    {
        $this->assertTrue($this->app->bound('microweber'));
        $this->assertTrue($this->app->bound(ModuleRegistryManager::class));
        $this->assertInstanceOf(ModuleRegistryManager::class, $this->app->make('microweber'));
        $this->assertSame(
            $this->app->make('microweber'),
            $this->app->make(ModuleRegistryManager::class)
        );
    }

    #[Test]
    public function facade_registers_into_shared_cms_registry(): void
    {
        // CMS already registers many modules; ensure facade still works
        ModuleRegistry::module(ExampleModule::class);
        $this->assertTrue(ModuleRegistry::hasModule('example'));
    }
}
