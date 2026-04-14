<?php

namespace MicroweberPackages\LaravelModules\Tests\Unit\Filament;

use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource\Pages\ListModuleDependencies;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource\Pages\CreateModuleDependency;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource\Pages\EditModuleDependency;
use MicroweberPackages\LaravelModules\Models\ModuleDependency;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleDependencyResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        // Clean up all records from previous runs to avoid unique constraint violations
        // and ensure pagination doesn't hide newly created test records
        ModuleDependency::query()->delete();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListModuleDependencies::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $dependencies = ModuleDependency::factory()->count(3)->create();
        Livewire::test(ListModuleDependencies::class)->assertCanSeeTableRecords($dependencies);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreateModuleDependency::class)
            ->fillForm([
                'module_name' => 'TestModule',
                'dependency_module_name' => 'DependencyModule',
                'dependency_type' => ModuleDependency::TYPE_REQUIRE,
                'is_optional' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('module_dependencies', [
            'module_name' => 'TestModule',
            'dependency_module_name' => 'DependencyModule',
        ]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $dependency = ModuleDependency::factory()->create([
            'module_name' => 'OriginalModule',
        ]);

        Livewire::test(EditModuleDependency::class, ['record' => $dependency->id])
            ->fillForm(['module_name' => 'UpdatedModule'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('module_dependencies', [
            'id' => $dependency->id,
            'module_name' => 'UpdatedModule',
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $dependency = ModuleDependency::factory()->create();
        Livewire::test(ListModuleDependencies::class)->callTableAction('delete', $dependency);
        $this->assertDatabaseMissing('module_dependencies', ['id' => $dependency->id]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListModuleDependencies::class)
            ->assertTableColumnExists('module_name')
            ->assertTableColumnExists('dependency_module_name')
            ->assertTableColumnExists('dependency_type')
            ->assertTableColumnExists('is_optional');
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        $dependency = ModuleDependency::factory()->create([
            'module_name' => 'GlobalSearchTestModule',
            'dependency_module_name' => 'TestDependency',
        ]);

        $results = ModuleDependencyResource::getGlobalSearchResults('GlobalSearchTest');
        $this->assertNotEmpty($results);
    }
}
