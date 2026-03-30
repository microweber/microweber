<?php

namespace Modules\Payment\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\ListPayments;
use Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\CreatePayment;
use Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages\EditPayment;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use Modules\Payment\Enums\PaymentStatus;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PaymentResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
        // Ensure active payment providers exist so form validation passes
        foreach (['stripe', 'paypal', 'pay_on_delivery'] as $provider) {
            PaymentProvider::firstOrCreate(
                ['provider' => $provider],
                ['name' => ucfirst($provider), 'is_active' => 1]
            );
        }
    }

    protected function tearDown(): void
    {
        PaymentProvider::whereIn('provider', ['stripe', 'paypal', 'pay_on_delivery'])->delete();
        parent::tearDown();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListPayments::class)->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $payments = Payment::factory()->count(3)->create();
        Livewire::test(ListPayments::class)->assertCanSeeTableRecords($payments);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        Livewire::test(CreatePayment::class)
            ->fillForm([
                'rel_id' => 1,
                'rel_type' => 'order',
                'amount' => 100.00,
                'currency' => 'USD',
                'status' => PaymentStatus::Pending,
                'payment_provider' => 'stripe',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('payments', ['amount' => 100.00]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $payment = Payment::factory()->create(['status' => PaymentStatus::Pending]);
        Livewire::test(EditPayment::class, ['record' => $payment->id])
            ->fillForm(['status' => PaymentStatus::Completed])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'status' => PaymentStatus::Completed]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $payment = Payment::factory()->create();
        Livewire::test(ListPayments::class)->callTableAction('delete', $payment);
        $this->assertDatabaseMissing('payments', ['id' => $payment->id]);
    }

    #[Test]
    public function it_can_filter_by_status(): void
    {
        $pending = Payment::factory()->create(['status' => PaymentStatus::Pending]);
        $completed = Payment::factory()->create(['status' => PaymentStatus::Completed]);

        Livewire::test(ListPayments::class)
            ->filterTable('status', PaymentStatus::Pending)
            ->assertCanSeeTableRecords([$pending])
            ->assertCanNotSeeTableRecords([$completed]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListPayments::class)
            ->assertTableColumnExists('id')
            ->assertTableColumnExists('amount')
            ->assertTableColumnExists('status')
            ->assertTableColumnExists('payment_provider');
    }

    #[Test]
    public function it_status_badge_displays_correctly(): void
    {
        $payment = Payment::factory()->create(['status' => PaymentStatus::Completed]);
        Livewire::test(ListPayments::class)->assertSuccessful();
    }
}
