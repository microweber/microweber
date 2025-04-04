<?php

namespace Modules\Coupons\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Coupons\Models\Coupon;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition()
    {
        return [
            'coupon_code' => strtoupper($this->faker->bothify('????####')),
            'coupon_name' => $this->faker->words(3, true),
            'discount_type' => 'fixed_amount',
            'discount_value' => $this->faker->numberBetween(5, 20),
            'total_amount' => $this->faker->numberBetween(50, 100),
            'uses_per_coupon' => $this->faker->numberBetween(1, 100),
            'uses_per_customer' => $this->faker->numberBetween(1, 5),
            'is_active' => true,
        ];
    }

    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false
            ];
        });
    }

    public function percentageDiscount()
    {
        return $this->state(function (array $attributes) {
            return [
                'discount_type' => 'percentage',
                'discount_value' => $this->faker->numberBetween(5, 50)
            ];
        });
    }
}