<?php

namespace Modules\Payment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Payment\Models\PaymentProvider;

class PaymentProviderFactory extends Factory
{
    protected $model = PaymentProvider::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'provider' => $this->faker->randomElement(['stripe', 'paypal', 'pay_on_delivery']),
            'is_active' => 1,
            'is_default' => 0,
            'settings' => [],
            'position' => $this->faker->numberBetween(1, 100),
        ];
    }
}
