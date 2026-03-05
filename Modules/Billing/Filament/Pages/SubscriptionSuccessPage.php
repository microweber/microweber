<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionCustomer;

class SubscriptionSuccessPage extends Page
{
    protected static ?string $title = 'Subscription Successful';

    protected static ?string $slug = 'subscription-success';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'modules.billing::filament.pages.subscription-success';

    public ?array $checkoutSession = null;

    public ?array $subscription = null;

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
                'subscription' => $session->subscription ?? null,
            ];

            // Retrieve subscription details
            if ($session->subscription) {
                $stripeSubscription = $stripe->subscriptions->retrieve($session->subscription);
                $this->subscription = [
                    'id' => $stripeSubscription->id,
                    'status' => $stripeSubscription->status,
                    'current_period_end' => $stripeSubscription->current_period_end ?? null,
                ];

                // Get local subscription record
                $localSubscription = Subscription::where('stripe_id', $session->subscription)
                    ->where('user_id', $user->id)
                    ->with('plan')
                    ->first();

                if ($localSubscription && $localSubscription->plan) {
                    $this->subscription['plan_name'] = $localSubscription->plan->name;
                    $this->subscription['plan_description'] = $localSubscription->plan->description;
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
        return 'Subscription Successful';
    }
}
