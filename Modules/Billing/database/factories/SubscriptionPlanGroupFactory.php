<?php

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\SubscriptionPlanGroup;
use Illuminate\Support\Str;

class SubscriptionPlanGroupFactory extends Factory
{
    protected $model = SubscriptionPlanGroup::class;

    public function definition()
    {
        $name = $this->faker->company . ' Group';

        return [
            'name' => $name,
            'description' => $this->faker->sentence,
            'sku' => strtoupper($this->faker->bothify('??##??##')),
            'type' => $this->faker->randomElement(['basic', 'standard', 'premium']),
            'position' => $this->faker->numberBetween(1, 10),
            'icon' => $this->faker->randomElement(['star', 'shield', 'bolt', 'crown']),
        ];
    }

    /**
     * Create a group with a specific position
     */
    public function withPosition(int $position)
    {
        return $this->state(function (array $attributes) use ($position) {
            return [
                'position' => $position,
            ];
        });
    }

    /**
     * Create a group with features
     */
    public function withFeatures(array $features = null)
    {
        return $this->afterCreating(function (SubscriptionPlanGroup $group) use ($features) {
            $featuresToCreate = $features ?? [
                ['name' => 'Core Feature', 'sort' => 1],
                ['name' => 'Premium Feature', 'sort' => 2],
                ['name' => 'Extra Feature', 'sort' => 3],
            ];

            foreach ($featuresToCreate as $feature) {
                $group->features()->create($feature);
            }
        });
    }
}
