<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Order\Models\Order;
use Modules\Payment\Events\PaymentWasProcessed;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use Stripe\StripeClient;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    /**
     * Handle incoming Stripe webhook requests.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
public function handleWebhook(Request $request)
{
$payload = $request->getContent();
$sigHeader = $request->header('Stripe-Signature');
$event = null;

// Get webhook secret from settings
$webhookSecret = $this->getWebhookSecret();

try {
// Verify webhook signature if secret is configured and signature is present
if ($webhookSecret && $sigHeader) {
$event = Webhook::constructEvent(
$payload,
$sigHeader,
$webhookSecret
);
} else {
// For testing without signature verification or when signature is missing
$event = json_decode($payload, true);
}
} catch (\Exception $e) {
Log::error('Stripe webhook signature verification failed', [
'error' => $e->getMessage(),
]);
return new Response('Invalid signature', 400);
}

        $eventType = $event['type'] ?? null;

        if (!$eventType) {
            return new Response('Invalid event', 400);
        }

        Log::info('Stripe webhook received', [
            'type' => $eventType,
            'id' => $event['id'] ?? 'unknown',
        ]);

        // Handle specific event types
        $method = 'handle' . Str::studly(str_replace('.', '_', $eventType));

        if (method_exists($this, $method)) {
            return $this->{$method}($event);
        }

        // Log unhandled events but return success
        Log::info('Unhandled Stripe webhook event', ['type' => $eventType]);
        return new Response('Event received', 200);
    }

    /**
     * Handle checkout.session.completed event.
     *
     * @param array $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCheckoutSessionCompleted(array $event): Response
    {
        $session = $event['data']['object'] ?? [];

        if (empty($session)) {
            return new Response('Invalid session data', 400);
        }

        $orderReferenceId = $session['client_reference_id'] ?? null;
        $paymentStatus = $session['payment_status'] ?? null;

        if (!$orderReferenceId) {
            Log::error('Stripe checkout session missing client_reference_id');
            return new Response('Missing order reference', 400);
        }

        // Find the order
        $order = Order::where('order_reference_id', $orderReferenceId)->first();

        if (!$order) {
            Log::error('Order not found for Stripe checkout session', [
                'order_reference_id' => $orderReferenceId,
            ]);
            return new Response('Order not found', 404);
        }

        // Process payment based on status
        if ($paymentStatus === 'paid') {
            return $this->processSuccessfulPayment($order, $session, 'checkout.session');
        }

        Log::info('Stripe checkout session not yet paid', [
            'order_reference_id' => $orderReferenceId,
            'payment_status' => $paymentStatus,
        ]);

        return new Response('Session not paid yet', 200);
    }

    /**
     * Handle payment_intent.succeeded event.
     *
     * @param array $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentIntentSucceeded(array $event): Response
    {
        $paymentIntent = $event['data']['object'] ?? [];

        if (empty($paymentIntent)) {
            return new Response('Invalid payment intent data', 400);
        }

        $orderReferenceId = $paymentIntent['metadata']['order_reference_id'] ?? null;

        if (!$orderReferenceId) {
            Log::error('Stripe payment intent missing order_reference_id in metadata');
            return new Response('Missing order reference', 400);
        }

        // Find the order
        $order = Order::where('order_reference_id', $orderReferenceId)->first();

        if (!$order) {
            Log::error('Order not found for Stripe payment intent', [
                'order_reference_id' => $orderReferenceId,
            ]);
            return new Response('Order not found', 404);
        }

        return $this->processSuccessfulPayment($order, $paymentIntent, 'payment_intent');
    }

    /**
     * Handle payment_intent.payment_failed event.
     *
     * @param array $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentIntentPaymentFailed(array $event): Response
    {
        $paymentIntent = $event['data']['object'] ?? [];

        if (empty($paymentIntent)) {
            return new Response('Invalid payment intent data', 400);
        }

        $orderReferenceId = $paymentIntent['metadata']['order_reference_id'] ?? null;

        if (!$orderReferenceId) {
            Log::error('Stripe payment intent missing order_reference_id in metadata');
            return new Response('Missing order reference', 400);
        }

        // Find the order
        $order = Order::where('order_reference_id', $orderReferenceId)->first();

        if (!$order) {
            Log::error('Order not found for failed Stripe payment intent', [
                'order_reference_id' => $orderReferenceId,
            ]);
            return new Response('Order not found', 404);
        }

        // Update order status to failed
        $order->payment_status = 'failed';
        $order->save();

        // Get the error message
        $errorMessage = $paymentIntent['last_payment_error']['message'] ?? 'Payment failed';

        Log::info('Stripe payment failed', [
            'order_reference_id' => $orderReferenceId,
            'error' => $errorMessage,
        ]);

        return new Response('Payment failed recorded', 200);
    }

    /**
     * Handle payment_intent.canceled event.
     *
     * @param array $event
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentIntentCanceled(array $event): Response
    {
        $paymentIntent = $event['data']['object'] ?? [];

        if (empty($paymentIntent)) {
            return new Response('Invalid payment intent data', 400);
        }

        $orderReferenceId = $paymentIntent['metadata']['order_reference_id'] ?? null;

        if (!$orderReferenceId) {
            return new Response('Missing order reference', 400);
        }

        // Find the order
        $order = Order::where('order_reference_id', $orderReferenceId)->first();

        if (!$order) {
            return new Response('Order not found', 404);
        }

        // Update order status to cancelled
        if ($order->payment_status !== 'completed') {
            $order->payment_status = 'cancelled';
            $order->save();
        }

        Log::info('Stripe payment canceled', [
            'order_reference_id' => $orderReferenceId,
        ]);

        return new Response('Payment cancellation recorded', 200);
    }

/**
 * Handle refund events.
 *
 * @param array $event
 * @param string $eventType
 * @return \Symfony\Component\HttpFoundation\Response
 */
