<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterTemplate;

class NewsletterTemplateFactory extends Factory
{
    protected $model = NewsletterTemplate::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'text' => $this->faker->paragraph,
            'json' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}