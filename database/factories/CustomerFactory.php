<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'active' => true,
            'customer_data' => null,
            'user_id' => null,
            'currency_id' => null, 
            'company_id' => null
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'active' => false
            ];
        });
    }

    public function premium()
    {
        return $this->state(function (array $attributes) {
            return [
                'customer_data' => ['is_premium' => true]
            ];
        });
    }

    public function withCompany($companyId)
    {
        return $this->state(function (array $attributes) use ($companyId) {
            return [
                'company_id' => $companyId
            ];
        });
    }
}