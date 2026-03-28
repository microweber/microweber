<?php

namespace Modules\Company\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Company\Models\Company;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'website' => $this->faker->optional()->url(),
            'email' => $this->faker->optional()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'country' => $this->faker->optional()->country(),
            'city' => $this->faker->optional()->city(),
            'address' => $this->faker->optional()->streetAddress(),
        ];
    }
}
