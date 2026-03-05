<?php

namespace Modules\Invoice\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Invoice\Filament\Resources\InvoiceResource;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages\CreateInvoice;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages\EditInvoice;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages\ListInvoices;
use Modules\Invoice\Models\Invoice;
use Modules\Customer\Models\Customer;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InvoiceResourceTest extends TestCase
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
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user);

        return $user;
    }

    protected function getResourceClass(): string
    {
        return InvoiceResource::class;
    }

    #[Test]
    public function test_index_page_loads_without_errors(): void
    {
        Livewire::test(ListInvoices::class)
            ->assertSuccessful();
    }

    #[Test]
    public function test_index_page_shows_all_records(): void
    {
        $invoices = Invoice::factory()->count(3)->create();

        Livewire::test(ListInvoices::class)
            ->assertCanSeeTableRecords($invoices);
    }

    #[Test]
    public function test_index_page_supports_pagination(): void
    {
        Invoice::factory()->count(15)->create();

        Livewire::test(ListInvoices::class)
            ->assertSuccessful();
    }

    #[Test]
    public function test_index_page_supports_search(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-SEARCH-TEST',
        ]);

        Livewire::test(ListInvoices::class)
            ->searchTable('INV-SEARCH-TEST')
            ->assertCanSeeTableRecords([$invoice]);
    }

    #[Test]
    public function test_create_page_renders_form(): void
    {
        Livewire::test(CreateInvoice::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function test_create_page_validates_required_fields(): void
    {
        Livewire::test(CreateInvoice::class)
            ->fillForm([
                'invoice_number' => '',
                'invoice_date' => '',
                'due_date' => '',
                'status' => '',
                'paid_status' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'invoice_number',
                'invoice_date',
                'due_date',
                'status',
                'paid_status',
            ]);
    }

    #[Test]
    public function test_create_page_saves_new_record(): void
    {
        $customer = Customer::factory()->create();

        Livewire::test(CreateInvoice::class)
            ->fillForm([
                'invoice_number' => 'INV-TEST-001',
                'invoice_date' => now()->format('Y-m-d'),
                'due_date' => now()->addDays(30)->format('Y-m-d'),
                'customer_id' => $customer->id,
                'status' => Invoice::STATUS_DRAFT,
                'paid_status' => Invoice::STATUS_UNPAID,
                'sub_total' => 10000,
                'total' => 10000,
                'due_amount' => 10000,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => 'INV-TEST-001',
            'status' => Invoice::STATUS_DRAFT,
            'customer_id' => $customer->id,
        ]);
    }

    #[Test]
    public function test_edit_page_pre_fills_form_data(): void
    {
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->create([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-EDIT-TEST',
        ]);

        Livewire::test(EditInvoice::class, ['record' => $invoice->id])
            ->assertSuccessful()
            ->assertFormSet([
                'invoice_number' => 'INV-EDIT-TEST',
                'customer_id' => $customer->id,
            ]);
    }

    #[Test]
    public function test_edit_page_updates_record(): void
    {
        $invoice = Invoice::factory()->create([
            'status' => Invoice::STATUS_DRAFT,
            'notes' => 'Original notes',
        ]);

        Livewire::test(EditInvoice::class, ['record' => $invoice->id])
            ->fillForm([
                'status' => Invoice::STATUS_SENT,
                'notes' => 'Updated notes',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => Invoice::STATUS_SENT,
            'notes' => 'Updated notes',
        ]);
    }

    #[Test]
    public function test_delete_action_removes_record(): void
    {
        $invoice = Invoice::factory()->create();

        Livewire::test(ListInvoices::class)
            ->callTableAction('delete', $invoice);

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);
    }

    #[Test]
    public function test_filter_by_status(): void
    {
        $draftInvoice = Invoice::factory()->create(['status' => Invoice::STATUS_DRAFT]);
        $paidInvoice = Invoice::factory()->create(['status' => Invoice::STATUS_PAID]);

        Livewire::test(ListInvoices::class)
            ->filterTable('status', Invoice::STATUS_DRAFT)
            ->assertCanSeeTableRecords([$draftInvoice])
            ->assertCanNotSeeTableRecords([$paidInvoice]);
    }

    #[Test]
    public function test_filter_by_paid_status(): void
    {
        $unpaidInvoice = Invoice::factory()->create(['paid_status' => Invoice::STATUS_UNPAID]);
        $paidInvoice = Invoice::factory()->create(['paid_status' => Invoice::STATUS_PAID]);

        Livewire::test(ListInvoices::class)
            ->filterTable('paid_status', Invoice::STATUS_PAID)
            ->assertCanSeeTableRecords([$paidInvoice])
            ->assertCanNotSeeTableRecords([$unpaidInvoice]);
    }

    #[Test]
    public function test_export_pdf_action_exists(): void
    {
        $invoice = Invoice::factory()->create();

        Livewire::test(ListInvoices::class)
            ->assertTableActionExists('pdf');
    }

    #[Test]
    public function test_invoice_has_required_relationships(): void
    {
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $this->assertInstanceOf(Customer::class, $invoice->customer);
        $this->assertEquals($customer->id, $invoice->customer->id);
    }
}
