<?php

namespace Modules\Faq\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Faq\Models\Faq;

class FaqFactory extends Factory
{
    protected $model = Faq::class;

    public function definition(): array
    {
        return [
            'question' => 'Test question ' . $this->faker->unique()->numberBetween(1, 1000000) . '?',
            // Use plain English words (not Faker Latin lorem ipsum) so the row is NOT
            // excluded by FaqModuleResource's production scope, which filters out
            // answers containing Latin Faker keywords.
            'answer' => 'This is a test answer ' . $this->faker->numberBetween(1, 1000000),
            'position' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
