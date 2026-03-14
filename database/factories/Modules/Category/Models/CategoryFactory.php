<?php

namespace Database\Factories\Modules\Category\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'title'     => $this->faker->words(3, true),
            'url'       => $this->faker->unique()->slug(),
            'data_type' => 'category',
            'rel_type'  => \Modules\Content\Models\Content::class,
            'is_active'  => 1,
            'is_deleted' => 0,
            'is_hidden'  => 0,
            'parent_id'  => 0,
        ];
    }
}
