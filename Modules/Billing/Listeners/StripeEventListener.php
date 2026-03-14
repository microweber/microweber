<?php

namespace Modules\Billing\Listeners;

use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Notification;
use Modules\Billing\Notifications\UserPaymentFailedNotification;
use Modules\Billing\Notifications\UserPaymentCanceledNotification;
use MicroweberPackages\Notification\Models\NotificationMailLog;
use MicroweberPackages\User\Models\User;
use Modules\Payment\Models\Payment;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Order\Models\Order;
use Modules\Payment\Events\PaymentWasProcessed;
use Modules\Billing\Models\WebhookLog;

class StripeEventListener
{
    /**
     * Handle received Stripe webhooks.
     *
     * @param \Laravel\Cashier\Events\WebhookReceived $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        $payload = $event->payload;
        $eventType = $payload['type'];

        // Log webhook processing
        Log::info('Processing Stripe webhook', [
            'event_type' => $eventType,
            'event_id' => $payload['id'] ?? 'unknown',
        ]);

        // Extract customer ID if available
        $stripeId = $payload['data']['object']['customer'] ?? (($payload['data']['object']['object'] ?? null) === 'customer' ? $payload['data']['object']['id'] : null);

        if (!$stripeId) {
            Log::warning('Stripe webhook missing customer ID', [
                'event_type' => $eventType,
                'event_id' => $payload['id'] ?? 'unknown',
            ]);
            return;
        }

        $customer = Cashier::findBillable($stripeId);
        if (!$customer) {
            Log::warning('Stripe webhook customer not found', [
                'event_type' => $eventType,
                'stripe_id' => $stripeId,
            ]);
            return;
        }

        $user = User::find($customer->user_id);
        if (!$user) {
            Log::warning('Stripe webhook user not found', [
                'event_type' => $eventType,
                'customer_id' => $customer->id,
            ]);
            return;
        }

        // Handle successful payment
        if ($eventType === 'checkout.session.completed') {
            $this->handleCheckoutSessionCompleted($payload, $customer, $user);
        }
        
        // Handle invoice paid
        elseif ($eventType === 'invoice.paid') {
            $this->handleInvoicePaid($payload, $customer, $user);
        }
        
        // Handle customer subscription updated
        elseif ($eventType === 'customer.subscription.updated') {
            $this->handleCustomerSubscriptionUpdated($payload, $customer, $user);
        }
        
        // Handle failed or canceled payments
        elseif ($eventType === 'invoice.payment_failed' || $eventType === 'payment_intent.canceled') {
            $this->handlePaymentFailed($payload, $customer, $user, $eventType);
        }
    }

    /**
     * Handle checkout.session.completed event.
     */
    protected function handleCheckoutSessionCompleted(array $payload, $customer, User $user): void
    {
        $session = $payload['data']['object'];

        // Ensure payment was successful
        if ($session['payment_status'] === 'paid') {
            $orderId = $session['metadata']['internal_order_id'] ?? null;
            $relType = $orderId ? morph_name(Order::class) : null;
            $relId = $orderId;

            $payment = Payment::create([
                'rel_id' => $relId,
                'rel_type' => $relType,
                'amount' => $session['amount_total'] / 100,
                'currency' => strtoupper($session['currency']),
                'status' => PaymentStatus::Completed,
                'payment_provider' => 'stripe',
                'payment_provider_id' => $customer->payment_provider_id ?? null,
                'transaction_id' => $session['payment_intent'] ?? $session['id'],
                'payment_data' => $session,
            ]);

            event(new PaymentWasProcessed($payment));

            // Update order status if applicable
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && !$order->is_paid) {
                    $order->is_paid = 1;
                    $order->order_completed = 1;
                    $order->payment_status = 'paid';
                    $order->transaction_id = $payment->transaction_id;
                    $order->save();
                    event(new \Modules\Order\Events\OrderWasPaid($order));
                }
            }

            Log::info('Checkout session completed processed', [
                'payment_id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
            ]);
        }
    }

    /**
     * Handle invoice.paid event.
     */
    protected function handleInvoicePaid(array $payload, $customer, User $user): void
    {
        $invoice = $payload['data']['object'];
        
        Log::info('Processing invoice.paid webhook', [
            'invoice_id' => $invoice['id'],
            'subscription_id' => $invoice['subscription'] ?? null,
            'customer_id' => $customer->id,
        ]);

        // Create payment record if not exists
        $existingPayment = Payment::where('transaction_id', $invoice['payment_intent'] ?? $invoice['id'])->first();
        
        if (!$existingPayment) {
            Payment::create([
                'rel_id' => $invoice['subscription'] ?? null,
                'rel_type' => $invoice['subscription'] ? 'subscription' : null,
                'amount' => ($invoice['amount_paid'] ?? 0) / 100,
                'currency' => strtoupper($invoice['currency'] ?? 'usd'),
                'status' => PaymentStatus::Completed,
                'payment_provider' => 'stripe',
                'payment_provider_id' => $customer->payment_provider_id ?? null,
                'transaction_id' => $invoice['payment_intent'] ?? $invoice['id'],
                'payment_data' => $invoice,
            ]);
        }
    }

    /**
     * Handle customer.subscription.updated event.
     */
    protected function handleCustomerSubscriptionUpdated(array $payload, $customer, User $user): void
    {
        $subscription = $payload['data']['object'];
        
        Log::info('Processing customer.subscription.updated webhook', [
            'subscription_id' => $subscription['id'],
            'status' => $subscription['status'],
            'customer_id' => $customer->id,
        ]);

        // Update local subscription if needed
        $localSubscription = $customer->subscriptions()
            ->where('stripe_id', $subscription['id'])
            ->first();
        
        if ($localSubscription) {
            // Update status
            $localSubscription->stripe_status = $subscription['status'];
            
            // Update cancellation info
            if ($subscription['cancel_at_period_end'] ?? false) {
                $localSubscription->ends_at = $localSubscription->onTrial()
                    ? $localSubscription->trial_ends_at
                    : $localSubscription->currentPeriodEnd();
            } elseif (isset($subscription['cancel_at']) || isset($subscription['canceled_at'])) {
                $localSubscription->ends_at = \Illuminate\Support\Carbon::createFromTimestamp(
                    $subscription['cancel_at'] ?? $subscription['canceled_at']
                );
            } else {
                $localSubscription->ends_at = null;
            }
            
            $localSubscription->save();
        }
    }

    /**
     * Handle failed or canceled payment events.
     */
    protected function handlePaymentFailed(array $payload, $customer, User $user, string $eventType): void
    {
        if ($eventType === 'payment_intent.canceled') {
            $notification = new UserPaymentCanceledNotification([]);
        } else {
            $notification = new UserPaymentFailedNotification([]);
        }

        Notification::send($user, $notification);

        Log::info('Payment failure notification sent', [
            'event_type' => $eventType,
            'user_id' => $user->id,
        ]);
    }
}
