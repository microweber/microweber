<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterList;

class NewsletterListFactory extends Factory
{
    protected $model = NewsletterList::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}