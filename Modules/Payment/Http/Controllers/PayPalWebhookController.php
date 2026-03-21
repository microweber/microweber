<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Order\Models\Order;
use Modules\Payment\Events\PaymentWasProcessed;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use Symfony\Component\HttpFoundation\Response;

/**
 * PayPal Webhook Controller
 * 
 * Handles incoming webhook notifications from PayPal for:
 * - Payment capture/completion
 * - Payment failures
 * - Refunds
 * - Disputes
 */
class PayPalWebhookController extends Controller
{
    /**
     * Handle incoming PayPal webhook requests.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['event_type'] ?? null;
        
        if (!$eventType) {
            Log::warning('PayPal webhook received without event_type', [
                'payload' => $payload,
            ]);
            return new Response('Invalid event type', 400);
        }
        
        Log::info('PayPal webhook received', [
            'event_type' => $eventType,
            'id' => $payload['id'] ?? 'unknown',
        ]);
        
        // Handle specific event types
        $method = $this->getHandlerMethod($eventType);
        
        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        }
        
        // Log unhandled events but return success
        Log::info('Unhandled PayPal webhook event', ['type' => $eventType]);
        return new Response('Event received', 200);
    }
    
    /**
     * Convert PayPal event type to handler method name
     *
     * @param string $eventType
     * @return string
     */
    protected function getHandlerMethod(string $eventType): string
    {
        // Convert PAYMENT.CAPTURE.COMPLETED to handlePaymentCaptureCompleted
        return 'handle' . str_replace(' ', '', ucwords(str_replace(['.', '_'], ' ', $eventType)));
    }
    
