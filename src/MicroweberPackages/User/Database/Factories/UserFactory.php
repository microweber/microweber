<?php

namespace MicroweberPackages\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MicroweberPackages\User\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'is_active' => 1,
            'is_admin' => 0,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ];
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => 1,
            ];
        });
    }
}