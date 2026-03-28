<?php

namespace Modules\Tag\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tag\Models\Tag;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'name' => ucwords($name),
            'slug' => \Illuminate\Support\Str::slug($name),
            'count' => 0,
            'suggest' => false,
        ];
    }
}
