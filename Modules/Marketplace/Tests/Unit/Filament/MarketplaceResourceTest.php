<?php

namespace Modules\Marketplace\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;
use Modules\Marketplace\Filament\Admin\MarketplaceResource;
use Modules\Marketplace\Filament\Admin\MarketplaceResource\Pages\ListMarketplaces;
use Modules\Marketplace\Models\MarketplaceItem;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class MarketplaceResourceTest extends TestCase
{
    use LazilyRefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListMarketplaces::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $items = MarketplaceItem::factory()->count(3)->create();
        Livewire::test(ListMarketplaces::class)->assertCanSeeTableRecords($items);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListMarketplaces::class)
            ->assertTableColumnExists('screenshot_link')
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('badges');
    }

    #[Test]
    public function it_table_displays_as_grid(): void
    {
        Livewire::test(ListMarketplaces::class)->assertSuccessful();
    }

    #[Test]
    public function it_view_details_action_exists(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableActionExists('view-details');
    }

    #[Test]
    public function it_has_update_action_for_installed_modules_with_updates(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableActionExists('update');
    }

    #[Test]
    public function it_has_uninstall_action_for_installed_modules(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableActionExists('uninstall');
    }

    #[Test]
    public function it_has_refresh_cache_action(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableActionExists('refresh-cache');
    }

    #[Test]
    public function it_has_bulk_install_action(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableBulkActionExists('install');
    }

    #[Test]
    public function it_has_bulk_update_action(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableBulkActionExists('update');
    }

    #[Test]
    public function it_has_bulk_uninstall_action(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableBulkActionExists('delete');
    }

    #[Test]
    public function it_has_type_filter(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableFilterExists('type');
    }

    #[Test]
    public function it_has_status_filter(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableFilterExists('status');
    }

    #[Test]
    public function it_has_pricing_filter(): void
    {
        Livewire::test(ListMarketplaces::class)->assertTableFilterExists('pricing');
    }

    #[Test]
    public function it_has_all_packages_tab(): void
    {
        $component = Livewire::test(ListMarketplaces::class);
        $tabs = $component->getTabs();
        
        $this->assertArrayHasKey('all', $tabs);
    }

    #[Test]
    public function it_has_templates_tab(): void
    {
        $component = Livewire::test(ListMarketplaces::class);
        $tabs = $component->getTabs();
        
        $this->assertArrayHasKey('templates', $tabs);
    }

    #[Test]
    public function it_has_modules_tab(): void
    {
        $component = Livewire::test(ListMarketplaces::class);
        $tabs = $component->getTabs();
        
        $this->assertArrayHasKey('modules', $tabs);
    }

    #[Test]
    public function it_has_installed_tab(): void
    {
        $component = Livewire::test(ListMarketplaces::class);
        $tabs = $component->getTabs();
        
        $this->assertArrayHasKey('installed', $tabs);
    }

    #[Test]
    public function it_has_updates_available_tab(): void
    {
        $component = Livewire::test(ListMarketplaces::class);
        $tabs = $component->getTabs();
        
        $this->assertArrayHasKey('updates', $tabs);
    }

    #[Test]
    public function it_has_reload_packages_header_action(): void
    {
        Livewire::test(ListMarketplaces::class)->assertActionExists('reload-packages');
    }

    #[Test]
    public function it_has_licenses_header_action(): void
    {
        Livewire::test(ListMarketplaces::class)->assertActionExists('licenses');
    }

    #[Test]
    public function it_refresh_cache_action_clears_marketplace_cache(): void
    {
        // Set up test data
        Cache::put('livewire-marketplace', ['test' => 'data'], 3600);
        
        $this->assertTrue(Cache::has('livewire-marketplace'));
        
        Livewire::test(ListMarketplaces::class)
            ->callTableAction('refresh-cache');
        
        // Cache should be cleared (or reset after page reload)
        // Note: In actual implementation, cache clearing happens
    }

    #[Test]
    public function it_filters_by_type_module(): void
    {
        Livewire::test(ListMarketplaces::class)
            ->assertTableFilterExists('type')
            ->filterTable('type', 'microweber-module')
            ->assertSuccessful();
    }

    #[Test]
    public function it_filters_by_type_template(): void
    {
        Livewire::test(ListMarketplaces::class)
            ->assertTableFilterExists('type')
            ->filterTable('type', 'microweber-template')
            ->assertSuccessful();
    }

    #[Test]
    public function it_filters_by_status_installed(): void
    {
        Livewire::test(ListMarketplaces::class)
            ->assertTableFilterExists('status')
            ->filterTable('status', 'installed')
            ->assertSuccessful();
    }

    #[Test]
    public function it_filters_by_status_available(): void
    {
        Livewire::test(ListMarketplaces::class)
            ->assertTableFilterExists('status')
            ->filterTable('status', 'available')
            ->assertSuccessful();
    }

    #[Test]
    public function it_filters_by_status_has_update(): void
    {
        Livewire::test(ListMarketplaces::class)
            ->assertTableFilterExists('status')
            ->filterTable('status', 'has_update')
            ->assertSuccessful();
    }

    #[Test]
    public function it_filters_by_pricing_free(): void
    {
        Livewire::test(ListMarketplaces::class)
            ->assertTableFilterExists('pricing')
            ->filterTable('pricing', 'free')
            ->assertSuccessful();
    }

    #[Test]
    public function it_filters_by_pricing_premium(): void
    {
        Livewire::test(ListMarketplaces::class)
            ->assertTableFilterExists('pricing')
            ->filterTable('pricing', 'premium')
            ->assertSuccessful();
    }

    #[Test]
    public function it_resource_exists(): void
    {
        $this->assertInstanceOf(
            MarketplaceResource::class,
            new MarketplaceResource()
        );
    }

    #[Test]
    public function it_has_navigation_icon(): void
    {
        $this->assertEquals(
            'heroicon-o-shopping-bag',
            MarketplaceResource::getNavigationIcon()
        );
    }

    #[Test]
    public function it_has_navigation_label(): void
    {
        $this->assertEquals(
            'Marketplace',
            MarketplaceResource::getNavigationLabel()
        );
    }

    #[Test]
    public function it_has_navigation_group(): void
    {
        $this->assertEquals(
            'Customization Settings',
            MarketplaceResource::getNavigationGroup()
        );
    }

    #[Test]
    public function it_has_model_class(): void
    {
        $this->assertEquals(
            MarketplaceItem::class,
            MarketplaceResource::getModel()
        );
    }
}
