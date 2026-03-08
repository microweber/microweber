<?php

namespace Database\Factories\Modules\Content\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Content\Models\Content;

class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        return [
            'title'        => $this->faker->sentence(4),
            'content_type' => 'page',
            'url'          => $this->faker->unique()->slug(),
            'is_active'    => 1,
        ];
    }
}
