<?php

namespace Modules\Invoice\Tests\Integration;

use Illuminate\Support\Facades\DB;
use Modules\Invoice\Models\Invoice;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * An order's invoice becomes paid when the order is paid — including the admin
 * "mark paid" path (is_paid toggle → OrderWasUpdated, not OrderWasPaid).
 */
class InvoicePaidOnOrderTest extends TestCase
{
    protected ?Order $order = null;
    protected ?Product $product = null;
    protected ?int $invoiceId = null;

    protected function setUp(): void
    {
        parent::setUp();
        save_option(['option_key' => 'enable_invoices', 'option_value' => '1', 'option_group' => 'shop']);
        save_option(['option_key' => 'auto_generate_invoices', 'option_value' => '1', 'option_group' => 'shop']);
        if (function_exists('cache_clear')) {
            cache_clear();
        }
    }

    #[Test]
    public function marking_an_order_paid_marks_its_invoice_paid(): void
    {
        $this->product = new Product();
        $this->product->title = 'inv-paid-' . uniqid();
        $this->product->url = $this->product->title;
        $this->product->content_type = 'product';
        $this->product->subtype = 'product';
        $this->product->save();

        $this->order = new Order();
        $this->order->order_status = 'pending';
        $this->order->is_paid = 0;
        $this->order->amount = 20;
        $this->order->currency = 'USD';
        $this->order->first_name = 'Inv';
        $this->order->email = 'inv@buyer.com';
        $this->order->order_reference_id = 'E2E' . substr(uniqid(), -6);
        $this->order->save();

        DB::table('cart')->insert([
            'order_id' => $this->order->id,
            'rel_id' => $this->product->id,
            'qty' => 1,
            'price' => 20,
            'order_completed' => 1,
            'session_id' => 'invtest',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // A draft invoice was auto-generated on OrderWasCreated.
        $this->order->refresh();
        $invoice = $this->order->invoice_id
            ? Invoice::find($this->order->invoice_id)
            : Invoice::where('reference_number', 'ORDER-' . $this->order->order_reference_id)->first();

        $this->assertNotNull($invoice, 'A draft invoice should be generated for the new order');
        $this->invoiceId = $invoice->id;
        $this->assertSame(Invoice::STATUS_UNPAID, $invoice->paid_status);

        // Admin marks the order paid (is_paid toggle → OrderWasUpdated).
        $this->order->update(['is_paid' => 1]);

        $invoice->refresh();
        $this->assertSame(Invoice::STATUS_PAID, $invoice->paid_status);
        $this->assertSame(Invoice::STATUS_PAID, $invoice->status);
    }

    protected function tearDown(): void
    {
        if ($this->invoiceId) {
            $inv = Invoice::find($this->invoiceId);
            if ($inv) {
                $inv->items()->delete();
                $inv->delete();
            }
        }
        if ($this->order) {
            DB::table('cart')->where('order_id', $this->order->id)->delete();
            $this->order->forceDelete();
        }
        if ($this->product) {
            $this->product->forceDelete();
        }
        DB::table('options')->where('option_group', 'shop')
            ->whereIn('option_key', ['enable_invoices', 'auto_generate_invoices'])->delete();

        parent::tearDown();
    }
}
