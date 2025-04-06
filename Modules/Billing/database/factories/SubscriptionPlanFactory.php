<?php

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;
use Illuminate\Support\Str;

class SubscriptionPlanFactory extends Factory
{
    protected $model = SubscriptionPlan::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'sku' => strtoupper(Str::random(8)),
            'subscription_plan_group_id' => SubscriptionPlanGroup::factory(),
            'remote_provider' => 'stripe',
            'remote_provider_price_id' => 'price_' . Str::random(10),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'discount_price' => null,
            'save_price' => null,
            'save_price_badge' => null,
            'auto_apply_coupon_code' => null,
            'billing_interval' => 'monthly',
            'alternative_annual_plan_id' => null,
            'description' => $this->faker->sentence,
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }

    /**
     * Configure the plan with yearly billing interval
     */
    public function yearly()
    {
        return $this->state(function (array $attributes) {
            return [
                'billing_interval' => 'yearly',
            ];
        });
    }

    /**
     * Configure the plan with a specific group
     */
    public function forGroup(SubscriptionPlanGroup $group)
    {
        return $this->state(function (array $attributes) use ($group) {
            return [
                'subscription_plan_group_id' => $group->id,
            ];
        });
    }

    /**
     * Configure the plan and create associated features after creation
     */
    public function withFeatures(array $features = null)
    {
        return $this->afterCreating(function (SubscriptionPlan $plan) use ($features) {
            $featuresToCreate = $features ?? [
                ['key' => 'feature_1', 'value' => 'Feature 1 description'],
                ['key' => 'feature_2', 'value' => 'Feature 2 description'],
            ];

            foreach ($featuresToCreate as $feature) {
                $plan->features()->create($feature);
            }
        });
    }
}
