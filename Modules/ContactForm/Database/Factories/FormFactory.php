<?php

declare(strict_types=1);

namespace Modules\ContactForm\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\ContactForm\Models\Form;

class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition(): array
    {
        $name = ucwords($this->faker->unique()->words(3, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 99999),
            'module_id' => $this->faker->unique()->numberBetween(1, 99999),
            'description' => $this->faker->sentence(),
            'confirmation_message' => 'Thanks for getting in touch!',
            'is_active' => 1,
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn () => ['is_active' => 0]);
    }
}
