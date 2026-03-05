<?php

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\WebhookLog;

class WebhookLogFactory extends Factory
{
    protected $model = WebhookLog::class;

    public function definition(): array
    {
        return [
            'provider' => 'stripe',
            'event_type' => $this->faker->randomElement([
                'invoice.paid',
                'invoice.payment_failed',
                'customer.subscription.updated',
                'customer.subscription.deleted',
                'checkout.session.completed',
            ]),
            'event_id' => 'evt_' . $this->faker->uuid(),
            'payload' => [
                'id' => 'evt_' . $this->faker->uuid(),
                'type' => 'invoice.paid',
                'data' => [
                    'object' => [
                        'id' => 'inv_' . $this->faker->uuid(),
                        'amount_paid' => $this->faker->numberBetween(1000, 10000),
                        'currency' => 'usd',
                    ],
                ],
            ],
            'status' => WebhookLog::STATUS_PENDING,
            'attempts' => 0,
            'error_message' => null,
            'processed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebhookLog::STATUS_COMPLETED,
            'processed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebhookLog::STATUS_FAILED,
            'error_message' => 'Test error message',
            'attempts' => 1,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => WebhookLog::STATUS_PENDING,
            'attempts' => 0,
        ]);
    }
}
