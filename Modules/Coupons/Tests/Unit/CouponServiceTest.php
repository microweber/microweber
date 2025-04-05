<?php

namespace Modules\Coupons\Tests\Unit;

use Modules\Coupons\Services\CouponService;
use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Models\CouponLog;
use Illuminate\Support\Facades\Session;
use MicroweberPackages\Core\tests\TestCase;

class CouponServiceTest extends TestCase
{
    private $couponService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->couponService = app(CouponService::class);
        Session::flush();
    }

    public function testGenerateUniqueCouponCode()
    {
        $code1 = $this->couponService->generateCouponCode();
        $code2 = $this->couponService->generateCouponCode();

        $this->assertNotEquals($code1, $code2);
        $this->assertEquals(8, strlen($code1));
    }

    public function testSessionManagement()
    {
        Coupon::truncate();
        CouponLog::truncate();
        $coupon = \Modules\Coupons\Database\Factories\CouponFactory::new()->create([
            'coupon_code' => 'TEST_SESSION',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10
        ]);

        // Test storing through public API
        $result = $this->couponService->applyCoupon('TEST_SESSION', 100);
        $sessionData = $this->couponService->getCouponSession();
        $this->assertTrue($result['success']);

        $this->assertEquals('TEST_SESSION', $sessionData['coupon_code']);
        $this->assertEquals(10, $sessionData['discount_value']);

        // Test clearing
        $this->couponService->clearCouponSession();
        $this->assertEquals([
    'coupon_code' => null,
    'discount_value' => null,
    'coupon_data' => null
], $this->couponService->getCouponSession());
    }

    public function testExpiredCoupon()
    {
        Coupon::truncate();
        CouponLog::truncate();
        $coupon = \Modules\Coupons\Database\Factories\CouponFactory::new()
            ->expired()
            ->create(['coupon_code' => 'EXPIRED_TEST']);

        $result = $this->couponService->applyCoupon('EXPIRED_TEST', 100);

        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('not valid', $result['message']);
    }

    public function testCustomerUsageLimits()
    {
        Coupon::truncate();
        CouponLog::truncate();
        $coupon = \Modules\Coupons\Database\Factories\CouponFactory::new()->create([
            'coupon_code' => 'CUSTOMER_LIMIT',
            'uses_per_customer' => 1
        ]);

        // First use - should succeed
        $result1 = $this->couponService->applyCoupon(
            'CUSTOMER_LIMIT',
            100,
            'test@example.com',
            '127.0.0.1'
        );
        $this->assertTrue($result1['success']);

        // Manually trigger usage logging (temporary for testing)
        $coupon = Coupon::where('coupon_code', 'CUSTOMER_LIMIT')->first();

        // Verify usage was logged
        $usageCount = \Modules\Coupons\Models\CouponLog::where('coupon_code', 'CUSTOMER_LIMIT')
            ->where('customer_email', 'test@example.com')
            ->count();
        $this->assertEquals(1, $usageCount);

        // Second use - should fail
        $result2 = $this->couponService->applyCoupon(
            'CUSTOMER_LIMIT',
            100,
            'test@example.com',
            '127.0.0.1'
        );
        $this->assertArrayHasKey('error', $result2);
        $this->assertStringContainsString('The coupon has reached its maximum usage limit for this customer', $result2['message']);
    }
}
