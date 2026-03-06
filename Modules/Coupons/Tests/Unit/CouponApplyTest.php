<?php

namespace Modules\Coupons\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;


use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Modules\Checkout\Repositories\CheckoutManager;
use Modules\Coupons\Models\CartCouponLog;
use Modules\Shop\Tests\Unit\ShopTestHelperTrait;
use Modules\Tax\Models\TaxType;

class CouponApplyTest extends TestCase
{

    use ShopTestHelperTrait;

    public function setUp(): void
    {
        parent::setUp();

        empty_cart();
        CartCouponLog::truncate();
        TaxType::truncate();

        save_option('enable_coupons', 1, 'shop');
        save_option('enable_taxes', 0, 'shop');

        DB::table('cart_coupon_logs')->truncate();
    }

    #[Test]

    public function it_valid_coupon_code(): void {
        $code = 'VALID_COUPON_CODE' . rand();
        $saveNewcode = [
            'coupon_code' => $code,
            'coupon_name' => 'test coupon' . rand(),
            'uses_per_coupon' => 1,
            'uses_per_customer' => 1,
            'total_amount' => 10,
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
        ];
        $saveNewcodeResult = coupons_save_coupon($saveNewcode);
        $couponId = $saveNewcodeResult['coupon_id'];
        $params = [
            'coupon_code' => $code,
        ];

        // First attempt (should fail due to minimum amount)
        $result = coupon_apply($params);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString("The coupon can't be applied because the minimum total amount is", $result['message']);

        // Add products to meet minimum amount
        $this->_addProductToCart('Product 1', 10);
        $this->_addProductToCart('Product 2', 10);
        $this->_addProductToCart('Product 3', 10);
        $this->_addProductToCart('Product 4', 10);
        $this->_addProductToCart('Product 5', 10);

        // Apply coupon successfully
        $result = coupon_apply($params);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertEquals($result['message'], 'Coupon code applied.');

        // Verify coupon was logged immediately after application
        $initialLog = CartCouponLog::where('coupon_code', $code)->first();
         $this->assertNotNull($initialLog, 'Coupon should be logged immediately after application');
        $this->assertSame($initialLog->coupon_code, $code);
        $this->assertSame($initialLog->coupon_id, $couponId);
        $this->assertSame($initialLog->discount_type, 'fixed_amount');
        $this->assertSame(floatval($initialLog->discount_value), floatval('10.00'));

        // Proceed with checkout
        $checkoutDetails = [
            'email' => 'test1' . uniqid() . '@microweber.com',
            'first_name' => 'Client',
            'last_name' => 'Microweber'
        ];

        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);


