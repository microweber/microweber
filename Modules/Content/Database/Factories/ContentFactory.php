<?php

namespace Modules\Content\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Content\Models\Content;

class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'content_type' => 'page',
            'subtype' => 'static',
            'url' => $this->faker->unique()->slug(),
            'is_active' => 1,
            'is_deleted' => 0,
            'is_home' => 0,
            'is_shop' => 0,
        ];
    }
}
