<?php

namespace Modules\Billing\Tests\Unit;

use Illuminate\Support\Facades\DB;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;

class SubscriptionPlanTest extends BillingTestCase
{

    public function test_it_can_create_subscription_plan()
    {
        $group = SubscriptionPlanGroup::create([
            'name' => 'Test Group',
            'description' => 'Test description'
        ]);

        $plan = SubscriptionPlan::create([
            'subscription_plan_group_id' => $group->id,
            'name' => 'Test Plan',
            'price' => 9.99,
            'billing_interval' => 'monthly',
         ]);

        $plan->features()->create([
            'key' => 'feature1',
            'value' => 'Feature 1 description'
        ]);
        $plan->features()->create([
            'key' => 'feature2',
            'value' => 'Feature 2 description'
        ]);

        $this->assertDatabaseHas('subscription_plans', [
            'name' => 'Test Plan',
            'price' => 9.99
        ]);

        $this->assertEquals(2, count($plan->features));
    }

    public function test_it_can_calculate_yearly_price()
    {
        $plan = SubscriptionPlan::create([
            'name' => 'Yearly Test',
            'price' => 100,
            'billing_interval' => 'yearly'
        ]);

        $this->assertEquals(100, $plan->yearlyPrice());
    }


    public function test_it_can_convert_monthly_to_yearly()
    {
        $plan = SubscriptionPlan::create([
            'name' => 'Monthly Test',
            'price' => 10,
            'billing_interval' => 'monthly'
        ]);

        $this->assertEquals(120, $plan->yearlyPrice());
    }
}
