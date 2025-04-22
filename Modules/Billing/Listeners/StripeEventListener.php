<?php
namespace Modules\Billing\Listeners;

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

class StripeEventListener
{
    /**
     * Handle received Stripe webhooks.
     *
     * @param  \Laravel\Cashier\Events\WebhookReceived  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        $payload = $event->payload;
        $eventType = $payload['type'];



        // Extract customer ID if available
        $stripeId = $payload['data']['object']['customer'] ?? ($payload['data']['object']['object'] === 'customer' ? $payload['data']['object']['id'] : null);

        if (!$stripeId) {
            // Log or handle cases where customer ID is missing if necessary
            return;
        }

        $customer = Cashier::findBillable($stripeId);
        if (!$customer) {
            // Log or handle cases where the customer is not found in the local system
            return;
        }

        $user = User::find($customer->user_id); // Assuming SubscriptionCustomer has user_id
        if (!$user) {
            // Log or handle cases where the user is not found
            return;
        }

        // Handle successful payment
        if ($eventType === 'checkout.session.completed') {
            $session = $payload['data']['object'];

            // Ensure payment was successful
            if ($session['payment_status'] === 'paid') {

                $orderId = $session['metadata']['internal_order_id'] ?? null;
                $relType = $orderId ? morph_name(Order::class) : null; // Determine relation type
                $relId = $orderId;


                $payment = Payment::create([
                    'rel_id' => $relId,
                    'rel_type' => $relType,
                    'amount' => $session['amount_total'] / 100, // Stripe amount is in cents
                    'currency' => strtoupper($session['currency']),
                    'status' => PaymentStatus::Completed,
                    'payment_provider' => 'stripe',
                    'payment_provider_id' => $customer->payment_provider_id ?? null, // Assuming customer model has this
                    'transaction_id' => $session['payment_intent'] ?? $session['id'], // Use payment_intent if available
                    'payment_data' => $session, // Store the full session data for reference
                ]);

                // Dispatch an event indicating payment was processed
                event(new PaymentWasProcessed($payment));

                // Optional: Add logic to update the related Order status if applicable
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order && !$order->is_paid) {
                         $order->is_paid = 1;
                         $order->order_completed = 1;
                         $order->payment_status = 'paid'; // Or use PaymentStatus::Completed->value
                         $order->transaction_id = $payment->transaction_id; // Ensure order has transaction ID
                         $order->save();
                         event(new \Modules\Order\Events\OrderWasPaid($order));
                    }
                }
            }
        }
        // Handle failed or canceled payments
        elseif ($eventType === 'invoice.payment_failed' || $eventType === 'payment_intent.canceled') {

            if ($user) {
                if ($eventType === 'payment_intent.canceled') {
                    $notification = new UserPaymentCanceledNotification([]);
                } else {
                    $notification = new UserPaymentFailedNotification([]);
                }

                Notification::send($user, $notification);


            }
        }
    }
}
