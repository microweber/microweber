<?php

namespace Modules\Tag\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tag\Models\TagGroup;

class TagGroupFactory extends Factory
{
    protected $model = TagGroup::class;

    public function definition(): array
    {
        return [
            'name' => ucwords($this->faker->unique()->words(2, true)),
        ];
    }
}
