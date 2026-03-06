<?php

namespace Tests\Feature\Regression;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\WebhookLog;
use Modules\Customer\Models\Customer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Full Regression Test Suite - Billing & Subscriptions
 *
 * End-to-end testing of the complete billing flow including:
 * - Subscription creation
 * - Webhook handling
 * - Subscription cancellation
 * - Payment processing
 *
 * @covers \Modules\Billing
 */
class BillingRegressionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->customer = User::factory()->create(['is_admin' => false]);

        // Mock Stripe API responses
        Http::fake([
            'api.stripe.com/v1/customers' => Http::response([
                'id' => 'cus_test_' . uniqid(),
                'object' => 'customer',
                'email' => $this->customer->email,
            ], 200),
            'api.stripe.com/v1/subscriptions' => Http::response([
                'id' => 'sub_test_' . uniqid(),
                'object' => 'subscription',
                'status' => 'active',
                'current_period_start' => time(),
                'current_period_end' => time() + (30 * 24 * 60 * 60),
            ], 200),
            'api.stripe.com/v1/checkout/sessions' => Http::response([
                'id' => 'cs_test_' . uniqid(),
                'object' => 'checkout.session',
                'url' => 'https://checkout.stripe.com/test',
            ], 200),
        ]);
    }

    /**
     * Test complete subscription flow: create → webhook → cancel
     */
    #[Test]
    public function test_complete_subscription_lifecycle(): void
    {
        $this->actingAs($this->customer);

        // Step 1: Create a subscription plan
        $plan = $this->createTestPlan();

        // Step 2: Subscribe to the plan
        $subscription = $this->subscribeToPlan($plan);

        // Step 3: Verify subscription is active
        $this->assertTrue($subscription->isActive());
        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'stripe_status' => 'active',
        ]);

        // Step 4: Simulate webhook - invoice.paid
        $this->simulateWebhook('invoice.paid', [
            'data' => [
                'object' => [
                    'subscription' => $subscription->stripe_id,
                    'customer' => $subscription->customer->stripe_id,
                    'amount_paid' => $plan->price * 100,
                    'currency' => 'usd',
                ],
            ],
        ]);

        // Step 5: Verify webhook was logged
        $this->assertDatabaseHas('webhook_logs', [
            'event_type' => 'invoice.paid',
            'subscription_id' => $subscription->id,
        ]);

        // Step 6: Cancel subscription
        $cancelResponse = $this->post('/billing/subscriptions/' . $subscription->id . '/cancel');
        $cancelResponse->assertStatus(200)->assertJson(['success' => true]);

        // Step 7: Verify cancellation
        $subscription->refresh();
        $this->assertEquals('canceled', $subscription->stripe_status);
        $this->assertFalse($subscription->isActive());
    }

    /**
     * Test subscription with trial period
     */
    #[Test]
    public function test_subscription_with_trial_period(): void
    {
        $this->actingAs($this->customer);

        $plan = SubscriptionPlan::factory()->create([
            'name' => 'Pro Plan with Trial',
            'price' => 29.99,
            'billing_interval' => 'monthly',
            'trial_days' => 14,
            'currency' => 'USD',
        ]);

        $subscription = $this->subscribeToPlan($plan, ['trial' => true]);

        $this->assertEquals('trialing', $subscription->stripe_status);
        $this->assertTrue($subscription->trial_ends_at->isFuture());
        $this->assertEquals(14, now()->diffInDays($subscription->trial_ends_at));
    }

    /**
    * Test webhook signature verification
     */
    #[Test]
    public function test_webhook_signature_verification(): void
    {
        $payload = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'customer.subscription.updated',
            'data' => [
                'object' => [
                    'id' => 'sub_test_' . uniqid(),
                    'status' => 'active',
                ],
            ],
        ];

        // Test without signature (should fail)
        $response = $this->postJson('/webhooks/stripe', $payload);
        $response->assertStatus(400);

        // Test with valid signature
        $signature = $this->generateStripeSignature($payload);
        $response = $this->postJson('/webhooks/stripe', $payload, [
            'Stripe-Signature' => $signature,
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test subscription upgrade/downgrade
     */
    #[Test]
    public function test_subscription_plan_change(): void
    {
        $this->actingAs($this->customer);

        $basicPlan = SubscriptionPlan::factory()->create([
            'name' => 'Basic Plan',
            'price' => 9.99,
            'billing_interval' => 'monthly',
        ]);

        $proPlan = SubscriptionPlan::factory()->create([
            'name' => 'Pro Plan',
            'price' => 29.99,
            'billing_interval' => 'monthly',
        ]);

        // Subscribe to basic plan
        $subscription = $this->subscribeToPlan($basicPlan);
        $this->assertEquals($basicPlan->id, $subscription->subscription_plan_id);

        // Upgrade to pro plan
        $upgradeResponse = $this->post('/billing/subscriptions/' . $subscription->id . '/change-plan', [
            'plan_id' => $proPlan->id,
        ]);
        $upgradeResponse->assertStatus(200)->assertJson(['success' => true]);

        $subscription->refresh();
        $this->assertEquals($proPlan->id, $subscription->subscription_plan_id);
    }

    /**
     * Test failed payment webhook handling
     */
    #[Test]
    public function test_failed_payment_webhook_handling(): void
    {
        $this->actingAs($this->customer);

        $plan = $this->createTestPlan();
        $subscription = $this->subscribeToPlan($plan);

        // Simulate payment failure webhook
        $this->simulateWebhook('invoice.payment_failed', [
            'data' => [
                'object' => [
                    'subscription' => $subscription->stripe_id,
                    'customer' => $subscription->customer->stripe_id,
                    'next_payment_attempt' => time() + (24 * 60 * 60),
                ],
            ],
        ]);

        $subscription->refresh();
        $this->assertEquals('past_due', $subscription->stripe_status);

        // Verify notification was sent
        $this->assertDatabaseHas('notifications', [
            'type' => 'Modules\Billing\Notifications\PaymentFailedNotification',
            'notifiable_id' => $this->customer->id,
        ]);
    }

    /**
     * Test subscription renewal
     */
    #[Test]
    public function test_subscription_renewal(): void
    {
        $this->actingAs($this->customer);

        $plan = $this->createTestPlan();
        $subscription = $this->subscribeToPlan($plan);

        $originalEndDate = $subscription->ends_at;

        // Simulate successful payment webhook (renewal)
        $this->simulateWebhook('invoice.paid', [
            'data' => [
                'object' => [
                    'subscription' => $subscription->stripe_id,
                    'customer' => $subscription->customer->stripe_id,
                    'billing_reason' => 'subscription_cycle',
                ],
            ],
        ]);

        $subscription->refresh();
        $this->assertTrue($subscription->ends_at->greaterThan($originalEndDate));
        $this->assertEquals('active', $subscription->stripe_status);
    }

    /**
     * Test admin can view all subscriptions
     */
    #[Test]
    public function test_admin_can_manage_all_subscriptions(): void
    {
        $this->actingAs($this->admin);

        // Create multiple subscriptions
        Subscription::factory()->count(5)->create();

        $response = $this->get('/admin/subscriptions');
        $response->assertStatus(200);

        // Test admin can cancel any subscription
        $subscription = Subscription::first();
        $response = $this->post('/admin/subscriptions/' . $subscription->id . '/cancel');
        $response->assertStatus(200);

        $subscription->refresh();
        $this->assertEquals('canceled', $subscription->stripe_status);
    }

    /**
     * Test subscription stats calculation
     */
    #[Test]
    public function test_subscription_stats_calculation(): void
    {
        $this->actingAs($this->admin);

        // Create subscriptions with various statuses
        Subscription::factory()->count(3)->create(['stripe_status' => 'active']);
        Subscription::factory()->count(2)->create(['stripe_status' => 'canceled']);
        Subscription::factory()->count(1)->create(['stripe_status' => 'trialing']);

        $response = $this->get('/admin/billing/stats');
        $response->assertStatus(200)->assertJson([
            'active_count' => 3,
            'canceled_count' => 2,
            'trialing_count' => 1,
        ]);
    }

    /**
     * Create a test subscription plan
     */
    private function createTestPlan(array $attributes = []): SubscriptionPlan
    {
        $defaults = [
            'name' => 'Test Plan ' . uniqid(),
            'price' => 19.99,
            'billing_interval' => 'monthly',
            'currency' => 'USD',
            'is_active' => true,
        ];

        return SubscriptionPlan::factory()->create(array_merge($defaults, $attributes));
    }

    /**
     * Subscribe to a plan
     */
    private function subscribeToPlan(SubscriptionPlan $plan, array $options = []): Subscription
    {
        $customer = $this->customer->customer()->firstOrCreate([
            'user_id' => $this->customer->id,
        ], [
            'stripe_id' => 'cus_test_' . uniqid(),
            'active' => true,
        ]);

        $subscriptionData = [
            'user_id' => $this->customer->id,
            'customer_id' => $customer->id,
            'subscription_plan_id' => $plan->id,
            'stripe_id' => 'sub_test_' . uniqid(),
            'stripe_status' => $options['trial'] ?? false ? 'trialing' : 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ];

        if ($options['trial'] ?? false) {
            $subscriptionData['trial_ends_at'] = now()->addDays($plan->trial_days ?? 14);
        }

        return Subscription::create($subscriptionData);
    }

    /**
     * Simulate Stripe webhook
     */
    private function simulateWebhook(string $eventType, array $data): void
    {
        $payload = [
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => $eventType,
            'created' => time(),
        ] + $data;

        $signature = $this->generateStripeSignature($payload);

        $this->postJson('/webhooks/stripe', $payload, [
            'Stripe-Signature' => $signature,
        ]);
    }

    /**
     * Generate Stripe webhook signature
     */
    private function generateStripeSignature(array $payload): string
    {
        $timestamp = time();
        $payloadJson = json_encode($payload);
        $secret = config('services.stripe.webhook_secret', 'whsec_test');

        $signedPayload = $timestamp . '.' . $payloadJson;
        $signature = hash_hmac('sha256', $signedPayload, $secret);

        return 't=' . $timestamp . ',v1=' . $signature;
    }
}
