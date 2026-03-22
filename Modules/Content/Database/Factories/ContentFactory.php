<?php

namespace Modules\Content\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Content\Models\Content;

class ContentFactory extends Factory
{
    protected $model = Content::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'url' => $this->faker->unique()->slug(),
            'content_type' => 'content',
            'subtype' => 'static',
            'content' => $this->faker->paragraphs(3, true),
            'description' => $this->faker->paragraph(),
            'content_body' => $this->faker->paragraphs(5, true),
            'content_meta_title' => $this->faker->sentence(3),
            'content_meta_keywords' => implode(', ', $this->faker->words(5)),
            'content_meta_description' => $this->faker->paragraph(),
            'is_active' => true,
            'is_deleted' => false,
            'is_home' => false,
            'is_shop' => false,
            'require_login' => false,
            'created_by' => 1,
        ];
    }

    /**
     * Indicate that the content is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the content is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the content is a home page.
     */
    public function home(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_home' => true,
            'url' => 'home',
        ]);
    }

    /**
     * Indicate that the content is deleted.
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_deleted' => true,
        ]);
    }
}
