<?php

namespace Modules\Payment\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages\CreatePaymentProvider;
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

    /**
     * Regression: switching the selected provider in the create wizard must
     * refresh the default Name. Previously the Name stuck on the first provider
     * picked (e.g. Pay on delivery -> Stripe kept showing "Pay on delivery").
     */
    #[Test]
    public function it_refreshes_default_name_when_switching_provider(): void
    {
        $available = PaymentProviderResource::getAvailableToSetup()['paymentProviders'];
        if (count($available) < 2) {
            $this->markTestSkipped('Need at least two available payment drivers.');
        }
        $keys = array_keys($available);
        [$k1, $k2] = [$keys[0], $keys[1]];

        Livewire::test(CreatePaymentProvider::class)
            ->set('data.provider', $k1)
            ->assertFormSet(['name' => $available[$k1]])
            ->set('data.provider', $k2)
            ->assertFormSet(['name' => $available[$k2]]);
    }

    /**
     * A Name the operator typed by hand must NOT be overwritten when they go
     * back and switch the provider — only an untouched auto-default is refreshed.
     */
    #[Test]
    public function it_keeps_a_custom_name_when_switching_provider(): void
    {
        $available = PaymentProviderResource::getAvailableToSetup()['paymentProviders'];
        if (count($available) < 2) {
            $this->markTestSkipped('Need at least two available payment drivers.');
        }
        $keys = array_keys($available);
        [$k1, $k2] = [$keys[0], $keys[1]];

        Livewire::test(CreatePaymentProvider::class)
            ->set('data.provider', $k1)
            ->set('data.name', 'My Custom Gateway')
            ->set('data.provider', $k2)
            ->assertFormSet(['name' => 'My Custom Gateway']);
    }
}
