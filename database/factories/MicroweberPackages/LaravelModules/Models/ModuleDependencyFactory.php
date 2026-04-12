<?php

namespace Database\Factories\MicroweberPackages\LaravelModules\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use MicroweberPackages\LaravelModules\Models\ModuleDependency;

class ModuleDependencyFactory extends Factory
{
    protected $model = ModuleDependency::class;

    public function definition(): array
    {
        return [
            'module_name'            => $this->faker->word() . 'Module',
            'dependency_module_name' => $this->faker->word() . 'Dependency',
            'version_constraint'     => '^1.0',
            'dependency_type'        => ModuleDependency::TYPE_REQUIRE,
            'is_optional'            => false,
            'description'            => $this->faker->sentence(),
        ];
    }
}
