<?php

namespace Modules\Payment\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use Tests\TestCase;

/**
 * PayPal Webhook Tests
 *
 * These tests mock PayPal's signature verification API to simulate
 * the real-world flow where PayPal sends signed webhooks.
 */
class PayPalWebhookTest extends TestCase
{
    protected PaymentProvider $paypalProvider;

    protected function setUp(): void
    {
        parent::setUp();

        // Deterministic fixture: getPayPalProvider() resolves the *first* active
        // paypal provider, so any row left behind by another test (payment_providers
        // is not reset between runs) would shadow ours. Clear the table first.
        PaymentProvider::query()->delete();

        // Create a PayPal payment provider for testing
        $this->paypalProvider = PaymentProvider::create([
            'name' => 'PayPal Test',
            'provider' => 'paypal',
            'is_active' => 1,
            'settings' => [
                'client_id' => 'test_client_' . uniqid(),
                'client_secret' => 'test_secret_' . uniqid(),
                'test_mode' => true,
                'webhook_id' => 'test_webhook_' . uniqid(),
            ],
        ]);

        // Mock PayPal API: access token + webhook verification returns SUCCESS
        Http::fake([
            'api-m.sandbox.paypal.com/v1/oauth2/token' => Http::response([
                'access_token' => 'test-access-token-' . uniqid(),
                'token_type' => 'Bearer',
                'expires_in' => 32400,
            ], 200),
            'api-m.sandbox.paypal.com/v1/notifications/verify-webhook-signature' => Http::response([
                'verification_status' => 'SUCCESS',
            ], 200),
        ]);
    }

