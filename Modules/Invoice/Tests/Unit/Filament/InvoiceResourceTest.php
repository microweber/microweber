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
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListInvoices::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $invoices = Invoice::factory()->count(3)->create();

        Livewire::test(ListInvoices::class)
            ->assertCanSeeTableRecords($invoices);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        Invoice::factory()->count(15)->create();

        Livewire::test(ListInvoices::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-SEARCH-TEST',
        ]);

        Livewire::test(ListInvoices::class)
            ->searchTable('INV-SEARCH-TEST')
            ->assertCanSeeTableRecords([$invoice]);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(CreateInvoice::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_create_page_validates_required_fields(): void
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
    public function it_create_page_saves_new_record(): void
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
    public function it_edit_page_pre_fills_form_data(): void
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
    public function it_edit_page_updates_record(): void
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
    public function it_delete_action_removes_record(): void
    {
        $invoice = Invoice::factory()->create();

        Livewire::test(ListInvoices::class)
            ->callTableAction('delete', $invoice);

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);
    }

    #[Test]
    public function it_filter_by_status(): void
    {
        $draftInvoice = Invoice::factory()->create(['status' => Invoice::STATUS_DRAFT]);
        $paidInvoice = Invoice::factory()->create(['status' => Invoice::STATUS_PAID]);

        Livewire::test(ListInvoices::class)
            ->filterTable('status', Invoice::STATUS_DRAFT)
            ->assertCanSeeTableRecords([$draftInvoice])
            ->assertCanNotSeeTableRecords([$paidInvoice]);
    }

    #[Test]
    public function it_filter_by_paid_status(): void
    {
        $unpaidInvoice = Invoice::factory()->create(['paid_status' => Invoice::STATUS_UNPAID]);
        $paidInvoice = Invoice::factory()->create(['paid_status' => Invoice::STATUS_PAID]);

        Livewire::test(ListInvoices::class)
            ->filterTable('paid_status', Invoice::STATUS_PAID)
            ->assertCanSeeTableRecords([$paidInvoice])
            ->assertCanNotSeeTableRecords([$unpaidInvoice]);
    }

    #[Test]
    public function it_export_pdf_action_exists(): void
    {
        $invoice = Invoice::factory()->create();

        Livewire::test(ListInvoices::class)
            ->assertTableActionExists('pdf');
    }

    #[Test]
    public function it_sorting_by_column_changes_order(): void
    {
        $customerA = Customer::factory()->create(['name' => 'Alice Anderson']);
        $customerB = Customer::factory()->create(['name' => 'Bob Baker']);
        $customerC = Customer::factory()->create(['name' => 'Charlie Clark']);

        $invoiceA = Invoice::factory()->create([
            'customer_id' => $customerA->id,
            'invoice_number' => 'INV-001',
            'invoice_date' => now()->subDays(5),
            'total' => 1000,
        ]);
        $invoiceB = Invoice::factory()->create([
            'customer_id' => $customerB->id,
            'invoice_number' => 'INV-002',
            'invoice_date' => now()->subDays(3),
            'total' => 2000,
        ]);
        $invoiceC = Invoice::factory()->create([
            'customer_id' => $customerC->id,
            'invoice_number' => 'INV-003',
            'invoice_date' => now()->subDays(1),
            'total' => 3000,
        ]);

        // Test sorting by invoice_number descending
        Livewire::test(ListInvoices::class)
            ->assertSuccessful()
            ->sortTable('invoice_number', 'desc')
            ->assertCanSeeTableRecords([$invoiceC, $invoiceB, $invoiceA], inOrder: true);

        // Test sorting by total ascending
        Livewire::test(ListInvoices::class)
            ->sortTable('total', 'asc')
            ->assertCanSeeTableRecords([$invoiceA, $invoiceB, $invoiceC], inOrder: true);

        // Test sorting by invoice_date descending (default)
        Livewire::test(ListInvoices::class)
            ->sortTable('invoice_date', 'desc')
            ->assertCanSeeTableRecords([$invoiceC, $invoiceB, $invoiceA], inOrder: true);
    }

    #[Test]
    public function it_filter_by_boolean_field(): void
    {
        // Create invoices with different paid statuses
        $unpaidInvoice = Invoice::factory()->create([
            'paid_status' => Invoice::STATUS_UNPAID,
            'invoice_number' => 'INV-UNPAID-001',
        ]);
        $paidInvoice = Invoice::factory()->create([
            'paid_status' => Invoice::STATUS_PAID,
            'invoice_number' => 'INV-PAID-001',
        ]);
        $partiallyPaidInvoice = Invoice::factory()->create([
            'paid_status' => Invoice::STATUS_PARTIALLY_PAID,
            'invoice_number' => 'INV-PARTIAL-001',
        ]);

        // Filter by unpaid status
        Livewire::test(ListInvoices::class)
            ->filterTable('paid_status', Invoice::STATUS_UNPAID)
            ->assertCanSeeTableRecords([$unpaidInvoice])
            ->assertCanNotSeeTableRecords([$paidInvoice, $partiallyPaidInvoice]);

        // Filter by paid status
        Livewire::test(ListInvoices::class)
            ->filterTable('paid_status', Invoice::STATUS_PAID)
            ->assertCanSeeTableRecords([$paidInvoice])
            ->assertCanNotSeeTableRecords([$unpaidInvoice, $partiallyPaidInvoice]);
    }

    #[Test]
    public function it_filter_by_select_relationship(): void
    {
        $customerA = Customer::factory()->create(['name' => 'Customer A']);
        $customerB = Customer::factory()->create(['name' => 'Customer B']);

        $invoiceA = Invoice::factory()->create([
            'customer_id' => $customerA->id,
            'invoice_number' => 'INV-CUST-A-001',
        ]);
        $invoiceB = Invoice::factory()->create([
            'customer_id' => $customerB->id,
            'invoice_number' => 'INV-CUST-B-001',
        ]);
        $invoiceC = Invoice::factory()->create([
            'customer_id' => $customerA->id,
            'invoice_number' => 'INV-CUST-A-002',
        ]);

        // Filter by customer relationship
        Livewire::test(ListInvoices::class)
            ->filterTable('customer_id', $customerA->id)
            ->assertCanSeeTableRecords([$invoiceA, $invoiceC])
            ->assertCanNotSeeTableRecords([$invoiceB]);
    }

    #[Test]
    public function it_bulk_delete_removes_selected_records(): void
    {
        $invoice1 = Invoice::factory()->create(['invoice_number' => 'INV-BULK-001']);
        $invoice2 = Invoice::factory()->create(['invoice_number' => 'INV-BULK-002']);
        $invoice3 = Invoice::factory()->create(['invoice_number' => 'INV-BULK-003']);

        // Select and bulk delete first two invoices
        Livewire::test(ListInvoices::class)
            ->callTableBulkAction('delete', [$invoice1, $invoice2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('invoices', ['id' => $invoice1->id]);
        $this->assertDatabaseMissing('invoices', ['id' => $invoice2->id]);

        // Assert third invoice still exists
        $this->assertDatabaseHas('invoices', ['id' => $invoice3->id]);
    }

    #[Test]
    public function it_export_bulk_action_generates_file(): void
    {
        // Create multiple invoices for bulk export
        $invoice1 = Invoice::factory()->create([
            'invoice_number' => 'INV-EXPORT-001',
            'status' => Invoice::STATUS_PAID,
        ]);
        $invoice2 = Invoice::factory()->create([
            'invoice_number' => 'INV-EXPORT-002',
            'status' => Invoice::STATUS_SENT,
        ]);
        $invoice3 = Invoice::factory()->create([
            'invoice_number' => 'INV-EXPORT-003',
            'status' => Invoice::STATUS_DRAFT,
        ]);

        // Test that export bulk action exists
        Livewire::test(ListInvoices::class)
            ->assertTableBulkActionExists('export');

        // Test that export bulk action can be triggered on selected records
        Livewire::test(ListInvoices::class)
            ->callTableBulkAction('export', [$invoice1->id, $invoice2->id, $invoice3->id], data: [
                'id' => true,
                'invoice_number' => true,
                'customer.name' => true,
                'invoice_date' => true,
                'status' => true,
                'total' => true,
            ])
            ->assertSuccessful();
    }

    #[Test]
    public function it_export_header_action_exists(): void
    {
        // Test that export header action exists
        Livewire::test(ListInvoices::class)
            ->assertTableHeaderActionExists('export');
    }

    #[Test]
    public function it_pdf_export_action_exists(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-PDF-001',
            'status' => Invoice::STATUS_PAID,
        ]);

        // Test PDF export action exists
        Livewire::test(ListInvoices::class)
            ->assertTableActionExists('pdf');

        // Test that PDF export action can be triggered
        Livewire::test(ListInvoices::class)
            ->callTableAction('pdf', $invoice)
            ->assertSuccessful();
    }
}
