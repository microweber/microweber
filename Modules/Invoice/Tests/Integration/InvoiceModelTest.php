<?php

namespace Modules\Invoice\Tests\Integration;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceItem;

class InvoiceModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Invoice::truncate();
        InvoiceItem::truncate();
    }

    #[Test]

    public function it_invoice_creation(): void {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-001',
            'status' => 'draft',
            'total' => 100.00,
            'tax' => 20.00,
            'sub_total' => 80.00
        ]);

        $this->assertNotNull($invoice->id);
        $this->assertEquals('INV-001', $invoice->invoice_number);
        $this->assertEquals('draft', $invoice->status);
    }

    #[Test]

    public function it_invoice_items_relationship(): void {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-002',
            'status' => 'draft'
        ]);

        $item = $invoice->items()->create([
            'description' => 'Test Item',
            'quantity' => 1,
            'price' => 100.00,
            'total' => 100.00
        ]);

        $this->assertCount(1, $invoice->fresh()->items);
        $this->assertEquals('Test Item', $invoice->items->first()->description);
    }

    #[Test]

    public function it_invoice_status_scopes(): void {
        Invoice::create(['invoice_number' => 'INV-003', 'status' => 'draft']);
        Invoice::create(['invoice_number' => 'INV-004', 'status' => 'paid']);
        Invoice::create(['invoice_number' => 'INV-005', 'status' => 'cancelled']);

        $this->assertEquals(1, Invoice::draft()->count());
        $this->assertEquals(1, Invoice::paid()->count());
        $this->assertEquals(1, Invoice::cancelled()->count());
    }

    #[Test]

    public function it_invoice_with_zero_total(): void {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-006',
            'status' => 'draft',
            'sub_total' => 0,
            'total' => 0
        ]);
        
        $this->assertEquals(0, $invoice->total);
        $this->assertEquals('draft', $invoice->status);
    }

    #[Test]

    public function it_invoice_with_max_discount(): void {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-007',
            'status' => 'draft',
            'sub_total' => 1000,
            'discount' => 1000,
            'discount_type' => 'fixed',
            'total' => 0
        ]);
        
        $this->assertEquals(0, $invoice->total);
    }

    #[Test]

    public function it_invoice_with_future_due_date(): void {
        $futureDate = now()->addYear();
        $invoice = Invoice::create([
            'invoice_number' => 'INV-008',
            'status' => 'draft',
            'due_date' => $futureDate,
            'sub_total' => 100,
            'total' => 100
        ]);
        
        $this->assertEquals($futureDate->format('Y-m-d'), $invoice->due_date->format('Y-m-d'));
    }
}