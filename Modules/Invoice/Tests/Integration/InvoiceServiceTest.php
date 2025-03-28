<?php

namespace Modules\Invoice\Tests\Integration;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Services\InvoiceService;

class InvoiceServiceTest extends TestCase
{
    protected $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();
        Invoice::truncate();
        $this->invoiceService = app(InvoiceService::class);
    }

    public function testGenerateInvoice()
    {
        $invoiceData = [
            'customer_id' => 1,
            'status' => Invoice::STATUS_DRAFT,
            'sub_total' => 200.00,
            'total' => 200.00
        ];

        $result = $this->invoiceService->generateInvoice($invoiceData);
        $this->assertTrue($result['success']);
        $invoice = Invoice::find($result['invoice_id']);
        $this->assertNotNull($invoice);
        $this->assertEquals(200.00, $invoice->total);
    }

    public function testUpdateInvoicePaidStatus()
    {
        $invoice = Invoice::create([
            'invoice_number' => 'SVC-002',
            'status' => Invoice::STATUS_DRAFT,
            'paid_status' => Invoice::STATUS_UNPAID
        ]);
        
        $result = $this->invoiceService->updateInvoicePaidStatus($invoice->id, Invoice::STATUS_PAID);
        
        $this->assertTrue($result['success']);
        $updatedInvoice = Invoice::find($invoice->id);
        $this->assertEquals(Invoice::STATUS_PAID, $updatedInvoice->paid_status);
    }

    public function testUpdateInvoiceStatus()
    {
        $invoice = Invoice::create([
            'invoice_number' => 'SVC-003',
            'status' => Invoice::STATUS_DRAFT
        ]);
        
        $result = $this->invoiceService->updateInvoiceStatus($invoice->id, Invoice::STATUS_SENT);
        
        $this->assertTrue($result['success']);
        $updatedInvoice = Invoice::find($invoice->id);
        $this->assertEquals(Invoice::STATUS_SENT, $updatedInvoice->status);
    }

    public function testGenerateInvoiceWithZeroTotal()
    {
        $invoiceData = [
            'customer_id' => 1,
            'status' => Invoice::STATUS_DRAFT,
            'sub_total' => 0,
            'total' => 0
        ];

        $result = $this->invoiceService->generateInvoice($invoiceData);
        
        $this->assertTrue($result['success']);
        $invoice = Invoice::find($result['invoice_id']);
        $this->assertEquals(0, $invoice->total);
    }

    public function testGenerateInvoiceWithMaxDiscount()
    {
        $invoiceData = [
            'customer_id' => 1,
            'status' => Invoice::STATUS_DRAFT,
            'sub_total' => 1000,
            'discount' => 1000,
            'discount_type' => 'fixed',
            'total' => 0
        ];

        $result = $this->invoiceService->generateInvoice($invoiceData);
        
        $this->assertTrue($result['success']);
        $invoice = Invoice::find($result['invoice_id']);
        $this->assertEquals(0, $invoice->total);
    }

    public function testUpdateInvalidInvoiceStatus()
    {
        $invoice = Invoice::create([
            'invoice_number' => 'SVC-004',
            'status' => Invoice::STATUS_DRAFT
        ]);
        
        $result = $this->invoiceService->updateInvoiceStatus($invoice->id, 'invalid_status');
        
        $this->assertTrue($result['error']);
        $this->assertEquals('Invalid status', $result['message']);
    }

    public function testBulkInvoiceGeneration()
    {
        // Generate 50 test invoices
        $batchCount = 50;
        $invoiceData = [
            'customer_id' => 1,
            'status' => Invoice::STATUS_DRAFT,
            'sub_total' => 100,
            'total' => 100
        ];

        $results = [];
        for ($i = 0; $i < $batchCount; $i++) {
            $results[] = $this->invoiceService->generateInvoice($invoiceData);
        }

        // Verify all were successful
        $successCount = count(array_filter($results, fn($r) => $r['success']));
        $this->assertEquals($batchCount, $successCount);

        // Verify database count matches
        $invoiceCount = Invoice::count();
        $this->assertEquals($batchCount, $invoiceCount);

        // Verify unique invoice numbers
        $invoiceNumbers = Invoice::pluck('invoice_number')->toArray();
        $this->assertCount($batchCount, array_unique($invoiceNumbers));
    }
}