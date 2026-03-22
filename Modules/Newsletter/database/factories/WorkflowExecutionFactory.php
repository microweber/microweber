<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\Workflow;
use Modules\Newsletter\Models\WorkflowExecution;

class WorkflowExecutionFactory extends Factory
{
    protected $model = WorkflowExecution::class;

    public function definition(): array
    {
        return [
            'workflow_id' => Workflow::factory(),
            'execution_key' => 'wf_' . uniqid() . '_' . time(),
            'status' => $this->faker->randomElement([
                WorkflowExecution::STATUS_PENDING,
                WorkflowExecution::STATUS_RUNNING,
                WorkflowExecution::STATUS_COMPLETED,
                WorkflowExecution::STATUS_FAILED,
            ]),
            'trigger_source' => $this->faker->randomElement([
                WorkflowExecution::SOURCE_EVENT,
                WorkflowExecution::SOURCE_MANUAL,
                WorkflowExecution::SOURCE_SCHEDULE,
            ]),
            'trigger_data' => ['email' => $this->faker->email()],
            'started_at' => null,
            'completed_at' => null,
            'current_step' => 0,
            'total_steps' => $this->faker->numberBetween(1, 10),
            'execution_log' => [],
            'error_message' => null,
            'user_id' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WorkflowExecution::STATUS_PENDING,
            'started_at' => null,
            'completed_at' => null,
        ]);
    }

    public function running(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WorkflowExecution::STATUS_RUNNING,
            'started_at' => now(),
            'completed_at' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WorkflowExecution::STATUS_COMPLETED,
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
            'current_step' => $attributes['total_steps'],
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WorkflowExecution::STATUS_FAILED,
            'started_at' => now()->subMinutes(5),
            'completed_at' => now(),
            'error_message' => $this->faker->sentence(),
        ]);
    }
}
