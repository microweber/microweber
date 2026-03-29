<?php

namespace Modules\Payment\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\ListPaymentProviders;
use Modules\Payment\Models\PaymentProvider;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PaymentProviderResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        DB::table('payment_providers')->delete();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListPaymentProviders::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $providers = PaymentProvider::factory()->count(3)->create();
        Livewire::test(ListPaymentProviders::class)->loadTable()->assertCanSeeTableRecords($providers);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListPaymentProviders::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('provider')
            ->assertTableColumnExists('is_active');
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $provider = PaymentProvider::factory()->create();
        Livewire::test(ListPaymentProviders::class)->callTableAction('delete', $provider);
        $this->assertDatabaseMissing('payment_providers', ['id' => $provider->id]);
    }
}
