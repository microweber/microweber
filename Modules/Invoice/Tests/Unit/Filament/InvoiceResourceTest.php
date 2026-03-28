<?php

namespace Modules\Invoice\Tests\Unit\Filament;

use Livewire\Livewire;
use Modules\Invoice\Filament\Resources\InvoiceResource;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages\CreateInvoice;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages\EditInvoice;
use Modules\Invoice\Filament\Resources\InvoiceResource\Pages\ListInvoices;
use Modules\Invoice\Models\Invoice;
use Modules\Customer\Models\Customer;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InvoiceResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListInvoices::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_records(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-SHOW-' . uniqid(),
        ]);

        Livewire::test(ListInvoices::class)
            ->searchTable($invoice->invoice_number)
            ->assertCanSeeTableRecords([$invoice]);
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
        $invoiceNumber = 'INV-TEST-' . uniqid();

        Livewire::test(CreateInvoice::class)
            ->fillForm([
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now()->format('Y-m-d'),
                'due_date' => now()->addDays(30)->format('Y-m-d'),
                'customer_id' => $customer->id,
                'status' => Invoice::STATUS_DRAFT,
                'paid_status' => Invoice::STATUS_UNPAID,
                'sub_total' => 10000,
                'total' => 10000,
                'due_amount' => 10000,
                'items' => [],
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('invoices', [
            'invoice_number' => $invoiceNumber,
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
        ]);

        Livewire::test(EditInvoice::class, ['record' => $invoice->id])
            ->fillForm([
                'status' => Invoice::STATUS_SENT,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => Invoice::STATUS_SENT,
        ]);
    }

    #[Test]
    public function it_filter_by_status(): void
    {
        Invoice::factory()->create(['status' => Invoice::STATUS_DRAFT]);
        Invoice::factory()->create(['status' => Invoice::STATUS_PAID]);

        Livewire::test(ListInvoices::class)
            ->filterTable('status', Invoice::STATUS_DRAFT)
            ->assertSuccessful();
    }

    #[Test]
    public function it_filter_by_paid_status(): void
    {
        Invoice::factory()->create(['paid_status' => Invoice::STATUS_UNPAID]);
        Invoice::factory()->create(['paid_status' => Invoice::STATUS_PAID]);

        Livewire::test(ListInvoices::class)
            ->filterTable('paid_status', Invoice::STATUS_PAID)
            ->assertSuccessful();
    }

    #[Test]
    public function it_pdf_action_exists(): void
    {
        Livewire::test(ListInvoices::class)
            ->assertTableActionExists('pdf');
    }

    #[Test]
    public function it_pdf_export_action_works(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-PDF-001',
            'status' => Invoice::STATUS_PAID,
        ]);

        Livewire::test(ListInvoices::class)
            ->callTableAction('pdf', $invoice)
            ->assertSuccessful();
    }

    #[Test]
    public function it_bulk_delete_removes_selected_records(): void
    {
        $invoice1 = Invoice::factory()->create(['invoice_number' => 'INV-BULK-001']);
        $invoice2 = Invoice::factory()->create(['invoice_number' => 'INV-BULK-002']);
        $invoice3 = Invoice::factory()->create(['invoice_number' => 'INV-BULK-003']);

        Livewire::test(ListInvoices::class)
            ->callTableBulkAction('delete', [$invoice1, $invoice2])
            ->assertHasNoTableBulkActionErrors();

        $this->assertDatabaseMissing('invoices', ['id' => $invoice1->id]);
        $this->assertDatabaseMissing('invoices', ['id' => $invoice2->id]);
        $this->assertDatabaseHas('invoices', ['id' => $invoice3->id]);
    }

    #[Test]
    public function it_pages_exist(): void
    {
        $pages = InvoiceResource::getPages();
        $this->assertArrayHasKey('index', $pages);
        $this->assertArrayHasKey('create', $pages);
        $this->assertArrayHasKey('edit', $pages);
    }

    #[Test]
    public function it_has_correct_model(): void
    {
        $this->assertEquals(Invoice::class, InvoiceResource::getModel());
    }
}
