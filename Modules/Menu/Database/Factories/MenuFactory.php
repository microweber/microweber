<?php

declare(strict_types=1);

namespace Modules\Menu\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Menu\Models\Menu;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->unique()->words(2, true)),
            'item_type' => 'menu',
            'parent_id' => 0,
            'position' => 0,
            'is_active' => 1,
        ];
    }

    public function item(): self
    {
        return $this->state(fn () => [
            'item_type' => 'menu_item',
            'url' => $this->faker->url(),
        ]);
    }
}
