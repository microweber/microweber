<?php

namespace Modules\Ai\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Models\AgentChatMessage;

class AgentChatMessageFactory extends Factory
{
    protected $model = AgentChatMessage::class;

    public function definition(): array
    {
        return [
            'chat_id' => AgentChat::factory(),
            'role' => $this->faker->randomElement(['user', 'assistant', 'system']),
            'content' => $this->faker->paragraph(),
            'metadata' => null,
            'agent_type' => $this->faker->randomElement(['general', 'customer', 'shop', 'content', 'media']),
            'processed_at' => $this->faker->optional()->dateTime(),
        ];
    }

    public function user(): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'user',
            'processed_at' => null,
        ]);
    }

    public function assistant(): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'assistant',
            'processed_at' => now(),
        ]);
    }

    public function system(): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'system',
            'processed_at' => now(),
        ]);
    }

    public function withToolCalls(array $toolCalls): self
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'assistant',
            'metadata' => [
                'tool_calls' => $toolCalls,
            ],
        ]);
    }

    public function unprocessed(): self
    {
        return $this->state(fn (array $attributes) => [
            'processed_at' => null,
        ]);
    }

    public function processed(): self
    {
        return $this->state(fn (array $attributes) => [
            'processed_at' => now(),
        ]);
    }
}