    /**
     * Handle PAYMENT.CAPTURE.COMPLETED event
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentCaptureCompleted(array $payload): Response
    {
        $resource = $payload['resource'] ?? [];
        $customId = $resource['custom_id'] ?? null;
        $invoiceId = $resource['invoice_id'] ?? null;
        $orderId = $customId ?: $invoiceId;
        
        if (!$orderId) {
            Log::error('PayPal payment capture missing order reference', [
                'payload' => $payload,
            ]);
            return new Response('Missing order reference', 400);
        }
        
        // Find the order
        $order = Order::where('order_reference_id', $orderId)->first();
        
        if (!$order) {
            Log::error('Order not found for PayPal payment capture', [
                'order_reference_id' => $orderId,
            ]);
            return new Response('Order not found', 404);
        }
        
        // Check if already processed
        if ($order->is_paid && $order->payment_status === 'completed') {
            Log::info('PayPal payment already processed', [
                'order_reference_id' => $orderId,
            ]);
            return new Response('Payment already processed', 200);
        }
        
        // Process the payment
        return $this->processSuccessfulPayment($order, $resource, 'paypal.capture');
    }
    
    /**
     * Handle CHECKOUT.ORDER.APPROVED event
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCheckoutOrderApproved(array $payload): Response
    {
        $resource = $payload['resource'] ?? [];
        $customId = $resource['purchase_units'][0]['custom_id'] ?? null;
        $invoiceId = $resource['purchase_units'][0]['invoice_id'] ?? null;
        $orderId = $customId ?: $invoiceId;
        
        if (!$orderId) {
            Log::warning('PayPal checkout order approved missing order reference', [
                'payload' => $payload,
            ]);
            return new Response('Missing order reference', 400);
        }
        
        Log::info('PayPal checkout order approved', [
            'order_reference_id' => $orderId,
            'paypal_order_id' => $resource['id'] ?? 'unknown',
        ]);
        
        // Order is approved but not yet captured
        // The actual capture will trigger PAYMENT.CAPTURE.COMPLETED
        return new Response('Order approval recorded', 200);
    }
    
    /**
     * Handle CHECKOUT.ORDER.COMPLETED event
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCheckoutOrderCompleted(array $payload): Response
    {
        $resource = $payload['resource'] ?? [];
        $customId = $resource['purchase_units'][0]['custom_id'] ?? null;
        $invoiceId = $resource['purchase_units'][0]['invoice_id'] ?? null;
        $orderId = $customId ?: $invoiceId;
        
        if (!$orderId) {
            Log::error('PayPal checkout order completed missing order reference', [
                'payload' => $payload,
            ]);
            return new Response('Missing order reference', 400);
        }
        
        // Find the order
        $order = Order::where('order_reference_id', $orderId)->first();
        
        if (!$order) {
            Log::error('Order not found for PayPal checkout completion', [
                'order_reference_id' => $orderId,
            ]);
            return new Response('Order not found', 404);
        }
        
        // Check if already processed
        if ($order->is_paid && $order->payment_status === 'completed') {
            return new Response('Payment already processed', 200);
        }
        
        // Process the payment
        return $this->processSuccessfulPayment($order, $resource, 'paypal.checkout');
    }
    
    /**
     * Handle PAYMENT.CAPTURE.DENIED event
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentCaptureDenied(array $payload): Response
    {
        $resource = $payload['resource'] ?? [];
        $customId = $resource['custom_id'] ?? null;
        
        if ($customId) {
            $order = Order::where('order_reference_id', $customId)->first();
            
            if ($order) {
                $order->payment_status = 'failed';
                $order->save();
                
                Log::info('PayPal payment capture denied', [
                    'order_reference_id' => $customId,
                ]);
            }
        }
        
        return new Response('Payment denial recorded', 200);
    }
    
    /**
     * Handle PAYMENT.CAPTURE.REFUNDED event
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handlePaymentCaptureRefunded(array $payload): Response
    {
        $resource = $payload['resource'] ?? [];
        $captureId = $resource['id'] ?? null;
        
        if (!$captureId) {
            return new Response('Missing capture ID', 400);
        }
        
        // Find the payment by transaction ID
        $payment = Payment::where('transaction_id', $captureId)->first();
        
        if ($payment) {
            $payment->status = 'refunded';
            $payment->payment_data = array_merge(
                $payment->payment_data ?? [],
                ['refund_data' => $resource]
            );
            $payment->save();
            
            Log::info('PayPal payment refunded', [
                'payment_id' => $payment->id,
                'transaction_id' => $captureId,
            ]);
        }
        
        return new Response('Refund processed', 200);
    }
    
    /**
     * Handle CUSTOMER.DISPUTE.CREATED event
     *
     * @param array $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerDisputeCreated(array $payload): Response
    {
        $resource = $payload['resource'] ?? [];
        $disputeId = $resource['dispute_id'] ?? null;
        
        Log::warning('PayPal dispute created', [
            'dispute_id' => $disputeId,
            'reason' => $resource['reason'] ?? 'unknown',
        ]);
        
        // In production, you might want to:
        // - Send notification to admin
        // - Put order on hold
        // - Update order status
        
        return new Response('Dispute recorded', 200);
    }
    
    /**
     * Process a successful payment
     *
     * @param Order $order
     * @param array $data
     * @param string $source
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function processSuccessfulPayment(Order $order, array $data, string $source): Response
    {
        try {
            // Extract payment details
            $transactionId = $data['id'] ?? $data['paypal_order_id'] ?? uniqid('PAYPAL-');
            $amount = $data['amount']['value'] ?? $order->amount;
            $currency = strtoupper($data['amount']['currency_code'] ?? $order->currency ?? 'USD');
            
            // Update order
            $order->transaction_id = $transactionId;
            $order->payment_amount = $amount;
            $order->payment_currency = $currency;
            $order->is_paid = 1;
            $order->payment_status = 'completed';
            $order->payment_data = $data;
            $order->save();
            
            // Create payment record
            $payment = Payment::firstOrCreate(
                [
                    'transaction_id' => $transactionId,
                    'rel_type' => morph_name(Order::class),
                    'rel_id' => $order->id,
                ],
                [
                    'amount' => $amount,
                    'currency' => $currency,
                    'status' => 'completed',
                    'payment_provider' => 'paypal',
                    'payment_provider_id' => $this->getPayPalProviderId(),
                    'payment_data' => $data,
                ]
            );
            
            // Fire event
            event(new PaymentWasProcessed($payment));
            
            Log::info('PayPal payment processed successfully', [
                'order_reference_id' => $order->order_reference_id,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'source' => $source,
            ]);
            
            return new Response('Payment processed successfully', 200);
            
        } catch (\Exception $e) {
            Log::error('Error processing PayPal payment', [
                'order_reference_id' => $order->order_reference_id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return new Response('Error processing payment: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Get PayPal provider ID from database
     *
     * @return int|null
     */
    protected function getPayPalProviderId(): ?int
    {
        $provider = PaymentProvider::where('provider', 'paypal')
            ->where('is_active', 1)
            ->first();
        
        return $provider ? $provider->id : null;
    }
}
