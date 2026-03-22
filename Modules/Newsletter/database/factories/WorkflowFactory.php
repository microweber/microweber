<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\Workflow;

class WorkflowFactory extends Factory
{
    protected $model = Workflow::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'trigger_type' => Workflow::TRIGGER_TYPE_EVENT,
            'trigger_event' => Workflow::TRIGGER_CART_ABANDONED,
            'trigger_conditions' => null,
            'is_active' => true,
            'execution_count' => 0,
            'success_count' => 0,
            'failure_count' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function manualTrigger(): static
    {
        return $this->state(fn (array $attributes) => [
            'trigger_type' => Workflow::TRIGGER_TYPE_MANUAL,
            'trigger_event' => null,
        ]);
    }

    public function withConditions(): static
    {
        return $this->state(fn (array $attributes) => [
            'trigger_conditions' => [
                ['field' => 'cart_total', 'operator' => 'greater_than', 'value' => 50],
            ],
        ]);
    }
}