protected function handleChargeRefunded(array $event): Response
{
$charge = $event['data']['object'] ?? [];

if (empty($charge)) {
return new Response('Invalid charge data', 400);
}

$chargeId = $charge['id'] ?? null;
$paymentIntentId = $charge['payment_intent'] ?? null;

if (!$chargeId && !$paymentIntentId) {
return new Response('Missing charge or payment intent reference', 400);
}

// Find the payment by charge ID first, then by payment intent ID
$query = Payment::query();
if ($chargeId) {
$query->where('transaction_id', $chargeId);
}
if ($paymentIntentId) {
if ($chargeId) {
$query->orWhere('transaction_id', $paymentIntentId);
} else {
$query->where('transaction_id', $paymentIntentId);
}
}

$payment = $query->first();

if ($payment) {
$payment->status = 'refunded';
$payment->payment_data = array_merge(
$payment->payment_data ?? [],
['refund_data' => $charge['refunds'] ?? []]
);
$payment->save();

Log::info('Payment refunded', [
'payment_id' => $payment->id,
'transaction_id' => $payment->transaction_id,
]);
}

return new Response('Refund processed', 200);
}

    /**
     * Process a successful payment.
     *
     * @param Order $order
     * @param array $data
     * @param string $source
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function processSuccessfulPayment(Order $order, array $data, string $source): Response
    {
        try {
            // Extract payment details based on source
            if ($source === 'checkout.session') {
                $transactionId = $data['id'];
                $amount = $data['amount_total'] ?? 0;
                $currency = $data['currency'] ?? 'usd';
                $paymentIntentId = $data['payment_intent'] ?? null;
            } else {
                $transactionId = $data['id'];
                $amount = $data['amount'] ?? 0;
                $currency = $data['currency'] ?? 'usd';
                $paymentIntentId = $data['id'];
            }

            // Update order
            $order->transaction_id = $paymentIntentId ?? $transactionId;
            $order->payment_amount = $amount / 100; // Convert from cents
            $order->payment_currency = strtoupper($currency);
            $order->is_paid = 1;
            $order->payment_status = 'completed';
            $order->payment_data = $data;
            $order->save();

            // Create payment record
            $payment = Payment::firstOrCreate(
                [
                    'transaction_id' => $paymentIntentId ?? $transactionId,
                    'rel_type' => morph_name(Order::class),
                    'rel_id' => $order->id,
                ],
                [
                    'amount' => $amount / 100,
                    'currency' => strtoupper($currency),
                    'status' => 'completed',
                    'payment_provider' => $order->payment_provider,
                    'payment_provider_id' => $order->payment_provider_id,
                    'payment_data' => $data,
                ]
            );

            // Fire event
            event(new PaymentWasProcessed($payment));

            Log::info('Stripe payment processed successfully', [
                'order_reference_id' => $order->order_reference_id,
                'transaction_id' => $transactionId,
                'amount' => $amount / 100,
            ]);

            return new Response('Payment processed successfully', 200);

        } catch (\Exception $e) {
            Log::error('Error processing Stripe payment', [
                'order_reference_id' => $order->order_reference_id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return new Response('Error processing payment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get webhook secret from settings.
     *
     * @return string|null
     */
    protected function getWebhookSecret(): ?string
    {
        // First check environment variable
        $envSecret = config('services.stripe.webhook_secret');
        if ($envSecret) {
            return $envSecret;
        }

        // Then check database settings
        $provider = PaymentProvider::where('provider', 'stripe')
            ->where('is_active', 1)
            ->first();

        if ($provider && !empty($provider->settings['webhook_secret'])) {
            return $provider->settings['webhook_secret'];
        }

        return null;
    }
}
