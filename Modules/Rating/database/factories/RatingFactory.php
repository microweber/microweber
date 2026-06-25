<?php

namespace Modules\Rating\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Rating\Models\Rating;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'rel_type' => 'content',
            'rel_id' => $this->faker->numberBetween(1, 100),
            'rating' => $this->faker->numberBetween(1, 5),
            // Use plain English words (not Faker Latin lorem ipsum) so the row is NOT
            // excluded by RatingModuleResource's production scope, which filters out
            // comments containing Latin Faker keywords.
            'comment' => 'Customer review ' . $this->faker->unique()->numberBetween(1, 1000000),
            'session_id' => $this->faker->uuid(),
        ];
    }
}
