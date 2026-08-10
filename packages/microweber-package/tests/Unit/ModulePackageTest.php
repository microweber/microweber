<?php

declare(strict_types=1);

namespace MicroweberPackages\Package\Tests\Unit;

use MicroweberPackages\Package\ModulePackage;
use MicroweberPackages\Package\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;

class ModulePackageTest extends TestCase
{
    #[Test]
    public function it_sets_type_via_constructor_and_fluent_api(): void
    {
        $viaCtor = new ModulePackage('blog');
        $this->assertSame('blog', $viaCtor->type);

        $viaFluent = (new ModulePackage())->type('shop');
        $this->assertSame('shop', $viaFluent->type);
    }

    #[Test]
    public function filament_helpers_are_noop_without_registry(): void
    {
        $module = (new ModulePackage('demo'))
            ->hasFilamentPage('App\\Filament\\Pages\\Demo')
            ->hasFilamentResource('App\\Filament\\Resources\\DemoResource')
            ->hasFilamentPlugin('App\\Filament\\Plugins\\DemoPlugin')
            ->hasFilamentWidget('App\\Filament\\Widgets\\DemoWidget');

        $this->assertSame('demo', $module->type);
    }

    #[Test]
    public function live_edit_helpers_are_noop_without_module_admin(): void
    {
        $module = (new ModulePackage('demo'))
            ->hasLiveEditSettings('App\\Livewire\\DemoSettings')
            ->hasViewComponent('App\\View\\Components\\Demo');

        $this->assertSame('demo', $module->type);
    }

    #[Test]
    public function class_is_not_final_and_is_instantiable(): void
    {
        $ref = new ReflectionClass(ModulePackage::class);
        $this->assertFalse($ref->isAbstract());
        $this->assertFalse($ref->isFinal());
    }
}
