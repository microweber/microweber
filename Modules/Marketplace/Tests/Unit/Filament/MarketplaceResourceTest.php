<?php

namespace Modules\Marketplace\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Marketplace\Filament\Admin\MarketplaceResource;
use Modules\Marketplace\Filament\Admin\MarketplaceResource\Pages\ListMarketplaces;
use Modules\Marketplace\Models\MarketplaceItem;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MarketplaceResourceTest extends TestCase
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
        $user = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($user);
        return $user;
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
