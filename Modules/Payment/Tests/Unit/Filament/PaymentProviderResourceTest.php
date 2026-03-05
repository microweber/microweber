<?php

namespace Modules\Payment\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use Modules\Payment\Models\PaymentProvider;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PaymentProviderResourceTest extends TestCase
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
    public function test_index_page_loads_without_errors(): void
    {
        Livewire::test(ListPaymentProviders::class)->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $providers = PaymentProvider::factory()->count(3)->create();
        Livewire::test(ListPaymentProviders::class)->assertCanSeeTableRecords($providers);
    }

    #[Test]
    public function test_table_has_required_columns(): void
    {
        Livewire::test(ListPaymentProviders::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('provider')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $provider = PaymentProvider::factory()->create();
        Livewire::test(ListPaymentProviders::class)->callTableAction('delete', $provider);
        $this->assertDatabaseMissing('payment_providers', ['id' => $provider->id]);
    }
}
