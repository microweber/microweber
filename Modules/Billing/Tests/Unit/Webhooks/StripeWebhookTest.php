<?php

namespace Modules\Billing\Tests\Unit\Webhooks;

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Modules\Billing\Events\PaymentSucceeded;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Tests\Unit\BillingTestCase;
use PHPUnit\Framework\Attributes\Test;

class StripeWebhookTest extends BillingTestCase
{
    #[Test]
    public function it_processes_successful_payment_webhook()
    {
        Queue::fake();
        Event::fake();

        $user = User::factory()->create();

        $customer = $user->customer()->create([
            'stripe_id' => 'cus_test123it_processes_successful_payment_webhook',
            'active' => 1,
            'stripe_plan' => 'plan_test123it_processes_successful_payment_webhook',
            'trial_ends_at' => now()->addDays(14),
        ]);

        $subscription = Subscription::factory()->create([
            'customer_id' => $customer->id,
            'stripe_id' => 'cus_test123it_processes_successful_payment_webhook',
            'stripe_status' => 'active'
        ]);

        $payload = [
            'type' => 'invoice.payment_succeeded',
            'data' => [
                'object' => [
                    'subscription' => 'cus_test123it_processes_successful_payment_webhook',
                    'amount_paid' => 2999,
                    'currency' => 'usd'
                ]
            ]
        ];

        $response = $this->postJson('/webhooks/stripe', $payload, [
            'Stripe-Signature' => $this->generateStripeSignature($payload)
        ]);

        $response->assertStatus(200);
        Event::assertDispatched(PaymentSucceeded::class);
    }

    #[Test]
    public function it_rejects_invalid_webhook_signature()
    {
        $response = $this->postJson('/webhooks/stripe', [
            'type' => 'invoice.payment_succeeded'
        ], [
            'Stripe-Signature' => 'invalid_signature'
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function it_handles_subscription_cancellation()
    {
        $subscription = Subscription::factory()->create([
            'stripe_id' => 'sub_cancel123',
            'stripe_status' => 'active'
        ]);

        $payload = [
            'type' => 'customer.subscription.deleted',
            'data' => [
                'object' => [
                    'id' => 'sub_cancel123'
                ]
            ]
        ];

        $response = $this->postJson('/webhooks/stripe', $payload, [
            'Stripe-Signature' => $this->generateStripeSignature($payload)
        ]);

        $response->assertStatus(200);
        $this->assertEquals('canceled', $subscription->fresh()->status);
    }

    protected function generateStripeSignature(array $payload): string
    {
        // Mock signature generation for testing
        return 't=' . time() . ',v1=' . hash_hmac(
                'sha256',
                time() . '.' . json_encode($payload),
                'whsec_testsecret'
            );
    }
}
