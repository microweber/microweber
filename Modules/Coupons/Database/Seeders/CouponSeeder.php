<?php

namespace Modules\Coupons\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Coupons\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        Coupon::create([
            'coupon_code' => 'TEST10',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addYear()
        ]);
    }
}