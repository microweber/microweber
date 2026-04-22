<?php

declare(strict_types=1);

namespace Modules\Tax\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Tax\Models\TaxRate;

class TaxRateFactory extends Factory
{
    protected $model = TaxRate::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst($this->faker->unique()->words(2, true)) . ' Tax',
            'description' => $this->faker->optional()->sentence(),
            'country_code' => strtoupper($this->faker->lexify('??')),
            'state_code' => null,
            'zip_code_pattern' => null,
            'city' => null,
            'type' => 'percentage',
            'rate' => $this->faker->randomFloat(2, 0, 25),
            'compound_tax' => false,
            'priority' => 1,
            'is_default' => false,
            'is_active' => true,
        ];
    }

    public function fixed(): self
    {
        return $this->state(fn () => [
            'type' => 'fixed',
            'rate' => $this->faker->randomFloat(2, 1, 50),
        ]);
    }

    public function inactive(): self
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
