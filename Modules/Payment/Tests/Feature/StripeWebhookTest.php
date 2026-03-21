<?php

namespace Modules\Payment\Tests\Feature;

use Illuminate\Support\Facades\Log;
use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    protected PaymentProvider $stripeProvider;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Stripe payment provider for testing
        $this->stripeProvider = PaymentProvider::create([
            'name' => 'Stripe Test',
            'provider' => 'stripe',
            'is_active' => 1,
            'settings' => [
                'publishable_key' => 'pk_test_' . uniqid(),
                'secret_key' => 'sk_test_' . uniqid(),
                'webhook_secret' => 'whsec_' . uniqid(),
                'payment_method' => 'checkout',
                'automatic_capture' => true,
            ],
        ]);
    }

    public function test_webhook_endpoint_is_accessible_without_csrf(): void
    {
        $payload = $this->createCheckoutSessionCompletedPayload();

        $response = $this->postJson('payment/stripe/webhook', $payload);

        // Should not get 419 (CSRF token mismatch)
        $this->assertNotEquals(419, $response->getStatusCode());
    }

    public function test_webhook_handles_invalid_payload(): void
    {
        $response = $this->postJson('payment/stripe/webhook', ['invalid' => 'data']);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_webhook_processes_checkout_session_completed(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_provider' => 'stripe',
            'payment_provider_id' => $this->stripeProvider->id,
            'is_completed' => true,
        ]);

        $payload = $this->createCheckoutSessionCompletedPayload([
            'client_reference_id' => $order->order_reference_id,
            'payment_status' => 'paid',
            'amount_total' => 9999,
            'currency' => 'usd',
            'id' => 'cs_test_' . uniqid(),
            'payment_intent' => 'pi_test_' . uniqid(),
        ]);

        $response = $this->postJson('payment/stripe/webhook', $payload);

        $this->assertEquals(200, $response->getStatusCode());

        // Refresh order
        $order->refresh();

        // Verify order was updated
        $this->assertEquals(1, $order->is_paid);
        $this->assertEquals('completed', $order->payment_status);
        $this->assertNotNull($order->transaction_id);

        // Verify payment record was created
        $payment = Payment::where('rel_id', $order->id)
            ->where('rel_type', 'Modules\\Order\\Models\\Order')
            ->first();

        $this->assertNotNull($payment);
        $this->assertEquals('completed', $payment->status);
    }

    public function test_webhook_handles_checkout_session_not_paid(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 99.99,
            'currency' => 'USD',
            'payment_provider' => 'stripe',
            'payment_provider_id' => $this->stripeProvider->id,
        ]);

        $payload = $this->createCheckoutSessionCompletedPayload([
            'client_reference_id' => $order->order_reference_id,
            'payment_status' => 'unpaid',
        ]);

        $response = $this->postJson('payment/stripe/webhook', $payload);

        $this->assertEquals(200, $response->getStatusCode());

        // Refresh order - should not be marked as paid
        $order->refresh();
        $this->assertEquals(0, $order->is_paid);
    }

    public function test_webhook_handles_missing_order(): void
    {
        $payload = $this->createCheckoutSessionCompletedPayload([
            'client_reference_id' => 'NONEXISTENT-ORDER',
        ]);

        $response = $this->postJson('payment/stripe/webhook', $payload);

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_webhook_processes_payment_intent_succeeded(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 50.00,
            'currency' => 'EUR',
            'payment_provider' => 'stripe',
            'payment_provider_id' => $this->stripeProvider->id,
            'is_completed' => true,
        ]);

        $payload = $this->createPaymentIntentSucceededPayload([
            'id' => 'pi_test_' . uniqid(),
            'metadata' => [
                'order_reference_id' => $order->order_reference_id,
            ],
            'amount' => 5000,
            'currency' => 'eur',
            'client_secret' => 'pi_' . uniqid() . '_secret_' . uniqid(),
        ]);

        $response = $this->postJson('payment/stripe/webhook', $payload);

        $this->assertEquals(200, $response->getStatusCode());

        // Refresh order
        $order->refresh();

        // Verify order was updated
        $this->assertEquals(1, $order->is_paid);
        $this->assertEquals('completed', $order->payment_status);
    }

    public function test_webhook_handles_payment_intent_payment_failed(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 25.00,
            'currency' => 'USD',
            'payment_provider' => 'stripe',
            'payment_provider_id' => $this->stripeProvider->id,
            'payment_status' => 'pending',
        ]);

        $payload = $this->createPaymentIntentFailedPayload([
            'id' => 'pi_test_' . uniqid(),
            'metadata' => [
                'order_reference_id' => $order->order_reference_id,
            ],
            'last_payment_error' => [
                'message' => 'Your card was declined.',
            ],
        ]);

        $response = $this->postJson('payment/stripe/webhook', $payload);

        $this->assertEquals(200, $response->getStatusCode());

        // Refresh order
        $order->refresh();

        // Verify order was marked as failed
        $this->assertEquals('failed', $order->payment_status);
    }

    public function test_webhook_handles_payment_intent_canceled(): void
    {
        $order = Order::create([
            'order_reference_id' => 'TEST-' . uniqid(),
            'email' => 'test@example.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'amount' => 75.00,
            'currency' => 'USD',
            'payment_provider' => 'stripe',
            'payment_provider_id' => $this->stripeProvider->id,
            'payment_status' => 'pending',
        ]);

        $payload = $this->createPaymentIntentCanceledPayload([
            'id' => 'pi_test_' . uniqid(),
            'metadata' => [
                'order_reference_id' => $order->order_reference_id,
            ],
        ]);

        $response = $this->postJson('payment/stripe/webhook', $payload);

        $this->assertEquals(200, $response->getStatusCode());

        // Refresh order
        $order->refresh();

        // Verify order was marked as cancelled
        $this->assertEquals('cancelled', $order->payment_status);
    }

