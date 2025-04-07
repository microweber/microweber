<?php

namespace Modules\Coupons\Tests\Unit\Livewire;

use Livewire\Livewire;
use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Tests\Unit\CouponTestCase;
use PHPUnit\Framework\Attributes\Test;

class CouponsTest extends CouponTestCase
{

    #[Test]
    public function it_can_create_new_coupon()
    {
        $couponData = [
            'code' => 'TEST10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_uses' => 100
        ];


        $coupon = Coupon::create([
            'coupon_code' => $couponData['code'],
            'discount_type' => $couponData['discount_type'],
            'discount_value' => $couponData['discount_value'],
            'uses_per_coupon' => $couponData['max_uses']
        ]);
        $this->assertDatabaseHas('cart_coupons', [
            'coupon_code' => $couponData['code'],
            'discount_type' => $couponData['discount_type'],
            'discount_value' => $couponData['discount_value'],
            'uses_per_coupon' => $couponData['max_uses']
        ]);
    }




    #[Test]
    public function it_can_delete_coupon()
    {
        $coupon = Coupon::create([
            'code' => 'DELETE_ME',
            'discount_type' => 'fixed',
            'discount_value' => 5
        ]);

        $couponId = $coupon->id;
        $coupon->delete();
        $this->assertDatabaseMissing('cart_coupons', ['id' => $couponId]);
    }
}
