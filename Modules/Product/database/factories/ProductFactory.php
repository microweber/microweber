<?php

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'content_type' => 'product',
            'subtype' => 'product',
            'url' => $this->faker->unique()->slug(),
            'is_active' => 1,
            'is_deleted' => 0,
            'is_home' => 0,
            'is_shop' => 0,
        ];
    }
}
