<?php

namespace MicroweberPackages\LaravelModules\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MicroweberPackages\LaravelModules\Models\ModuleDependency;

class ModuleDependencyFactory extends Factory
{
    protected $model = ModuleDependency::class;

    public function definition(): array
    {
        return [
            'module_name' => $this->faker->word(),
            'dependency_module_name' => $this->faker->word(),
            'version_constraint' => $this->faker->optional()->randomElement(['^1.0', '>=2.0', '~3.0']),
            'dependency_type' => $this->faker->randomElement([
                ModuleDependency::TYPE_REQUIRE,
                ModuleDependency::TYPE_CONFLICT,
                ModuleDependency::TYPE_SUGGEST,
                ModuleDependency::TYPE_REPLACE,
            ]),
            'is_optional' => $this->faker->boolean(20),
            'description' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * State for a required dependency
     */
    public function required(): static
    {
        return $this->state(fn (array $attributes) => [
            'dependency_type' => ModuleDependency::TYPE_REQUIRE,
            'is_optional' => false,
        ]);
    }

    /**
     * State for a conflict dependency
     */
    public function conflict(): static
    {
        return $this->state(fn (array $attributes) => [
            'dependency_type' => ModuleDependency::TYPE_CONFLICT,
            'is_optional' => false,
        ]);
    }
}
