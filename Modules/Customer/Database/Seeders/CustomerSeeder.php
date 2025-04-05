<?php

namespace Modules\Customer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Customer\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        Customer::create([
            'name' => 'Admin Customer',
            'email' => 'admin@example.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'active' => 1
        ]);
    }
}