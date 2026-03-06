<?php

namespace Modules\Shipping\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages\ListShippingProviders;
use Modules\Shipping\Models\ShippingProvider;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ShippingProviderResourceTest extends TestCase
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
