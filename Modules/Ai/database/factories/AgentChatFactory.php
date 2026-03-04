<?php

namespace Modules\Ai\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ai\Models\AgentChat;

class AgentChatFactory extends Factory
{
    protected $model = AgentChat::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'agent_type' => $this->faker->randomElement(['general', 'customer', 'shop', 'content', 'media']),
            'user_id' => null,
            'metadata' => null,
            'is_active' => true,
            'status' => 'active',
            'tags' => null,
        ];
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'status' => 'active',
        ]);
    }

    public function inactive(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'status' => 'archived',
        ]);
    }

    public function withUser(int $userId): self
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId,
        ]);
    }
}
