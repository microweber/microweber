<?php

namespace Modules\MailTemplate\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\MailTemplate\Models\MailTemplate;

class MailTemplateFactory extends Factory
{
    protected $model = MailTemplate::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'type' => $this->faker->randomElement(['welcome', 'order', 'notification', 'password_reset']),
            'from_name' => $this->faker->name,
            'from_email' => $this->faker->email,
            'copy_to' => $this->faker->optional()->email,
            'subject' => $this->faker->sentence,
            'message' => '<p>' . $this->faker->paragraph . '</p>',
            'is_active' => $this->faker->boolean(80),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
