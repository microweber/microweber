<?php

namespace Modules\Billing\Tests\Unit;

use App\Models\User;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Customer\Models\Customer;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionTest extends BillingTestCase
{

    protected function tearDown(): void
    {
        // Delete any test subscriptions
        Subscription::where('stripe_id', 'like', 'sub_test%')->delete();

        // Delete any test customers
        Customer::where('stripe_id', 'like', 'cus_test%')->delete();

        // Delete any test subscription plans
        SubscriptionPlan::where('name', 'like', 'Test%')->delete();

        // Clean up any created users
        User::whereHas('customer', function($query) {
            $query->where('stripe_id', 'like', 'cus_test%');
        })->delete();

        parent::tearDown();
    }
    #[Test]
    public function it_can_create_user_subscription()
    {
        $user = User::factory()->create();

        Customer::where('stripe_id', 'cus_test123')->delete();
        Subscription::where('stripe_id', 'sub_test123')->delete();
        SubscriptionPlan::where('name', 'Test Pro Plan')->delete();

        $customer = $user->customer()->create([
            'stripe_id' => 'cus_test123',
            'active' => 1,
            'stripe_plan' => 'plan_test123',
            'trial_ends_at' => now()->addDays(14),
        ]);

        $plan = SubscriptionPlan::create([
            'name' => 'Test Pro Plan',
            'price' => 29.99,
            'billing_interval' => 'monthly'
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'subscription_plan_id' => $plan->id,
            'stripe_id' => 'sub_test123',
            'stripe_status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth()
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'customer_id' => $customer->id,
            'stripe_status' => 'active'
        ]);

        $this->assertEquals($plan->id, $subscription->plan->id);
    }

    #[Test]
    public function it_can_determine_if_subscription_is_active()
    {
        $user = User::factory()->create();

        $customer = $user->customer()->create([
            'stripe_id' => 'cus_test1231',
            'active' => 1,
            'stripe_plan' => 'plan_test1231',
            'trial_ends_at' => now()->addDays(14),
        ]);
        $plan = SubscriptionPlan::create([
            'name' => 'Test Basic Plan',
            'price' => 9.99,
            'billing_interval' => 'monthly'
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,

            'customer_id' => $customer->id,
            'subscription_plan_id' => $plan->id,
            'stripe_status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth()
        ]);

        $this->assertTrue($subscription->isActive());
    }

    #[Test]
    public function it_can_determine_if_subscription_is_expired()
    {
        $user = User::factory()->create();

        $customer = $user->customer()->create([
            'stripe_id' => 'cus_test1231it_can_determine_if_subscription_is_expired',
            'active' => 1,
            'stripe_plan' => 'plan_test1231it_can_determine_if_subscription_is_expired',
            'trial_ends_at' => now()->addDays(14),
        ]);
        $plan = SubscriptionPlan::create([
            'name' => 'Test Trial Plan',
            'price' => 0,
            'billing_interval' => 'monthly'
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,

            'customer_id' => $customer->id,
            'subscription_plan_id' => $plan->id,
            'stripe_status' => 'expired',
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->subDay()
        ]);

        $this->assertTrue($subscription->isExpired());
    }
}
