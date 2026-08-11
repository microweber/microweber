<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Unit;

use MicroweberPackages\ModuleRegistry\Tests\Fixtures\ExampleModule;
use MicroweberPackages\ModuleRegistry\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BaseModuleTest extends TestCase
{
    #[Test]
    public function it_exposes_static_metadata(): void
    {
        $this->assertSame('Example Module', ExampleModule::getName());
        $this->assertSame('example', ExampleModule::getModuleType());
        $this->assertSame(10, ExampleModule::getPosition());
        $this->assertSame('module-registry-test::templates', ExampleModule::getTemplatesNamespace());
        $this->assertSame(['title'], ExampleModule::getTranslatableOptionKeys());
        $this->assertTrue(ExampleModule::shouldRegisterNavigation());
        $this->assertTrue(ExampleModule::shouldRegisterNavigtion()); // BC typo alias
        $this->assertFalse(ExampleModule::isStaticElement());
    }

    #[Test]
    public function it_builds_view_data_from_params(): void
    {
        $module = new ExampleModule(['id' => 'abc', 'template' => 'skin-1']);
        $data = $module->getViewData();

        $this->assertSame('abc', $data['id']);
        $this->assertSame('skin-1', $data['template']);
        $this->assertIsArray($data['options']);
        $this->assertIsArray($data['params']);
    }

    #[Test]
    public function get_view_name_resolves_existing_template(): void
    {
        $this->app['view']->addNamespace(
            'module-registry-test',
            __DIR__ . '/../Fixtures/views'
        );

        $module = new ExampleModule(['id' => 'x']);
        $viewName = $module->getViewName('default');
        $this->assertStringContainsString('default', $viewName);
        $this->assertTrue(view()->exists($viewName));
    }
}
