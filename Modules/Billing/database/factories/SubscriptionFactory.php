<?php

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionCustomer;
use Illuminate\Support\Str;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'customer_id' => SubscriptionCustomer::factory(),
            'user_id' => null,
            'subscription_plan_id' => SubscriptionPlan::factory(),
            'subscription_customer_id' => null,
            'type' => 'stripe',
            'name' => $this->faker->words(2, true) . ' Plan',
            'stripe_id' => 'sub_' . Str::random(10),
            'stripe_status' => 'active',
            'stripe_price' => 'price_' . Str::random(10),
            'quantity' => 1,
            'trial_ends_at' => null,
            'starts_at' => now(),
            'ends_at' => null,
        ];
    }

    /**
     * Configure the subscription as active
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'stripe_status' => 'active',
            'trial_ends_at' => null,
            'ends_at' => null,
        ]);
    }

    /**
     * Configure the subscription as canceled
     */
    public function canceled(): static
    {
        return $this->state(fn (array $attributes) => [
            'stripe_status' => 'canceled',
            'ends_at' => now()->subDay(),
        ]);
    }

    /**
     * Configure the subscription as trialing
     */
    public function trialing(): static
    {
        return $this->state(fn (array $attributes) => [
            'stripe_status' => 'trialing',
            'trial_ends_at' => now()->addDays(7),
            'starts_at' => now(),
        ]);
    }

    /**
     * Configure the subscription as expired
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'stripe_status' => 'incomplete_expired',
            'ends_at' => now()->subDay(),
        ]);
    }

    /**
     * Configure the subscription as past due
     */
    public function pastDue(): static
    {
        return $this->state(fn (array $attributes) => [
            'stripe_status' => 'past_due',
        ]);
    }

    /**
     * Configure the subscription with an expired trial
     */
    public function expiredTrial(): static
    {
        return $this->state(fn (array $attributes) => [
            'stripe_status' => 'active',
            'trial_ends_at' => now()->subDay(),
        ]);
    }

    /**
     * Configure the subscription with a specific plan
     */
    public function forPlan(SubscriptionPlan $plan): static
    {
        return $this->state(fn (array $attributes) => [
            'subscription_plan_id' => $plan->id,
            'stripe_price' => $plan->remote_provider_price_id ?? 'price_' . Str::random(10),
        ]);
    }

    /**
     * Configure the subscription with a specific customer
     */
    public function forCustomer(SubscriptionCustomer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
            'user_id' => $customer->user_id,
        ]);
    }
}
