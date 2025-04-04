<?php

namespace Modules\Coupons\Tests\Unit;

use Modules\Coupons\Services\CouponService;
use Modules\Coupons\Models\Coupon;
use Carbon\Carbon;
use MicroweberPackages\Core\tests\TestCase;

class CouponDateValidationTest extends TestCase
{
    private $couponService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock the entire database layer
        $this->mockDatabase();
            
        $this->couponService = new \Tests\TestCouponService();
    }

    private function mockDatabase()
    {
        // Mock all database dependencies
        $mock = \Mockery::mock('overload:Illuminate\Database\DatabaseManager');
        $mock->shouldReceive('connection')->andReturnSelf();
        $mock->shouldReceive('table')->andReturnSelf();
        $mock->shouldReceive('where')->andReturnSelf();
        $mock->shouldReceive('first')->andReturn(null);
        $mock->shouldReceive('insert')->andReturn(true);
    }

    public function testFutureDatedCoupon()
    {
        $coupon = \Modules\Coupons\Database\Factories\CouponFactory::new()->create([
            'coupon_code' => 'FUTURE_COUPON',
            'start_date' => Carbon::now()->addDays(7),
            'end_date' => Carbon::now()->addDays(14),
            'is_active' => 1
        ]);

        $result = $this->couponService->applyCoupon('FUTURE_COUPON', 100);
        
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('not yet valid', $result['message']);
    }

    public function testExpiredCoupon()
    {
        $coupon = \Modules\Coupons\Database\Factories\CouponFactory::new()->create([
            'coupon_code' => 'EXPIRED_COUPON',
            'start_date' => Carbon::now()->subDays(14),
            'end_date' => Carbon::now()->subDays(7),
            'is_active' => 1
        ]);

        $result = $this->couponService->applyCoupon('EXPIRED_COUPON', 100);
        
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('expired', $result['message']);
    }

    public function testActiveCoupon()
    {
        $coupon = \Modules\Coupons\Database\Factories\CouponFactory::new()->create([
            'coupon_code' => 'ACTIVE_COUPON',
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addDays(1),
            'is_active' => 1
        ]);

        $result = $this->couponService->applyCoupon('ACTIVE_COUPON', 100);
        
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
    }

    public function testNoDateRestrictions()
    {
        $coupon = \Modules\Coupons\Database\Factories\CouponFactory::new()->create([
            'coupon_code' => 'NO_DATE_COUPON',
            'start_date' => null,
            'end_date' => null,
            'is_active' => 1
        ]);

        $result = $this->couponService->applyCoupon('NO_DATE_COUPON', 100);
        
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
    }
}