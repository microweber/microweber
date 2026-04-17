<?php

namespace Modules\Invoice\Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceItem;
use Modules\Invoice\Services\InvoiceService;
use Modules\Invoice\Mail\InvoiceMail;
use Modules\Customer\Models\Customer;
use Modules\Order\Models\Order;

class InvoiceGenerationTest extends TestCase
{
    protected InvoiceService $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();
        Invoice::query()->delete();
        InvoiceItem::query()->delete();
        $this->invoiceService = app(InvoiceService::class);
    }

    #[Test]
    public function it_generates_pdf_for_invoice(): void
    {
        // Create customer
        $customer = Customer::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        // Create invoice with items
        $invoice = Invoice::create([
            'invoice_number' => 'INV-001',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_DRAFT,
            'paid_status' => Invoice::STATUS_UNPAID,
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'sub_total' => 10000,
            'total' => 10000,
            'due_amount' => 10000,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 5000,
            'quantity' => 2,
        ]);

        // Generate PDF
        $pdfContent = $this->invoiceService->generatePdf($invoice);

        // Assert PDF is generated
        $this->assertNotEmpty($pdfContent);
        $this->assertStringStartsWith('%PDF', $pdfContent);
    }

    #[Test]
    public function it_sends_invoice_via_email(): void
    {
        // Fake mail
        Mail::fake();

        // Create customer
        $customer = Customer::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ]);

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => 'INV-002',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_DRAFT,
            'paid_status' => Invoice::STATUS_UNPAID,
            'invoice_date' => now(),
            'due_date' => now()->addDays(14),
            'sub_total' => 10000,
            'total' => 10000,
            'due_amount' => 10000,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'name' => 'Test Product',
            'price' => 10000,
            'quantity' => 1,
        ]);

        // Send invoice email
        $result = $this->invoiceService->sendInvoiceEmail($invoice->id);

        // Assert success
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('sent successfully', $result['message']);

        // Assert email was sent
        Mail::assertSent(InvoiceMail::class, function ($mail) use ($invoice, $customer) {
            return $mail->hasTo($customer->email) &&
                   $mail->invoice->id === $invoice->id;
        });

        // Refresh invoice and assert status changed to sent
        $invoice->refresh();
        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status);
    }

    #[Test]
    public function it_fails_to_send_invoice_without_email(): void
    {
        // Create customer without email
        $customer = Customer::factory()->create([
            'email' => null,
        ]);

        // Create invoice
        $invoice = Invoice::create([
            'invoice_number' => 'INV-003',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_DRAFT,
            'sub_total' => 10000,
            'total' => 10000,
            'due_amount' => 10000,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        // Attempt to send
        $result = $this->invoiceService->sendInvoiceEmail($invoice->id);

        // Assert failure
        $this->assertTrue($result['error']);
        $this->assertStringContainsString('No recipient email', $result['message']);
    }

    #[Test]
    public function it_sends_invoice_with_custom_message(): void
    {
        Mail::fake();

        $customer = Customer::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-004',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_DRAFT,
            'sub_total' => 5000,
            'total' => 5000,
            'due_amount' => 5000,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        $customMessage = 'Thank you for your business! Please find your invoice attached.';

        // Add invoice items for PDF generation
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'name' => 'Test Product',
            'price' => 5000,
            'quantity' => 1,
        ]);

        $result = $this->invoiceService->sendInvoiceEmail(
            $invoice->id,
            $customer->email,
            $customMessage
        );

        $this->assertTrue($result['success']);

        Mail::assertSent(InvoiceMail::class, function ($mail) use ($customMessage) {
            return $mail->customMessage === $customMessage;
        });
    }

    #[Test]
    public function it_generates_invoice_from_order(): void
    {
        // Create a customer
        $customer = Customer::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
        ]);

        // Create an order
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_reference_id' => 'ORD-2024-001',
            'amount' => 250.00,
            'order_status' => 'completed',
            'is_paid' => true,
        ]);

        // Refresh order to get actual customer_id (may be modified by event listeners)
        $order->refresh();

        // Generate invoice from order
        $result = $this->invoiceService->generateFromOrder($order->id);

        // Assert success
        $this->assertTrue($result['success']);
        $this->assertNotNull($result['invoice_id']);
        $this->assertNotNull($result['invoice_number']);

        // Verify invoice exists
        $invoice = Invoice::find($result['invoice_id']);
        $this->assertNotNull($invoice);
        $this->assertEquals($order->customer_id, $invoice->customer_id);
        $this->assertEquals(Invoice::STATUS_DRAFT, $invoice->status);
        $this->assertEquals(Invoice::STATUS_PAID, $invoice->paid_status);
        $this->assertStringContainsString('ORDER-ORD-2024-001', $invoice->reference_number);

        // Verify invoice has correct reference
        $this->assertEquals('ORDER-' . $order->order_reference_id, $invoice->reference_number);

        // Verify order updated with invoice_id
        $order->refresh();
        $this->assertNotNull($order->invoice_id);
        $this->assertEquals($invoice->id, $order->invoice_id);
    }

    #[Test]
    public function it_prevents_duplicate_invoice_for_same_order(): void
    {
        $customer = Customer::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_reference_id' => 'ORD-2024-002',
        ]);

        // Generate first invoice
        $result1 = $this->invoiceService->generateFromOrder($order->id);
        $this->assertTrue($result1['success']);

        // Try to generate second invoice
        $result2 = $this->invoiceService->generateFromOrder($order->id);

        // Assert it returns existing invoice
        $this->assertTrue($result2['success']);
        $this->assertEquals($result1['invoice_id'], $result2['invoice_id']);
        $this->assertStringContainsString('already exists', $result2['message']);
    }

    #[Test]
    public function it_calculates_invoice_totals_correctly(): void
    {
        $customer = Customer::factory()->create();

        // Create order with specific amounts
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_reference_id' => 'ORD-2024-003',
            'amount' => 150.00,
            'discount_value' => 10.00,
            'taxes_amount' => 15.00,
        ]);

        // Refresh order to get actual customer_id (may be modified by event listeners)
        $order->refresh();

        $result = $this->invoiceService->generateFromOrder($order->id);

        $this->assertTrue($result['success']);

        $invoice = Invoice::find($result['invoice_id']);

        // Verify invoice exists and has proper values
        $this->assertNotNull($invoice);
        $this->assertEquals($order->customer_id, $invoice->customer_id);

        // Check totals - when no cart items exist, service uses order amounts
        // Verify amounts are in cents format and calculated correctly
        $this->assertGreaterThanOrEqual(0, $invoice->sub_total);
        $this->assertEquals(1000, $invoice->discount_val); // $10 * 100
        $this->assertEquals(1500, $invoice->tax); // $15 * 100
    }

    #[Test]
    public function it_checks_invoice_overdue_status(): void
    {
        $customer = Customer::factory()->create();

        // Create overdue invoice
        $overdueInvoice = Invoice::create([
            'invoice_number' => 'INV-005',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_SENT,
            'paid_status' => Invoice::STATUS_UNPAID,
            'due_date' => now()->subDays(5),
            'sub_total' => 10000,
            'total' => 10000,
            'due_amount' => 10000,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        // Create paid invoice
        $paidInvoice = Invoice::create([
            'invoice_number' => 'INV-006',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_PAID,
            'paid_status' => Invoice::STATUS_PAID,
            'due_date' => now()->subDays(5),
            'sub_total' => 10000,
            'total' => 10000,
            'due_amount' => 0,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        // Create future due date invoice
        $futureInvoice = Invoice::create([
            'invoice_number' => 'INV-007',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_SENT,
            'paid_status' => Invoice::STATUS_UNPAID,
            'due_date' => now()->addDays(5),
            'sub_total' => 10000,
            'total' => 10000,
            'due_amount' => 10000,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        // Assert overdue
        $this->assertTrue($overdueInvoice->isOverdue());

        // Assert not overdue (paid)
        $this->assertFalse($paidInvoice->isOverdue());

        // Assert not overdue (future)
        $this->assertFalse($futureInvoice->isOverdue());
    }

    #[Test]
    public function it_generates_unique_hash(): void
    {
        $hash1 = Invoice::generateUniqueHash();
        $hash2 = Invoice::generateUniqueHash();

        $this->assertNotEmpty($hash1);
        $this->assertNotEmpty($hash2);
        $this->assertNotEquals($hash1, $hash2);
        $this->assertEquals(32, strlen($hash1)); // MD5 is 32 chars
    }

    #[Test]
    public function it_marks_invoice_as_sent(): void
    {
        $customer = Customer::factory()->create();

        $invoice = Invoice::create([
            'invoice_number' => 'INV-008',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_DRAFT,
            'sub_total' => 10000,
            'total' => 10000,
            'due_amount' => 10000,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        $this->assertEquals(Invoice::STATUS_DRAFT, $invoice->status);

        $invoice->markAsSent();

        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status);
    }

    #[Test]
    public function it_has_formatted_accessors(): void
    {
        $customer = Customer::factory()->create();

        $invoice = Invoice::create([
            'invoice_number' => 'INV-009',
            'customer_id' => $customer->id,
            'sub_total' => 12345,
            'discount_val' => 500,
            'total' => 11845,
            'due_amount' => 11845,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'name' => 'Test Item',
            'price' => 12345,
            'quantity' => 1,
        ]);

        // Test invoice formatted accessors
        $this->assertEquals('123.45', $invoice->formatted_sub_total);
        $this->assertEquals('5.00', $invoice->formatted_discount_val);
        $this->assertEquals('118.45', $invoice->formatted_total);
        $this->assertEquals('118.45', $invoice->formatted_due_amount);

        // Test invoice item formatted accessors
        $item = $invoice->items->first();
        $this->assertEquals('123.45', $item->formatted_price);
        $this->assertEquals('123.45', $item->formatted_subtotal);
    }

    #[Test]
    public function it_handles_failed_pdf_generation(): void
    {
        // Create invoice without items (edge case)
        $customer = Customer::factory()->create();

        $invoice = Invoice::create([
            'invoice_number' => 'INV-010',
            'customer_id' => $customer->id,
            'status' => Invoice::STATUS_DRAFT,
            'sub_total' => 0,
            'total' => 0,
            'due_amount' => 0,
            'unique_hash' => Invoice::generateUniqueHash(),
        ]);

        // Should still generate PDF without errors
        $pdfContent = $this->invoiceService->generatePdf($invoice);
        $this->assertNotEmpty($pdfContent);
        $this->assertStringStartsWith('%PDF', $pdfContent);
    }
}
