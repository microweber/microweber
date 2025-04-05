<?php

namespace Modules\Billing\Tests\Unit;

use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;

class SubscriptionPlanTest extends BillingTestCase
{
    /** @test */
    public function it_can_create_subscription_plan()
    {
        $group = SubscriptionPlanGroup::create([
            'name' => 'Test Group',
            'description' => 'Test description'
        ]);

        $plan = SubscriptionPlan::create([
            'subscription_plan_group_id' => $group->id,
            'name' => 'Test Plan',
            'price' => 9.99,
            'billing_cycle' => 'monthly',
            'features' => ['feature1', 'feature2']
        ]);

        $this->assertDatabaseHas('subscription_plans', [
            'name' => 'Test Plan',
            'price' => 9.99
        ]);
        
        $this->assertEquals(2, count($plan->features));
    }

    /** @test */
    public function it_can_calculate_yearly_price()
    {
        $plan = SubscriptionPlan::create([
            'name' => 'Yearly Test',
            'price' => 100,
            'billing_cycle' => 'yearly'
        ]);

        $this->assertEquals(100, $plan->yearlyPrice());
    }

    /** @test */
    public function it_can_convert_monthly_to_yearly()
    {
        $plan = SubscriptionPlan::create([
            'name' => 'Monthly Test',
            'price' => 10,
            'billing_cycle' => 'monthly'
        ]);

        $this->assertEquals(120, $plan->yearlyPrice());
    }
}