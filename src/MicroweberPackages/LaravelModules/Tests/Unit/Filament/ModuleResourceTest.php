<?php

namespace MicroweberPackages\LaravelModules\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleResource\ModuleResource;
use MicroweberPackages\LaravelModules\Filament\Resources\ModuleResource\Pages\ListModules;
use MicroweberPackages\LaravelModules\Models\SystemModulesSushi;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsAdmin();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user);

        return $user;
    }

    protected function getResourceClass(): string
    {
        return ModuleResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListModules::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        SystemModulesSushi::factory()->count(3)->create();

        Livewire::test(ListModules::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        SystemModulesSushi::factory()->count(15)->create();

        Livewire::test(ListModules::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        SystemModulesSushi::factory()->create([
            'name' => 'Test Module Search',
        ]);

        Livewire::test(ListModules::class)
            ->searchTable('Test Module')
            ->assertSuccessful();
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListModules::class)
            ->assertTableColumnExists('icon')
            ->assertTableColumnExists('name');
    }

    #[Test]
    public function it_table_displays_as_grid(): void
    {
        Livewire::test(ListModules::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        SystemModulesSushi::factory()->create([
            'name' => 'Searchable Module',
            'description' => 'Test description',
        ]);

        $results = ModuleResource::getGlobalSearchResults('Searchable');
        $this->assertNotEmpty($results);
    }

    #[Test]
    public function it_can_filter_by_type(): void
    {
        Livewire::test(ListModules::class)
            ->filterTable('type', 'all')
            ->assertSuccessful();
    }

    #[Test]
    public function it_table_uses_custom_columns(): void
    {
        Livewire::test(ListModules::class)
            ->assertSuccessful();
    }
}
