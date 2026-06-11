<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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
 *
 * All webhooks are verified using PayPal's signature verification API
 * before any payment state changes are applied.
 */
class PayPalWebhookController extends Controller
{
    /**
     * Handle incoming PayPal webhook requests.
     *
     * Signature verification is MANDATORY. If webhook verification fails
     * or is not configured, the request is rejected.
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

        // SECURITY: Verify the webhook signature before processing
        $verificationResult = $this->verifyWebhookSignature($request);
        if ($verificationResult !== true) {
            return $verificationResult;
        }

        // SECURITY: Idempotency — reject duplicate event IDs using a nonce cache
        $eventId = $payload['id'] ?? null;
        if ($eventId) {
            $nonceKey = 'paypal_webhook_nonce:' . $eventId;
            if (Cache::has($nonceKey)) {
                Log::info('PayPal webhook duplicate event rejected', ['event_id' => $eventId]);
                return new Response('Event already processed', 200);
            }
            // Store nonce for 24 hours to prevent replay attacks
            Cache::put($nonceKey, true, now()->addHours(24));
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
     * Verify PayPal webhook signature using PayPal's verification API.
     *
     * PayPal signs every webhook notification. We verify by sending the
     * headers and body to PayPal's /v1/notifications/verify-webhook-signature
     * endpoint. This prevents attackers from crafting fake webhook payloads.
     *
     * @param \Illuminate\Http\Request $request
     * @return true|\Symfony\Component\HttpFoundation\Response
     */
    protected function verifyWebhookSignature(Request $request)
    {
        $provider = $this->getPayPalProvider();

        if (!$provider) {
            Log::critical('PayPal webhook rejected: no active PayPal payment provider configured');
            return new Response('PayPal not configured', 403);
        }

        $webhookId = $provider->settings['webhook_id'] ?? null;

        // SECURITY: Reject if webhook_id is not configured — without it we cannot verify
        if (!$webhookId) {
            Log::critical('PayPal webhook rejected: webhook_id is not configured. Set it in PayPal payment provider settings.');
            return new Response('Webhook verification not configured', 403);
        }

        // Get the required PayPal signature headers
        $transmissionId = $request->header('PAYPAL-TRANSMISSION-ID');
        $transmissionTime = $request->header('PAYPAL-TRANSMISSION-TIME');
        $transmissionSig = $request->header('PAYPAL-TRANSMISSION-SIG');
        $certUrl = $request->header('PAYPAL-CERT-URL');
        $authAlgo = $request->header('PAYPAL-AUTH-ALGO');

        if (!$transmissionId || !$transmissionTime || !$transmissionSig || !$certUrl || !$authAlgo) {
            Log::warning('PayPal webhook rejected: missing signature headers', [
                'ip' => $request->ip(),
                'has_transmission_id' => (bool) $transmissionId,
                'has_transmission_sig' => (bool) $transmissionSig,
            ]);
            return new Response('Missing PayPal signature headers', 403);
        }

        // SECURITY: Validate cert URL is from PayPal to prevent SSRF
        $parsedCertUrl = parse_url($certUrl);
        $allowedHosts = ['api.paypal.com', 'api.sandbox.paypal.com'];
        if (!isset($parsedCertUrl['host']) || !in_array($parsedCertUrl['host'], $allowedHosts)) {
            Log::warning('PayPal webhook rejected: suspicious cert URL', [
                'cert_url' => $certUrl,
                'ip' => $request->ip(),
            ]);
            return new Response('Invalid certificate URL', 403);
        }

        try {
            $accessToken = $this->getPayPalAccessToken($provider);
            if (!$accessToken) {
                Log::error('PayPal webhook verification failed: could not obtain access token');
                return new Response('Verification service unavailable', 503);
            }

            $testMode = $provider->settings['test_mode'] ?? true;
            $baseUrl = $testMode
                ? 'https://api-m.sandbox.paypal.com'
                : 'https://api-m.paypal.com';

            $response = Http::withToken($accessToken)
                ->timeout(10)
                ->post("{$baseUrl}/v1/notifications/verify-webhook-signature", [
                    'auth_algo' => $authAlgo,
                    'cert_url' => $certUrl,
                    'transmission_id' => $transmissionId,
                    'transmission_sig' => $transmissionSig,
                    'transmission_time' => $transmissionTime,
                    'webhook_id' => $webhookId,
                    'webhook_event' => $request->all(),
                ]);

            if (!$response->successful()) {
                Log::warning('PayPal webhook signature verification API call failed', [
                    'status' => $response->status(),
                    'ip' => $request->ip(),
                ]);
                return new Response('Signature verification failed', 403);
            }

            $verificationStatus = $response->json('verification_status');
            if ($verificationStatus !== 'SUCCESS') {
                Log::warning('PayPal webhook signature verification failed', [
                    'verification_status' => $verificationStatus,
                    'ip' => $request->ip(),
                ]);
                return new Response('Invalid signature', 403);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('PayPal webhook signature verification error', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);
            return new Response('Signature verification error', 503);
        }
    }

    /**
     * Get PayPal access token for API calls.
     *
     * @param PaymentProvider $provider
     * @return string|null
     */
    protected function getPayPalAccessToken(PaymentProvider $provider): ?string
    {
        $cacheKey = 'paypal_access_token:' . $provider->id;

        return Cache::remember($cacheKey, now()->addMinutes(55), function () use ($provider) {
            $settings = $provider->settings;
            $clientId = $settings['client_id'] ?? null;
            $clientSecret = $settings['client_secret'] ?? null;

            if (!$clientId || !$clientSecret) {
                return null;
            }

            $testMode = $settings['test_mode'] ?? true;
            $baseUrl = $testMode
                ? 'https://api-m.sandbox.paypal.com'
                : 'https://api-m.paypal.com';

            $response = Http::withBasicAuth($clientId, $clientSecret)
                ->asForm()
                ->timeout(10)
                ->post("{$baseUrl}/v1/oauth2/token", [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            return null;
        });
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
        $provider = $this->getPayPalProvider();
        return $provider ? $provider->id : null;
    }

    /**
     * Get the active PayPal payment provider.
     *
     * @return PaymentProvider|null
     */
    protected function getPayPalProvider(): ?PaymentProvider
    {
        return PaymentProvider::where('provider', 'paypal')
            ->where('is_active', 1)
            ->first();
    }
}
