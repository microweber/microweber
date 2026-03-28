<?php

namespace Modules\Shipping\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Shipping\Models\ShippingProvider;

class ShippingProviderFactory extends Factory
{
    protected $model = ShippingProvider::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'provider' => 'flat_rate',
            'is_active' => true,
            'is_default' => false,
            'settings' => json_encode([]),
            'position' => $this->faker->numberBetween(1, 100),
        ];
    }
}
