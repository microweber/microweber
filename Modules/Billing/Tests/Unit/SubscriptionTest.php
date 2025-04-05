<?php

namespace Modules\Billing\Tests\Unit;

use App\Models\User;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;

class SubscriptionTest extends BillingTestCase
{
    /** @test */
    public function it_can_create_user_subscription()
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::create([
            'name' => 'Pro Plan',
            'price' => 29.99,
            'billing_cycle' => 'monthly'
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addMonth()
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'status' => 'active'
        ]);
        
        $this->assertEquals($plan->id, $subscription->plan->id);
    }

    /** @test */
    public function it_can_determine_if_subscription_is_active()
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::create([
            'name' => 'Basic Plan',
            'price' => 9.99,
            'billing_cycle' => 'monthly'
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth()
        ]);

        $this->assertTrue($subscription->isActive());
    }

    /** @test */
    public function it_can_determine_if_subscription_is_expired()
    {
        $user = User::factory()->create();
        $plan = SubscriptionPlan::create([
            'name' => 'Trial Plan',
            'price' => 0,
            'billing_cycle' => 'monthly'
        ]);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'expired',
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->subDay()
        ]);

        $this->assertTrue($subscription->isExpired());
    }
}