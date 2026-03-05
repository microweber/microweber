<?php

namespace Modules\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Customer;
use MicroweberPackages\User\Models\User;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => 'active',
            'customer_data' => [],
            'user_id' => null,
            'currency_id' => null,
            'company_id' => null,
        ];
    }

    public function withUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => User::factory(),
        ]);
    }
}
