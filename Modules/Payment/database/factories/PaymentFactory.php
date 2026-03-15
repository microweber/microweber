<?php

namespace Modules\Payment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Payment\Enums\PaymentStatus;
use Modules\Payment\Models\Payment;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'rel_id' => (string) $this->faker->randomNumber(4),
            'rel_type' => 'order',
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'GBP']),
            'status' => $this->faker->randomElement([
                PaymentStatus::Pending->value,
                PaymentStatus::Completed->value,
                PaymentStatus::Failed->value,
            ]),
            'payment_provider' => $this->faker->randomElement(['stripe', 'paypal', 'pay_on_delivery']),
            'transaction_id' => $this->faker->uuid(),
            'payment_provider_id' => null,
            'payment_data' => null,
        ];
    }
}