        // Verify coupon is still logged after checkout
        $postCheckoutLog = CartCouponLog::where('coupon_code', $code)->first();
        $this->assertNotNull($postCheckoutLog, 'Coupon log should persist after checkout');
    }


    #[Test]


    public function it_valid_coupon_code_with_too_big_discount(): void {

        $code = 'VALID_COUPON_CODE' . rand();
        $saveNewcode = [
            'coupon_code' => $code,
            'coupon_name' => 'test coupon' . rand(),
            'uses_per_coupon' => 1,
            'uses_per_customer' => 1,
            'total_amount' => 10,
            'discount_type' => 'fixed_amount',
            'discount_value' => 1000,
            'is_active' => 1,
        ];
        $saveNewcodeResult = coupons_save_coupon($saveNewcode);
        $couponId = $saveNewcodeResult['coupon_id'];
        $params = [
            'coupon_code' => $code,
        ];

        $result = coupon_apply($params);

        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('error', $result);


        $this->_addProductToCart('Product 1', 10);
        $this->_addProductToCart('Product 2', 10);
        $this->_addProductToCart('Product 3', 10);
        $this->_addProductToCart('Product 4', 10);
        $this->_addProductToCart('Product 5', 10);


        $result = coupon_apply($params);

        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);


        $checkoutDetails = array();
        $checkoutDetails['email'] = 'test' . uniqid() . '@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';


        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);

        // Verify coupon was logged and applied correctly
        $couponLog = CartCouponLog::where('coupon_code', $code)->first();
        $this->assertNotNull($couponLog, 'Coupon should be logged');
        $this->assertSame($couponLog->coupon_code, $code);
        $this->assertSame($couponLog->coupon_id, $couponId);
        $this->assertSame($couponLog->discount_type, 'fixed_amount');
        $this->assertSame(floatval($couponLog->discount_value), floatval('1000'));
        $this->assertSame(floatval($couponLog->discount_amount), floatval('50')); // Should be capped at cart total


    }


    #[Test]


    public function it_valid_coupon_code_with_percent(): void {

        $code = 'VALID_COUPON_CODE' . rand();
        $saveNewcode = [
            'coupon_code' => $code,
            'coupon_name' => 'test coupon percentage' . rand(),
            'uses_per_coupon' => 1,
            'uses_per_customer' => 1,
            'total_amount' => 10,
            'discount_type' => 'percentage',
            'discount_value' => 50,
            'is_active' => 1,
        ];
        $saveNewcodeResult = coupons_save_coupon($saveNewcode);
        $couponId = $saveNewcodeResult['coupon_id'];
        $params = [
            'coupon_code' => $code,
        ];

        $this->_addProductToCart('Product 1', 10);
        $this->_addProductToCart('Product 2', 10);
        $this->_addProductToCart('Product 3', 10);
        $this->_addProductToCart('Product 4', 10);
        $this->_addProductToCart('Product 5', 10);


        $result = coupon_apply($params);

        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
        $this->assertEquals($result['message'], 'Coupon code applied.');


        $checkoutDetails = array();
        $checkoutDetails['email'] = 'test' . uniqid() . '@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';


        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);

        $this->assertSame($checkoutStatus['promo_code'] ?? null, $code);
        $this->assertSame($checkoutStatus['coupon_id'] ?? null, $couponId);
        $this->assertSame($checkoutStatus['discount_type'] ?? null, 'percentage');
        $this->assertSame($checkoutStatus['discount_value'] ?? null, floatval('50.00'));
        $this->assertSame($checkoutStatus['amount'] ?? null, floatval('25.00'));

        $checkIfApplied = CartCouponLog::where('coupon_code', $code)->first();
        $this->assertSame($checkIfApplied->coupon_code, $code);
        $this->assertSame($checkIfApplied->coupon_id, $couponId);

    }

    #[Test]

    public function it_valid_coupon_code_with_percent_too_big(): void {
        empty_cart();
        CartCouponLog::truncate();
        save_option('enable_coupons', 1, 'shop');
        $code = 'VALID_COUPON_CODE' . rand();
        $saveNewcode = [
            'coupon_code' => $code,
            'coupon_name' => 'test coupon percentage' . rand(),
            'uses_per_coupon' => 1,
            'uses_per_customer' => 1,
            'total_amount' => 10,
            'discount_type' => 'percentage',
            'discount_value' => 5000,
            'is_active' => 1,
        ];
        $saveNewcodeResult = coupons_save_coupon($saveNewcode);
        $couponId = $saveNewcodeResult['coupon_id'];
        $params = [
            'coupon_code' => $code,
        ];

        $this->_addProductToCart('Product 1', 10);
        $this->_addProductToCart('Product 2', 10);
        $this->_addProductToCart('Product 3', 10);
        $this->_addProductToCart('Product 4', 10);
        $this->_addProductToCart('Product 5', 10);


        $result = coupon_apply($params);

        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);


        $checkoutDetails = array();
        $checkoutDetails['email'] = 'test' . uniqid() . '@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';


        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);

        $this->assertSame($checkoutStatus['promo_code'] ?? null, $code);
        $this->assertSame($checkoutStatus['coupon_id'] ?? null, $couponId);
        $this->assertSame($checkoutStatus['discount_type'] ?? null, 'percentage');
        $this->assertSame($checkoutStatus['discount_value'] ?? null, floatval('5000.00'));
        $this->assertSame($checkoutStatus['amount'] ?? null, floatval('0.00'));

        $checkIfApplied = CartCouponLog::where('coupon_code', $code)->first();
        $this->assertSame($checkIfApplied->coupon_code, $code);
        $this->assertSame($checkIfApplied->coupon_id, $couponId);

    }

    #[Test]

    public function it_invalid_coupon_code(): void {

        $params = [
            'coupon_code' => 'INVALID_COUPON_CODE',
        ];

        $result = coupon_apply($params);

        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('error', $result);
    }

    #[Test]

    public function it_disabled_coupon_usage(): void {
        save_option('enable_coupons', 0, 'shop');
        $params = [
            'coupon_code' => 'VALID_COUPON_CODE',
        ];
        $result = coupon_apply($params);

        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('error', $result);
    }


}
