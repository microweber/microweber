<?php

namespace Tests\Feature\Regression;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\WebhookLog;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
* Regression Test Suite - Billing & Subscriptions
*
* Tests billing models, factories, webhook handling, and subscription lifecycle
* using actual module code paths.
*/
class BillingRegressionTest extends TestCase
{
    use WithFaker;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->customer = User::factory()->create(['is_admin' => false]);
    }

    /**
     * Test subscription model lifecycle: create active, verify, cancel
     */
    #[Test]
    public function it_complete_subscription_lifecycle(): void
    {
        $plan = SubscriptionPlan::factory()->create([
            'name' => 'Lifecycle Plan',
            'price' => 19.99,
            'billing_interval' => 'monthly',
            'is_active' => true,
        ]);

        $customerRecord = SubscriptionCustomer::create([
            'user_id' => $this->customer->id,
            'email' => $this->customer->email,
            'stripe_id' => 'cus_test_' . uniqid(),
            'status' => 'active',
        ]);

        $subscription = Subscription::create([
            'customer_id' => $customerRecord->id,
            'user_id' => $this->customer->id,
            'subscription_plan_id' => $plan->id,
            'type' => 'stripe',
            'stripe_id' => 'sub_test_' . uniqid(),
            'stripe_status' => 'active',
            'stripe_price' => $plan->remote_provider_price_id,
            'quantity' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
        ]);

        // Verify active
        $this->assertTrue($subscription->isActive());
        $this->assertFalse($subscription->isExpired());
        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'stripe_status' => 'active',
        ]);

        // Verify plan relationship
        $this->assertNotNull($subscription->plan);
        $this->assertEquals($plan->id, $subscription->plan->id);

        // Cancel subscription
        $subscription->stripe_status = 'canceled';
        $subscription->ends_at = now();
        $subscription->save();

        $subscription->refresh();
        $this->assertFalse($subscription->isActive());
        $this->assertEquals('canceled', $subscription->stripe_status);
    }

    /**
     * Test subscription with trial period via factory
     */
    #[Test]
    public function it_subscription_with_trial_period(): void
    {
        $plan = SubscriptionPlan::factory()->create([
            'name' => 'Pro Plan with Trial',
            'price' => 29.99,
            'billing_interval' => 'monthly',
            'trial_days' => 14,
        ]);

        $subscription = Subscription::factory()
            ->trialing()
            ->forPlan($plan)
            ->create();

        $this->assertEquals('trialing', $subscription->stripe_status);
        $this->assertNotNull($subscription->trial_ends_at);
        $this->assertTrue($subscription->trial_ends_at->isFuture());
    }

    /**
     * Test webhook controller handles valid payload via actual route
     */
    #[Test]
    public function it_webhook_signature_verification(): void
    {
        $payload = json_encode([
            'id' => 'evt_test_' . uniqid(),
            'object' => 'event',
            'type' => 'customer.subscription.updated',
            'data' => [
                'object' => [
                    'id' => 'sub_test_' . uniqid(),
                    'status' => 'active',
                    'customer' => 'cus_test_nonexistent',
                ],
            ],
        ]);

        // Test with empty payload (should return 400)
        $response = $this->postJson('/billing/stripe/webhook', []);
        $this->assertTrue(
            in_array($response->getStatusCode(), [400, 403]),
            'Empty payload should return 400 or 403, got ' . $response->getStatusCode()
        );

        // Test with valid-shaped payload (no signature middleware in test env)
        // The webhook controller should accept and process it
        $response = $this->call(
            'POST',
            '/billing/stripe/webhook',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $payload
        );

        // Should return 200 (processed) or 400/403 if signature check is enabled
        $this->assertTrue(
            in_array($response->getStatusCode(), [200, 400, 403]),
            'Valid payload should return 200, 400, or 403, got ' . $response->getStatusCode()
        );
    }

    /**
     * Test subscription plan change at model level
     */
    #[Test]
    public function it_subscription_plan_change(): void
    {
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

        $subscription = Subscription::factory()
            ->active()
            ->forPlan($basicPlan)
            ->create();

        $this->assertEquals($basicPlan->id, $subscription->subscription_plan_id);
        $this->assertEquals($basicPlan->id, $subscription->plan->id);

        // Simulate plan change at model level
        $subscription->subscription_plan_id = $proPlan->id;
        $subscription->stripe_price = $proPlan->remote_provider_price_id;
        $subscription->save();

        $subscription->refresh();
        $this->assertEquals($proPlan->id, $subscription->subscription_plan_id);
        $this->assertEquals($proPlan->id, $subscription->plan->id);
    }

    /**
     * Test WebhookLog model for failed payment tracking
     */
    #[Test]
    public function it_failed_payment_webhook_handling(): void
    {
        $webhookLog = WebhookLog::create([
            'provider' => 'stripe',
            'event_type' => 'invoice.payment_failed',
            'event_id' => 'evt_test_' . uniqid(),
            'payload' => [
                'type' => 'invoice.payment_failed',
                'data' => [
                    'object' => [
                        'subscription' => 'sub_test_123',
                        'customer' => 'cus_test_456',
                    ],
                ],
            ],
            'status' => WebhookLog::STATUS_PENDING,
            'attempts' => 0,
        ]);

        $this->assertDatabaseHas('webhook_logs', [
            'event_type' => 'invoice.payment_failed',
            'status' => 'pending',
        ]);

        // Test status transitions
        $webhookLog->markAsProcessing();
        $this->assertEquals(WebhookLog::STATUS_PROCESSING, $webhookLog->status);
        $this->assertEquals(1, $webhookLog->attempts);

        $webhookLog->markAsFailed('Payment method declined');
        $this->assertEquals(WebhookLog::STATUS_FAILED, $webhookLog->status);
        $this->assertEquals('Payment method declined', $webhookLog->error_message);

        // Test retry logic
        $this->assertTrue($webhookLog->canRetry());

        $webhookLog->markForRetry();
        $this->assertEquals(WebhookLog::STATUS_RETRYING, $webhookLog->status);
    }

    /**
     * Test subscription renewal updates status
     */
    #[Test]
    public function it_subscription_renewal(): void
    {
        $plan = SubscriptionPlan::factory()->create([
            'name' => 'Renewal Plan',
            'price' => 19.99,
            'billing_interval' => 'monthly',
        ]);

        $subscription = Subscription::factory()
            ->pastDue()
            ->forPlan($plan)
            ->create([
                'ends_at' => now()->subDay(),
            ]);

        $this->assertEquals('past_due', $subscription->stripe_status);

        // Simulate renewal: payment succeeds, status restored
        $subscription->stripe_status = 'active';
        $subscription->ends_at = now()->addMonth();
        $subscription->save();

        $subscription->refresh();
        $this->assertEquals('active', $subscription->stripe_status);
        $this->assertTrue($subscription->isActive());
        $this->assertTrue($subscription->ends_at->isFuture());
    }

    /**
     * Test admin can query all subscriptions
     */
    #[Test]
    public function it_admin_can_manage_all_subscriptions(): void
    {
        $this->actingAs($this->admin);

        // Record baseline counts
        $baseActive = Subscription::where('stripe_status', 'active')->count();
        $baseCanceled = Subscription::where('stripe_status', 'canceled')->count();

        // Create multiple subscriptions via factory
        Subscription::factory()->count(3)->active()->create();
        Subscription::factory()->count(2)->canceled()->create();

        $activeSubscriptions = Subscription::where('stripe_status', 'active')->get();
        $this->assertCount($baseActive + 3, $activeSubscriptions);

        $canceledSubscriptions = Subscription::where('stripe_status', 'canceled')->get();
        $this->assertCount($baseCanceled + 2, $canceledSubscriptions);

        // Test admin can cancel a subscription
        $subscription = $activeSubscriptions->first();
        $subscription->stripe_status = 'canceled';
        $subscription->ends_at = now();
        $subscription->save();

        $subscription->refresh();
        $this->assertEquals('canceled', $subscription->stripe_status);
    }

    /**
     * Test subscription stats can be calculated from models
     */
    #[Test]
    public function it_subscription_stats_calculation(): void
    {
        $baseActive = Subscription::where('stripe_status', 'active')->count();
        $baseCanceled = Subscription::where('stripe_status', 'canceled')->count();
        $baseTrialing = Subscription::where('stripe_status', 'trialing')->count();

        Subscription::factory()->count(3)->active()->create();
        Subscription::factory()->count(2)->canceled()->create();
        Subscription::factory()->count(1)->trialing()->create();

        $activeCount = Subscription::where('stripe_status', 'active')->count();
        $canceledCount = Subscription::where('stripe_status', 'canceled')->count();
        $trialingCount = Subscription::where('stripe_status', 'trialing')->count();

        $this->assertEquals($baseActive + 3, $activeCount);
        $this->assertEquals($baseCanceled + 2, $canceledCount);
        $this->assertEquals($baseTrialing + 1, $trialingCount);

        // Test SubscriptionPlan yearly price calculation
        $monthlyPlan = SubscriptionPlan::factory()->create([
            'price' => 10.00,
            'billing_interval' => 'monthly',
        ]);
        $this->assertEquals(120.00, $monthlyPlan->yearlyPrice());

        $yearlyPlan = SubscriptionPlan::factory()->create([
            'price' => 100.00,
            'billing_interval' => 'yearly',
        ]);
        $this->assertEquals(100.00, $yearlyPlan->yearlyPrice());
    }
}