    /**
     * Send a PayPal webhook request with valid signature headers.
     *
     * In production, PayPal adds these headers. We include them so the
     * controller's verification logic proceeds to call PayPal's API
     * (which is mocked to return SUCCESS).
     */
    protected function sendVerifiedPayPalWebhook(array $payload): \Illuminate\Testing\TestResponse
    {
        return $this->call(
            'POST',
            'payment/paypal/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_PAYPAL_TRANSMISSION_ID' => 'test-trans-' . uniqid(),
                'HTTP_PAYPAL_TRANSMISSION_TIME' => now()->toIso8601String(),
                'HTTP_PAYPAL_TRANSMISSION_SIG' => 'valid-test-sig-' . uniqid(),
                'HTTP_PAYPAL_CERT_URL' => 'https://api.sandbox.paypal.com/v1/notifications/certs/CERT-360caa42-fca2a784.pem',
                'HTTP_PAYPAL_AUTH_ALGO' => 'SHA256withRSA',
            ],
            json_encode($payload)
        );
    }

    public function test_webhook_endpoint_is_accessible_without_csrf(): void
    {
        $payload = $this->createPaymentCaptureCompletedPayload();

        $response = $this->sendVerifiedPayPalWebhook($payload);

        // Should not get 419 (CSRF token mismatch)
        $this->assertNotEquals(419, $response->getStatusCode());
    }

    public function test_webhook_rejects_unsigned_requests(): void
    {
        // Sending without PayPal signature headers should be rejected
        $response = $this->postJson('payment/paypal/webhook', [
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
            'resource' => [],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function test_webhook_handles_invalid_payload(): void
    {
        $response = $this->sendVerifiedPayPalWebhook(['invalid' => 'data']);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_webhook_processes_payment_capture_completed(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_provider' => 'paypal',
            'payment_provider_id' => $this->paypalProvider->id,
            'is_completed' => true,
        ]);

        $payload = $this->createPaymentCaptureCompletedPayload([
            'custom_id' => $order->order_reference_id,
            'invoice_id' => $order->order_reference_id,
            'amount' => [
                'value' => '99.99',
                'currency_code' => 'USD',
            ],
            'id' => 'PAYID-' . uniqid(),
        ]);

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(200, $response->getStatusCode());

        // Refresh order
        $order->refresh();

        // Verify order was updated
        $this->assertEquals(1, $order->is_paid);
        $this->assertEquals('completed', $order->payment_status);
        $this->assertNotNull($order->transaction_id);

        // Verify payment record was created
        $payment = Payment::where('rel_id', $order->id)
            ->where('rel_type', morph_name(Order::class))
            ->first();

        $this->assertNotNull($payment);
        $this->assertEquals('completed', $payment->status);
    }

    public function test_webhook_handles_checkout_order_completed(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 50.00,
            'currency' => 'EUR',
            'payment_provider' => 'paypal',
            'payment_provider_id' => $this->paypalProvider->id,
            'is_completed' => true,
        ]);

        $payload = $this->createCheckoutOrderCompletedPayload([
            'purchase_units' => [
                [
                    'custom_id' => $order->order_reference_id,
                    'invoice_id' => $order->order_reference_id,
                    'amount' => [
                        'value' => '50.00',
                        'currency_code' => 'EUR',
                    ],
                ],
            ],
            'id' => 'ORDER-' . uniqid(),
        ]);

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(200, $response->getStatusCode());

        // Refresh order
        $order->refresh();

        // Verify order was updated
        $this->assertEquals(1, $order->is_paid);
        $this->assertEquals('completed', $order->payment_status);
    }

    public function test_webhook_handles_checkout_order_approved(): void
    {
        $orderReferenceId = 'TEST-' . uniqid();

        Order::create([
            'order_reference_id' => $orderReferenceId,
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 75.00,
            'currency' => 'USD',
            'payment_provider' => 'paypal',
            'payment_provider_id' => $this->paypalProvider->id,
            'is_completed' => true,
            'is_paid' => 0,
        ]);

        $payload = $this->createCheckoutOrderApprovedPayload([
            'purchase_units' => [
                [
                    'custom_id' => $orderReferenceId,
                    'invoice_id' => $orderReferenceId,
                ],
            ],
            'id' => 'ORDER-' . uniqid(),
        ]);

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_webhook_handles_missing_order(): void
    {
        $payload = $this->createPaymentCaptureCompletedPayload([
            'custom_id' => 'NONEXISTENT-ORDER',
            'invoice_id' => 'NONEXISTENT-ORDER',
        ]);

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_webhook_handles_payment_capture_denied(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 25.00,
            'currency' => 'USD',
            'payment_provider' => 'paypal',
            'payment_provider_id' => $this->paypalProvider->id,
            'payment_status' => 'pending',
        ]);

        $payload = $this->createPaymentCaptureDeniedPayload([
            'custom_id' => $order->order_reference_id,
            'id' => 'PAYID-' . uniqid(),
        ]);

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(200, $response->getStatusCode());

        $order->refresh();
        $this->assertEquals('failed', $order->payment_status);
    }

    public function test_webhook_processes_payment_capture_refunded(): void
    {
        $captureId = 'PAYID-' . uniqid();
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 100.00,
            'currency' => 'USD',
            'payment_provider' => 'paypal',
            'payment_provider_id' => $this->paypalProvider->id,
            'is_paid' => 1,
            'payment_status' => 'completed',
            'transaction_id' => $captureId,
        ]);

        Payment::create([
            'rel_type' => morph_name(Order::class),
            'rel_id' => $order->id,
            'amount' => 100.00,
            'currency' => 'USD',
            'status' => 'completed',
            'payment_provider' => 'paypal',
            'payment_provider_id' => $this->paypalProvider->id,
            'transaction_id' => $captureId,
        ]);

        $payload = $this->createPaymentCaptureRefundedPayload([
            'id' => $captureId,
            'amount' => [
                'value' => '100.00',
                'currency_code' => 'USD',
            ],
        ]);

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(200, $response->getStatusCode());

        $payment = Payment::where('transaction_id', $captureId)->first();
        $this->assertNotNull($payment);
        $this->assertEquals('refunded', $payment->status);
    }

    public function test_webhook_handles_customer_dispute_created(): void
    {
        Log::spy();

        $payload = $this->createCustomerDisputeCreatedPayload([
            'dispute_id' => 'PP-D-' . uniqid(),
            'reason' => 'MERCHANDISE_OR_SERVICE_NOT_RECEIVED',
        ]);

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(200, $response->getStatusCode());

        Log::shouldHaveReceived('warning')
            ->with('PayPal dispute created', \Mockery::any());
    }

    public function test_webhook_logs_unhandled_events(): void
    {
        Log::spy();

        $payload = [
            'id' => 'WH-' . uniqid(),
            'event_version' => '1.0',
            'create_time' => now()->toIso8601String(),
            'resource_type' => 'authorization',
            'event_type' => 'PAYMENT.AUTHORIZATION.CREATED',
            'summary' => 'A payment authorization was created',
            'resource' => [
                'id' => 'AUTH-' . uniqid(),
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '99.99',
                ],
            ],
        ];

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(200, $response->getStatusCode());

        Log::shouldHaveReceived('info')
            ->with('Unhandled PayPal webhook event', ['type' => 'PAYMENT.AUTHORIZATION.CREATED']);
    }

    public function test_webhook_handles_already_processed_payments(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_provider' => 'paypal',
            'payment_provider_id' => $this->paypalProvider->id,
            'is_paid' => 1,
            'payment_status' => 'completed',
        ]);

        $payload = $this->createPaymentCaptureCompletedPayload([
            'custom_id' => $order->order_reference_id,
            'invoice_id' => $order->order_reference_id,
            'id' => 'PAYID-' . uniqid(),
        ]);

        $response = $this->sendVerifiedPayPalWebhook($payload);

        $this->assertEquals(200, $response->getStatusCode());

        $content = $response->getContent();
        $this->assertTrue(
            str_contains($content, 'already') || str_contains($content, 'success'),
            "Response should indicate idempotent handling: {$content}"
        );
    }

    /**
     * Create a PAYMENT.CAPTURE.COMPLETED payload
     */
    protected function createPaymentCaptureCompletedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'WH-' . uniqid(),
            'event_version' => '1.0',
            'create_time' => now()->toIso8601String(),
            'resource_type' => 'capture',
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
            'summary' => 'A payment capture was completed',
            'resource' => [
                'id' => 'PAYID-' . uniqid(),
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '99.99',
                ],
                'custom_id' => 'TEST-ORDER',
                'invoice_id' => 'TEST-ORDER',
                'status' => 'COMPLETED',
                'create_time' => now()->toIso8601String(),
                'update_time' => now()->toIso8601String(),
                'seller_protection' => [
                    'status' => 'ELIGIBLE',
                ],
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Create a CHECKOUT.ORDER.COMPLETED payload
     */
    protected function createCheckoutOrderCompletedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'WH-' . uniqid(),
            'event_version' => '1.0',
            'create_time' => now()->toIso8601String(),
            'resource_type' => 'checkout-order',
            'event_type' => 'CHECKOUT.ORDER.COMPLETED',
            'summary' => 'An order was completed',
            'resource' => [
                'id' => 'ORDER-' . uniqid(),
                'status' => 'COMPLETED',
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => 'default',
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => '99.99',
                        ],
                        'custom_id' => 'TEST-ORDER',
                        'invoice_id' => 'TEST-ORDER',
                    ],
                ],
                'create_time' => now()->toIso8601String(),
                'update_time' => now()->toIso8601String(),
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Create a CHECKOUT.ORDER.APPROVED payload
     */
    protected function createCheckoutOrderApprovedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'WH-' . uniqid(),
            'event_version' => '1.0',
            'create_time' => now()->toIso8601String(),
            'resource_type' => 'checkout-order',
            'event_type' => 'CHECKOUT.ORDER.APPROVED',
            'summary' => 'An order was approved',
            'resource' => [
                'id' => 'ORDER-' . uniqid(),
                'status' => 'APPROVED',
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'reference_id' => 'default',
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => '99.99',
                        ],
                        'custom_id' => 'TEST-ORDER',
                        'invoice_id' => 'TEST-ORDER',
                    ],
                ],
                'create_time' => now()->toIso8601String(),
                'update_time' => now()->toIso8601String(),
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Create a PAYMENT.CAPTURE.DENIED payload
     */
    protected function createPaymentCaptureDeniedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'WH-' . uniqid(),
            'event_version' => '1.0',
            'create_time' => now()->toIso8601String(),
            'resource_type' => 'capture',
            'event_type' => 'PAYMENT.CAPTURE.DENIED',
            'summary' => 'A payment capture was denied',
            'resource' => [
                'id' => 'PAYID-' . uniqid(),
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '25.00',
                ],
                'custom_id' => 'TEST-ORDER',
                'status' => 'DENIED',
                'create_time' => now()->toIso8601String(),
                'update_time' => now()->toIso8601String(),
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Create a PAYMENT.CAPTURE.REFUNDED payload
     */
    protected function createPaymentCaptureRefundedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'WH-' . uniqid(),
            'event_version' => '1.0',
            'create_time' => now()->toIso8601String(),
            'resource_type' => 'capture',
            'event_type' => 'PAYMENT.CAPTURE.REFUNDED',
            'summary' => 'A payment capture was refunded',
            'resource' => [
                'id' => 'PAYID-' . uniqid(),
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '100.00',
                ],
                'custom_id' => 'TEST-ORDER',
                'status' => 'REFUNDED',
                'amount_refunded' => [
                    'currency_code' => 'USD',
                    'value' => '100.00',
                ],
                'refunds' => [
                    [
                        'id' => 'REFUND-' . uniqid(),
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => '100.00',
                        ],
                        'status' => 'COMPLETED',
                    ],
                ],
                'create_time' => now()->toIso8601String(),
                'update_time' => now()->toIso8601String(),
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Create a CUSTOMER.DISPUTE.CREATED payload
     */
    protected function createCustomerDisputeCreatedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'WH-' . uniqid(),
            'event_version' => '1.0',
            'create_time' => now()->toIso8601String(),
            'resource_type' => 'dispute',
            'event_type' => 'CUSTOMER.DISPUTE.CREATED',
            'summary' => 'A customer dispute was created',
            'resource' => [
                'dispute_id' => 'PP-D-' . uniqid(),
                'create_time' => now()->toIso8601String(),
                'update_time' => now()->toIso8601String(),
                'reason' => 'MERCHANDISE_OR_SERVICE_NOT_RECEIVED',
                'status' => 'OPEN',
                'dispute_amount' => [
                    'currency_code' => 'USD',
                    'value' => '99.99',
                ],
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Merge payload data with overrides
     */
    protected function mergePayloadData(array $defaults, array $overrides): array
    {
        if (isset($overrides['id'])) {
            $defaults['resource']['id'] = $overrides['id'];
            unset($overrides['id']);
        }
        if (isset($overrides['amount'])) {
            $defaults['resource']['amount'] = $overrides['amount'];
            unset($overrides['amount']);
        }
        if (isset($overrides['custom_id'])) {
            $defaults['resource']['custom_id'] = $overrides['custom_id'];
            unset($overrides['custom_id']);
        }
        if (isset($overrides['invoice_id'])) {
            $defaults['resource']['invoice_id'] = $overrides['invoice_id'];
            unset($overrides['invoice_id']);
        }
        if (isset($overrides['dispute_id'])) {
            $defaults['resource']['dispute_id'] = $overrides['dispute_id'];
            unset($overrides['dispute_id']);
        }
        if (isset($overrides['reason'])) {
            $defaults['resource']['reason'] = $overrides['reason'];
            unset($overrides['reason']);
        }
        if (isset($overrides['refunds'])) {
            $defaults['resource']['refunds'] = $overrides['refunds'];
            unset($overrides['refunds']);
        }
        if (isset($overrides['purchase_units'])) {
            $defaults['resource']['purchase_units'] = $overrides['purchase_units'];
            unset($overrides['purchase_units']);
        }

        return array_merge_recursive($defaults, $overrides);
    }
}
