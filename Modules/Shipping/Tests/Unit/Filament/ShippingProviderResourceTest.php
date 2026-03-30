<?php

namespace Modules\Shipping\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders;
use Modules\Shipping\Models\ShippingProvider;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ShippingProviderResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        DB::table('shipping_providers')->delete();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListShippingProviders::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $providers = ShippingProvider::factory()->count(3)->create();
        Livewire::test(ListShippingProviders::class)->assertCanSeeTableRecords($providers);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListShippingProviders::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('provider')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $provider = ShippingProvider::factory()->create();
        Livewire::test(ListShippingProviders::class)->callTableAction('delete', $provider);
        $this->assertDatabaseMissing('shipping_providers', ['id' => $provider->id]);
    }
}
