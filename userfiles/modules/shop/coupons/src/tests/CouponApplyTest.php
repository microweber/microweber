<?php

namespace MicroweberPackages\Modules\Shop\Coupons\tests;


use MicroweberPackages\Checkout\CheckoutManager;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Modules\Shop\Coupons\Models\CartCouponLog;
use MicroweberPackages\Shop\tests\ShopTestHelperTrait;

class CouponApplyTest extends TestCase
{

    use ShopTestHelperTrait;

    public function testValidCouponCode()
    {
        empty_cart();
        CartCouponLog::truncate();
        save_option('enable_coupons', 1, 'shop');
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
        $checkoutDetails['email'] = 'test'.uniqid().'@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';


        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);

        $this->assertSame($checkoutStatus['promo_code'], $code);
        $this->assertSame($checkoutStatus['coupon_id'], $couponId);
        $this->assertSame($checkoutStatus['discount_type'], 'fixed_amount');
        $this->assertSame($checkoutStatus['discount_value'], floatval('10.00'));
        $this->assertSame($checkoutStatus['amount'], floatval('40.00'));
        $this->assertSame($checkoutStatus['payment_amount'], floatval('40.00'));

        $checkIfApplied = CartCouponLog::where('coupon_code', $code)->first();
        $this->assertSame($checkIfApplied->coupon_code, $code);
        $this->assertSame($checkIfApplied->coupon_id, $couponId);


    }


    public function testValidCouponCodeWithTooBigDiscount()
    {
        empty_cart();
        CartCouponLog::truncate();
        save_option('enable_coupons', 1, 'shop');
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
        $checkoutDetails['email'] = 'test'.uniqid().'@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';


        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);

        $this->assertSame($checkoutStatus['promo_code'], $code);
        $this->assertSame($checkoutStatus['coupon_id'], $couponId);
        $this->assertSame($checkoutStatus['discount_type'], 'fixed_amount');
        $this->assertSame($checkoutStatus['discount_value'], floatval('1000'));
        $this->assertSame($checkoutStatus['amount'], floatval('0'));
        $this->assertSame($checkoutStatus['payment_amount'], floatval('0'));

        $checkIfApplied = CartCouponLog::where('coupon_code', $code)->first();
        $this->assertSame($checkIfApplied->coupon_code, $code);
        $this->assertSame($checkIfApplied->coupon_id, $couponId);


    }




    public function testValidCouponCodeWithPercent()
    {
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


        $checkoutDetails = array();
        $checkoutDetails['email'] = 'test'.uniqid().'@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';


        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);

        $this->assertSame($checkoutStatus['promo_code'], $code);
        $this->assertSame($checkoutStatus['coupon_id'], $couponId);
        $this->assertSame($checkoutStatus['discount_type'], 'percentage');
        $this->assertSame($checkoutStatus['discount_value'], floatval('50.00'));
        $this->assertSame($checkoutStatus['amount'], floatval('25.00'));
        $this->assertSame($checkoutStatus['payment_amount'], floatval('25.00'));

        $checkIfApplied = CartCouponLog::where('coupon_code', $code)->first();
        $this->assertSame($checkIfApplied->coupon_code, $code);
        $this->assertSame($checkIfApplied->coupon_id, $couponId);

    }
    public function testValidCouponCodeWithPercentTooBig()
    {
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
        $checkoutDetails['email'] = 'test'.uniqid().'@microweber.com';
        $checkoutDetails['first_name'] = 'Client';
        $checkoutDetails['last_name'] = 'Microweber';


        $checkout = new CheckoutManager();
        $checkoutStatus = $checkout->checkout($checkoutDetails);

        $this->assertSame($checkoutStatus['promo_code'], $code);
        $this->assertSame($checkoutStatus['coupon_id'], $couponId);
        $this->assertSame($checkoutStatus['discount_type'], 'percentage');
        $this->assertSame($checkoutStatus['discount_value'], floatval('5000.00'));
        $this->assertSame($checkoutStatus['amount'], floatval('0.00'));
        $this->assertSame($checkoutStatus['payment_amount'], floatval('0.00'));

        $checkIfApplied = CartCouponLog::where('coupon_code', $code)->first();
        $this->assertSame($checkIfApplied->coupon_code, $code);
        $this->assertSame($checkIfApplied->coupon_id, $couponId);

    }

    public function testInvalidCouponCode()
    {
        save_option('enable_coupons', 1, 'shop');

        empty_cart();
        $params = [
            'coupon_code' => 'INVALID_COUPON_CODE',
        ];

        $result = coupon_apply($params);

        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('error', $result);
    }

    public function testDisabledCouponUsage()
    {
        save_option('enable_coupons', 0, 'shop');
        $params = [
            'coupon_code' => 'VALID_COUPON_CODE',
        ];
        $result = coupon_apply($params);

        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('error', $result);
    }



}
