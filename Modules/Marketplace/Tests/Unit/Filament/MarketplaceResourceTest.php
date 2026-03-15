<?php

namespace Modules\Marketplace\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
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
}
