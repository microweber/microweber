<?php

namespace MicroweberPackages\LaravelModules\Tests\Unit\Services;

use Illuminate\Support\Facades\Cache;
use MicroweberPackages\LaravelModules\Models\ModuleDependency;
use MicroweberPackages\LaravelModules\Services\ModuleDependencyService;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleDependencyServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    #[Test]
    public function it_validates_exact_version_match(): void
    {
        $service = app(ModuleDependencyService::class);
        $this->assertTrue($service->isVersionCompatible('1.0.0', '1.0.0'));
        $this->assertFalse($service->isVersionCompatible('1.0.0', '1.0.1'));
    }

    #[Test]
    public function it_validates_caret_constraint(): void
    {
        $service = app(ModuleDependencyService::class);

        // ^1.2.3 allows >=1.2.3 <2.0.0
        $this->assertTrue($service->isVersionCompatible('1.2.3', '^1.2.3'));
        $this->assertTrue($service->isVersionCompatible('1.5.0', '^1.2.3'));
        $this->assertFalse($service->isVersionCompatible('2.0.0', '^1.2.3'));
        $this->assertFalse($service->isVersionCompatible('1.2.2', '^1.2.3'));

        // ^0.x.y only allows patch changes
        $this->assertTrue($service->isVersionCompatible('0.1.5', '^0.1.0'));
        $this->assertFalse($service->isVersionCompatible('0.2.0', '^0.1.0'));
    }

    #[Test]
    public function it_validates_tilde_constraint(): void
    {
        $service = app(ModuleDependencyService::class);

        // ~1.2.3 allows >=1.2.3 <1.3.0
        $this->assertTrue($service->isVersionCompatible('1.2.3', '~1.2.3'));
        $this->assertTrue($service->isVersionCompatible('1.2.5', '~1.2.3'));
        $this->assertFalse($service->isVersionCompatible('1.3.0', '~1.2.3'));
    }

    #[Test]
    public function it_validates_comparison_operators(): void
    {
        $service = app(ModuleDependencyService::class);

        // >= constraint
        $this->assertTrue($service->isVersionCompatible('2.0.0', '>=1.0.0'));
        $this->assertTrue($service->isVersionCompatible('1.0.0', '>=1.0.0'));
        $this->assertFalse($service->isVersionCompatible('0.9.0', '>=1.0.0'));

        // > constraint
        $this->assertTrue($service->isVersionCompatible('1.0.1', '>1.0.0'));
        $this->assertFalse($service->isVersionCompatible('1.0.0', '>1.0.0'));

        // <= constraint
        $this->assertTrue($service->isVersionCompatible('0.9.0', '<=1.0.0'));
        $this->assertTrue($service->isVersionCompatible('1.0.0', '<=1.0.0'));
        $this->assertFalse($service->isVersionCompatible('1.0.1', '<=1.0.0'));
    }

    #[Test]
    public function it_validates_wildcard_constraint(): void
    {
        $service = app(ModuleDependencyService::class);

        $this->assertTrue($service->isVersionCompatible('1.0.0', '*'));
        $this->assertTrue($service->isVersionCompatible('999.999.999', '*'));
    }

    #[Test]
    public function it_validates_or_constraints(): void
    {
        $service = app(ModuleDependencyService::class);

        // ^1.0 || ^2.0
        $this->assertTrue($service->isVersionCompatible('1.5.0', '^1.0 || ^2.0'));
        $this->assertTrue($service->isVersionCompatible('2.5.0', '^1.0 || ^2.0'));
        $this->assertFalse($service->isVersionCompatible('3.0.0', '^1.0 || ^2.0'));
    }

    #[Test]
    public function it_normalizes_version_strings(): void
    {
        $service = app(ModuleDependencyService::class);

        // Using reflection to test protected method
        $reflection = new \ReflectionClass($service);
        $method = $reflection->getMethod('normalizeVersion');
        $method->setAccessible(true);

        $this->assertEquals('1.0.0', $method->invoke($service, '1'));
        $this->assertEquals('1.5.0', $method->invoke($service, '1.5'));
        $this->assertEquals('1.0.0', $method->invoke($service, 'v1'));
        $this->assertEquals('2.3.4', $method->invoke($service, 'v2.3.4'));
    }

    #[Test]
    public function it_can_create_dependency_records(): void
    {
        $uniqueSuffix = uniqid();
        $dependency = ModuleDependency::create([
            'module_name' => 'Cart' . $uniqueSuffix,
            'dependency_module_name' => 'Product' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
            'version_constraint' => '^1.0',
            'is_optional' => false,
        ]);

        $this->assertDatabaseHas('module_dependencies', [
            'module_name' => 'Cart' . $uniqueSuffix,
            'dependency_module_name' => 'Product' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
        ]);
    }

    #[Test]
    public function it_can_get_module_dependencies(): void
    {
        $service = app(ModuleDependencyService::class);
        $uniqueSuffix = uniqid();

        ModuleDependency::create([
            'module_name' => 'Cart' . $uniqueSuffix,
            'dependency_module_name' => 'Product' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
            'version_constraint' => '^1.0',
        ]);

        ModuleDependency::create([
            'module_name' => 'Cart' . $uniqueSuffix,
            'dependency_module_name' => 'Customer' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_SUGGEST,
            'version_constraint' => '*',
        ]);

        $deps = $service->getModuleDependencies('Cart' . $uniqueSuffix);
        $this->assertCount(2, $deps);
    }

    #[Test]
    public function it_can_get_dependent_modules(): void
    {
        $service = app(ModuleDependencyService::class);
        $uniqueSuffix = uniqid();

        ModuleDependency::create([
            'module_name' => 'Cart' . $uniqueSuffix,
            'dependency_module_name' => 'Product' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
        ]);

        ModuleDependency::create([
            'module_name' => 'Checkout' . $uniqueSuffix,
            'dependency_module_name' => 'Product' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
        ]);

        $dependents = $service->getDependentModules('Product' . $uniqueSuffix);
        $this->assertCount(2, $dependents);
    }

    #[Test]
    public function it_caches_dependencies(): void
    {
        $service = app(ModuleDependencyService::class);
        $uniqueSuffix = uniqid();

        ModuleDependency::create([
            'module_name' => 'TestModule' . $uniqueSuffix,
            'dependency_module_name' => 'Product' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
            'version_constraint' => '^1.0',
        ]);

        // First call should cache
        $deps = $service->getModuleDependencies('TestModule' . $uniqueSuffix);
        $this->assertCount(1, $deps);

        // Delete from DB
        ModuleDependency::where('module_name', 'TestModule' . $uniqueSuffix)->delete();

        // Second call should return cached version
        $deps = $service->getModuleDependencies('TestModule' . $uniqueSuffix);
        $this->assertCount(1, $deps);
    }

    #[Test]
    public function it_can_get_dependency_tree(): void
    {
        $service = app(ModuleDependencyService::class);
        $uniqueSuffix = uniqid();

        ModuleDependency::create([
            'module_name' => 'Cart' . $uniqueSuffix,
            'dependency_module_name' => 'Product' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
        ]);

        ModuleDependency::create([
            'module_name' => 'Checkout' . $uniqueSuffix,
            'dependency_module_name' => 'Cart' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
        ]);

        $tree = $service->getDependencyTree('Checkout' . $uniqueSuffix);

        $this->assertIsArray($tree);
        $this->assertCount(1, $tree);
        $this->assertEquals('Cart' . $uniqueSuffix, $tree[0]['name']);
        $this->assertIsArray($tree[0]['children']);
    }

    #[Test]
    public function it_prevents_infinite_recursion_in_dependency_tree(): void
    {
        $service = app(ModuleDependencyService::class);
        $uniqueSuffix = uniqid();

        ModuleDependency::create([
            'module_name' => 'ModuleA' . $uniqueSuffix,
            'dependency_module_name' => 'ModuleB' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
        ]);

        ModuleDependency::create([
            'module_name' => 'ModuleB' . $uniqueSuffix,
            'dependency_module_name' => 'ModuleA' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
        ]);

        // Should not cause infinite recursion
        $tree = $service->getDependencyTree('ModuleA' . $uniqueSuffix);
        $this->assertIsArray($tree);
    }

    #[Test]
    public function it_can_detect_conflicts(): void
    {
        $service = app(ModuleDependencyService::class);
        $uniqueSuffix = uniqid();

        // Create conflict - ModuleA conflicts with OldModule <2.0
        ModuleDependency::create([
            'module_name' => 'ModuleA' . $uniqueSuffix,
            'dependency_module_name' => 'OldModule' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_CONFLICT,
            'version_constraint' => '<2.0',
        ]);

        // Should return conflict info
        $conflicts = ModuleDependency::where('dependency_type', ModuleDependency::TYPE_CONFLICT)
            ->where('module_name', 'ModuleA' . $uniqueSuffix)
            ->get();

        $this->assertCount(1, $conflicts);
        $this->assertEquals('OldModule' . $uniqueSuffix, $conflicts->first()->dependency_module_name);
    }

    #[Test]
    public function it_can_handle_optional_dependencies(): void
    {
        $uniqueSuffix = uniqid();
        ModuleDependency::create([
            'module_name' => 'Cart' . $uniqueSuffix,
            'dependency_module_name' => 'Seo' . $uniqueSuffix,
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
            'is_optional' => true,
        ]);

        $dep = ModuleDependency::where('module_name', 'Cart' . $uniqueSuffix)->first();
        $this->assertFalse($dep->isRequired());
        $this->assertTrue($dep->is_optional);
    }
}
