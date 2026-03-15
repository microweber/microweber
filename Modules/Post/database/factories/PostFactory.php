<?php

namespace Modules\Post\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Post\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'content_type' => 'post',
            'subtype' => 'post',
            'url' => $this->faker->unique()->slug(),
            'is_active' => 1,
            'is_deleted' => 0,
            'is_home' => 0,
            'is_shop' => 0,
        ];
    }
}