public function test_webhook_handles_charge_refunded(): void
{
// Create an existing payment with charge ID as transaction_id
$chargeId = 'ch_test_' . uniqid();
$order = Order::create([
'order_reference_id' => 'TEST-' . uniqid(),
'email' => 'test@example.com',
'first_name' => 'Test',
'last_name' => 'User',
'amount' => 100.00,
'currency' => 'USD',
'payment_provider' => 'stripe',
'payment_provider_id' => $this->stripeProvider->id,
'is_paid' => 1,
'payment_status' => 'completed',
'transaction_id' => $chargeId,
]);

Payment::create([
'rel_type' => 'Modules\\Order\\Models\\Order',
'rel_id' => $order->id,
'amount' => 100.00,
'currency' => 'USD',
'status' => 'completed',
'payment_provider' => 'stripe',
'payment_provider_id' => $this->stripeProvider->id,
'transaction_id' => $chargeId,
]);

$payload = $this->createChargeRefundedPayload([
'id' => $chargeId,
'payment_intent' => 'pi_test_' . uniqid(), // Charge webhook can have different PI
'refunds' => [
['id' => 're_test_' . uniqid(), 'amount' => 10000],
],
]);

$response = $this->postJson('payment/stripe/webhook', $payload);

$this->assertEquals(200, $response->getStatusCode());

// Verify payment was marked as refunded
$payment = Payment::where('transaction_id', $chargeId)->first();
$this->assertNotNull($payment);
$this->assertEquals('refunded', $payment->status);
}

    public function test_webhook_logs_unhandled_events(): void
    {
        Log::spy();

        $payload = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'invoice.created',
            'data' => [
                'object' => [
                    'id' => 'in_test_' . uniqid(),
                ],
            ],
        ];

        $response = $this->postJson('payment/stripe/webhook', $payload);

        $this->assertEquals(200, $response->getStatusCode());

        // Verify the event was logged as unhandled
        Log::shouldHaveReceived('info')
            ->with('Unhandled Stripe webhook event', ['type' => 'invoice.created']);
    }

    /**
     * Create a checkout.session.completed payload.
     */
    protected function createCheckoutSessionCompletedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_' . uniqid(),
                    'object' => 'checkout.session',
                    'payment_status' => 'paid',
                    'amount_total' => 9999,
                    'currency' => 'usd',
                    'client_reference_id' => 'TEST-ORDER',
                    'payment_intent' => 'pi_test_' . uniqid(),
                ],
            ],
        ];

        // Apply overrides to nested object
        if (isset($overrides['client_reference_id'])) {
            $defaults['data']['object']['client_reference_id'] = $overrides['client_reference_id'];
            unset($overrides['client_reference_id']);
        }
        if (isset($overrides['payment_status'])) {
            $defaults['data']['object']['payment_status'] = $overrides['payment_status'];
            unset($overrides['payment_status']);
        }
        if (isset($overrides['amount_total'])) {
            $defaults['data']['object']['amount_total'] = $overrides['amount_total'];
            unset($overrides['amount_total']);
        }
        if (isset($overrides['currency'])) {
            $defaults['data']['object']['currency'] = $overrides['currency'];
            unset($overrides['currency']);
        }
        if (isset($overrides['id'])) {
            $defaults['data']['object']['id'] = $overrides['id'];
            unset($overrides['id']);
        }
        if (isset($overrides['payment_intent'])) {
            $defaults['data']['object']['payment_intent'] = $overrides['payment_intent'];
            unset($overrides['payment_intent']);
        }

        return array_merge($defaults, $overrides);
    }

    /**
     * Create a payment_intent.succeeded payload.
     */
    protected function createPaymentIntentSucceededPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test_' . uniqid(),
                    'object' => 'payment_intent',
                    'status' => 'succeeded',
                    'amount' => 9999,
                    'currency' => 'usd',
                    'client_secret' => 'pi_test_secret_test',
                    'metadata' => [
                        'order_reference_id' => 'TEST-ORDER',
                    ],
                ],
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Create a payment_intent.payment_failed payload.
     */
    protected function createPaymentIntentFailedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'payment_intent.payment_failed',
            'data' => [
                'object' => [
                    'id' => 'pi_test_' . uniqid(),
                    'object' => 'payment_intent',
                    'status' => 'requires_payment_method',
                    'metadata' => [
                        'order_reference_id' => 'TEST-ORDER',
                    ],
                    'last_payment_error' => [
                        'code' => 'card_declined',
                        'message' => 'Your card was declined.',
                        'decline_code' => 'generic_decline',
                    ],
                ],
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Create a payment_intent.canceled payload.
     */
    protected function createPaymentIntentCanceledPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'payment_intent.canceled',
            'data' => [
                'object' => [
                    'id' => 'pi_test_' . uniqid(),
                    'object' => 'payment_intent',
                    'status' => 'canceled',
                    'metadata' => [
                        'order_reference_id' => 'TEST-ORDER',
                    ],
                ],
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Create a charge.refunded payload.
     */
    protected function createChargeRefundedPayload(array $overrides = []): array
    {
        $defaults = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'charge.refunded',
            'data' => [
                'object' => [
                    'id' => 'ch_test_' . uniqid(),
                    'object' => 'charge',
                    'payment_intent' => 'pi_test_' . uniqid(),
                    'amount_refunded' => 9999,
                    'refunds' => [
                        [
                            'id' => 're_test_' . uniqid(),
                            'object' => 'refund',
                            'amount' => 9999,
                            'status' => 'succeeded',
                        ],
                    ],
                ],
            ],
        ];

        return $this->mergePayloadData($defaults, $overrides);
    }

    /**
     * Merge payload data with overrides.
     */
    protected function mergePayloadData(array $defaults, array $overrides): array
    {
        if (isset($overrides['id'])) {
            $defaults['data']['object']['id'] = $overrides['id'];
            unset($overrides['id']);
        }
        if (isset($overrides['amount'])) {
            $defaults['data']['object']['amount'] = $overrides['amount'];
            unset($overrides['amount']);
        }
        if (isset($overrides['currency'])) {
            $defaults['data']['object']['currency'] = $overrides['currency'];
            unset($overrides['currency']);
        }
        if (isset($overrides['client_secret'])) {
            $defaults['data']['object']['client_secret'] = $overrides['client_secret'];
            unset($overrides['client_secret']);
        }
        if (isset($overrides['metadata'])) {
            $defaults['data']['object']['metadata'] = $overrides['metadata'];
            unset($overrides['metadata']);
        }
        if (isset($overrides['payment_intent'])) {
            $defaults['data']['object']['payment_intent'] = $overrides['payment_intent'];
            unset($overrides['payment_intent']);
        }
        if (isset($overrides['refunds'])) {
            $defaults['data']['object']['refunds'] = $overrides['refunds'];
            unset($overrides['refunds']);
        }
        if (isset($overrides['last_payment_error'])) {
            $defaults['data']['object']['last_payment_error'] = $overrides['last_payment_error'];
            unset($overrides['last_payment_error']);
        }

        return array_merge_recursive($defaults, $overrides);
    }
}
