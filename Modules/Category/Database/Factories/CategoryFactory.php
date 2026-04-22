<?php

declare(strict_types=1);

namespace Modules\Category\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;
use Modules\Content\Models\Content;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $title = ucwords($this->faker->unique()->words(3, true));

        return [
            'title' => $title,
            'url' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1000, 99999),
            'data_type' => 'category',
            'rel_type' => Content::class,
            'rel_id' => 0,
            'parent_id' => 0,
            'position' => 0,
            'is_active' => 1,
            'is_hidden' => 0,
            'is_deleted' => 0,
        ];
    }

    public function hidden(): self
    {
        return $this->state(fn () => ['is_hidden' => 1]);
    }

    public function forProducts(): self
    {
        return $this->state(fn () => ['rel_type' => 'Modules\\Product\\Models\\Product']);
    }
}
