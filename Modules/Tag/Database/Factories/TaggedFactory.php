<?php

namespace Modules\Tag\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tag\Models\Tagged;

class TaggedFactory extends Factory
{
    protected $model = Tagged::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'taggable_id' => $this->faker->randomNumber(),
            'taggable_type' => 'content',
            'tag_name' => ucwords($name),
            'tag_slug' => \Illuminate\Support\Str::slug($name),
        ];
    }
}
