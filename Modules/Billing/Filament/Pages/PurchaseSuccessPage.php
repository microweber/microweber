<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Order\Models\Order;

class PurchaseSuccessPage extends Page
{
    protected static ?string $title = 'Purchase Successful';

    protected static ?string $slug = 'purchase-success';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'modules.billing::filament.pages.purchase-success';

    public ?array $checkoutSession = null;

    public ?array $order = null;

    public ?array $invoice = null;

    public ?string $error = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect(route('login'));
            return;
        }

        $sessionId = request()->get('session_id');
        if (!$sessionId) {
            $this->error = 'Invalid session ID';
            return;
        }

        $customer = SubscriptionCustomer::firstOrCreate([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        try {
            $stripe = $customer->stripe();
            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if (!$session) {
                $this->error = 'Session not found';
                return;
            }

            $this->checkoutSession = [
                'id' => $session->id,
                'payment_status' => $session->payment_status,
                'amount_total' => $session->amount_total ? $session->amount_total / 100 : 0,
                'currency' => strtoupper($session->currency ?? 'USD'),
                'customer_email' => $session->customer_email ?? $user->email,
            ];

            // Get order details from metadata
            if (isset($session->metadata->internal_order_id)) {
                $order = Order::find($session->metadata->internal_order_id);
                if ($order) {
                    $this->order = [
                        'id' => $order->id,
                        'amount' => $order->amount,
                        'currency' => $order->currency ?? 'USD',
                        'transaction_id' => $order->transaction_id,
                        'created_at' => $order->created_at?->toFormattedDateString(),
                    ];
                }
            }

            // Retrieve invoice if available
            if ($session->invoice) {
                $stripeInvoice = $stripe->invoices->retrieve($session->invoice);
                $this->invoice = [
                    'id' => $stripeInvoice->id,
                    'number' => $stripeInvoice->number,
                    'amount_due' => $stripeInvoice->amount_due ? $stripeInvoice->amount_due / 100 : 0,
                    'currency' => strtoupper($stripeInvoice->currency ?? 'USD'),
                    'pdf_url' => $stripeInvoice->invoice_pdf ?? null,
                    'hosted_invoice_url' => $stripeInvoice->hosted_invoice_url ?? null,
                    'created' => $stripeInvoice->created ?? null,
                ];
            }
        } catch (\Exception $e) {
            $this->error = 'Unable to retrieve session details. Please contact support.';
        }
    }

    public function downloadInvoice(): void
    {
        if ($this->invoice && isset($this->invoice['pdf_url'])) {
            $this->redirect($this->invoice['pdf_url'], true);
        }
    }

    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return 'Purchase Successful';
    }
}
