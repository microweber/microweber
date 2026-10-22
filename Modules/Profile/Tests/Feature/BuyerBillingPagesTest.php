<?php

namespace Modules\Profile\Tests\Feature;

use MicroweberPackages\User\Models\User;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;
use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Profile\Filament\Pages\MyInvoices;
use Modules\Profile\Filament\Pages\MyPayments;
use Modules\Profile\Filament\Pages\TwoFactorAuth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * The buyer-facing Payments & Invoices profile pages only show in the sidebar
 * when the user actually has that data, and the legacy duplicate 2FA page is
 * hidden from the nav.
 */
class BuyerBillingPagesTest extends TestCase
{
    protected array $userIds = [];
    protected array $customerIds = [];
    protected array $orderIds = [];
    protected array $paymentIds = [];
    protected array $invoiceIds = [];

    protected function tearDown(): void
    {
        Payment::whereIn('id', $this->paymentIds)->delete();
        Invoice::whereIn('id', $this->invoiceIds)->delete();
        Order::whereIn('id', $this->orderIds)->forceDelete();
        Customer::whereIn('id', $this->customerIds)->delete();
        User::whereIn('id', $this->userIds)->forceDelete();
        parent::tearDown();
    }

    #[Test]
    public function payments_and_invoices_are_hidden_when_the_user_has_none(): void
    {
        $user = User::factory()->create();
        $this->userIds[] = $user->id;

        $this->actingAs($user);

        $this->assertFalse(MyPayments::shouldRegisterNavigation(), 'Payments hidden with no payments');
        $this->assertFalse(MyInvoices::shouldRegisterNavigation(), 'Invoices hidden with no invoices');
    }

    #[Test]
    public function payments_and_invoices_show_when_the_user_has_them(): void
    {
        $user = User::factory()->create();
        $this->userIds[] = $user->id;
        $customer = Customer::factory()->create(['user_id' => $user->id, 'email' => $user->email]);
        $this->customerIds[] = $customer->id;

        $order = Order::factory()->create(['email' => $user->email]);
        // The Order model re-resolves customer_id on save (customer hook), so
        // pin it to our customer directly for a deterministic linkage.
        \Illuminate\Support\Facades\DB::table('cart_orders')
            ->where('id', $order->id)
            ->update(['customer_id' => $customer->id]);
        $this->orderIds[] = $order->id;

        $payment = Payment::create([
            'rel_id' => $order->id,
            'rel_type' => Order::class,
            'amount' => 42.00,
            'currency' => 'usd',
            'status' => 'paid',
            'payment_provider' => 'stripe',
            'transaction_id' => 'txn_' . uniqid(),
        ]);
        $this->paymentIds[] = $payment->id;

        $invoice = new Invoice();
        $invoice->customer_id = $customer->id;
        $invoice->invoice_number = 'INV-' . uniqid();
        $invoice->invoice_date = now()->toDateString();
        $invoice->status = 'SENT';
        $invoice->paid_status = 'PAID';
        $invoice->total = 42;
        $invoice->due_amount = 0;
        $invoice->unique_hash = substr(md5(uniqid()), 0, 20);
        $invoice->save();
        $this->invoiceIds[] = $invoice->id;

        $this->actingAs($user);

        $this->assertTrue(MyPayments::shouldRegisterNavigation(), 'Payments visible when the user has payments');
        $this->assertTrue(MyInvoices::shouldRegisterNavigation(), 'Invoices visible when the user has invoices');
    }

    #[Test]
    public function legacy_two_factor_page_is_hidden_from_navigation(): void
    {
        // Deduped: the Fortify TwoFactorSettingsPage is the canonical 2FA screen,
        // so the Profile module's legacy page must not appear in the sidebar.
        $this->assertFalse(TwoFactorAuth::shouldRegisterNavigation());
    }
}
