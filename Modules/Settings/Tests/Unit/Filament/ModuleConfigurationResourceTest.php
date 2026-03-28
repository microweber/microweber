<?php

namespace Modules\Settings\Tests\Unit\Filament;

use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Modules\Settings\Filament\Resources\ModuleConfigurationResource;
use Modules\Settings\Filament\Resources\ModuleConfigurationResource\Pages\ListModuleConfigurations;
use MicroweberPackages\Module\Models\Module;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleConfigurationResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_resource_exists(): void
    {
        $this->assertInstanceOf(
            ModuleConfigurationResource::class,
            new ModuleConfigurationResource()
        );
    }

    #[Test]
    public function it_has_navigation_icon(): void
    {
        $this->assertEquals(
            'heroicon-o-puzzle-piece',
            ModuleConfigurationResource::getNavigationIcon()
        );
    }

    #[Test]
    public function it_has_navigation_label(): void
    {
        $this->assertEquals(
            'Module Configuration',
            ModuleConfigurationResource::getNavigationLabel()
        );
    }

    #[Test]
    public function it_has_navigation_group(): void
    {
        $this->assertEquals(
            'Customization Settings',
            ModuleConfigurationResource::getNavigationGroup()
        );
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListModuleConfigurations::class)->assertSuccessful();
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('description')
            ->assertTableColumnExists('is_enabled')
            ->assertTableColumnExists('priority');
    }

    #[Test]
    public function it_has_edit_action(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableActionExists('edit');
    }

    #[Test]
    public function it_has_toggle_action(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableActionExists('toggle');
    }

    #[Test]
    public function it_has_refresh_action(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableActionExists('refresh');
    }

    #[Test]
    public function it_has_enable_bulk_action(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableBulkActionExists('enable');
    }

    #[Test]
    public function it_has_disable_bulk_action(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableBulkActionExists('disable');
    }

    #[Test]
    public function it_has_status_filter(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableFilterExists('status');
    }

    #[Test]
    public function it_filters_by_status_enabled(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableFilterExists('status')
            ->filterTable('status', 'enabled')
            ->assertSuccessful();
    }

    #[Test]
    public function it_filters_by_status_disabled(): void
    {
        Livewire::test(ListModuleConfigurations::class)
            ->assertTableFilterExists('status')
            ->filterTable('status', 'disabled')
            ->assertSuccessful();
    }

    #[Test]
    public function it_refresh_action_clears_module_cache(): void
    {
        Cache::put('modules', ['test' => 'data'], 3600);
        $this->assertTrue(Cache::has('modules'));

        Livewire::test(ListModuleConfigurations::class)
            ->callTableAction('refresh');

        // Cache should be cleared
        $this->assertFalse(Cache::has('modules'));
    }

    #[Test]
    public function it_has_model_class(): void
    {
        $this->assertEquals(
            Module::class,
            ModuleConfigurationResource::getModel()
        );
    }

    #[Test]
    public function it_has_plural_label(): void
    {
        $this->assertEquals(
            'Module Configurations',
            ModuleConfigurationResource::getPluralLabel()
        );
    }

    #[Test]
    public function it_has_breadcrumb(): void
    {
        $this->assertEquals(
            'Modules',
            ModuleConfigurationResource::getBreadcrumb()
        );
    }

    #[Test]
    public function it_has_description(): void
    {
        $resource = new ModuleConfigurationResource();
        $this->assertEquals(
            'Manage module settings and configurations',
            $resource->getDescription()
        );
    }

    #[Test]
    public function it_list_page_returns_proper_title(): void
    {
        $livewire = Livewire::test(ListModuleConfigurations::class);
        $this->assertEquals('Module Configuration', $livewire->instance()->getTitle());
    }

    #[Test]
    public function it_has_proper_breadcrumbs(): void
    {
        $livewire = Livewire::test(ListModuleConfigurations::class);
        $breadcrumbs = $livewire->instance()->getBreadcrumbs();

        $this->assertArrayHasKey(url('admin'), $breadcrumbs);
        $this->assertArrayHasKey('#', $breadcrumbs);
        $this->assertEquals('Module Configuration', $breadcrumbs['#']);
    }
}
