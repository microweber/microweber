<?php

namespace Modules\Offer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Offer\Models\Offer;

class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        return [
            'product_id' => null,
            'price_id' => null,
            'offer_price' => $this->faker->randomFloat(2, 5, 500),
            'is_active' => 1,
            'expires_at' => $this->faker->optional()->dateTimeBetween('+1 day', '+30 days'),
        ];
    }
}
