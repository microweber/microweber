<?php

namespace Modules\Coupons\Tests\Unit;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Coupons\Models\Coupon;
use Modules\Shop\Tests\Unit\ShopTestHelperTrait;

class CouponValidationTest extends TestCase
{
    use ShopTestHelperTrait;

    protected function setUp(): void
    {
        parent::setUp();
        empty_cart();
        save_option('enable_coupons', 1, 'shop');
    }

    public function testMinimumCartAmountRequirement()
    {
        // Create coupon with $50 minimum cart amount
        $coupon = Coupon::create([
            'coupon_code' => 'MIN50TEST',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'total_amount' => 50, // Minimum cart amount
            'is_active' => 1
        ]);

        // Test with cart below minimum
        $this->_addProductToCart('Product 1', 20);
        $result = coupon_apply(['coupon_code' => 'MIN50TEST']);
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('minimum total amount', $result['message']);

        // Test with cart meeting minimum
        $this->_addProductToCart('Product 2', 35); // Total now $55
        $result = coupon_apply(['coupon_code' => 'MIN50TEST']);
        $this->assertArrayHasKey('success', $result);
    }

    public function testCouponDateValidity()
    {
        // First verify the Coupon model has date fields
        $this->assertTrue(Schema::hasColumn('cart_coupons', 'valid_from'));
        $this->assertTrue(Schema::hasColumn('cart_coupons', 'valid_to'));

        // Clean up any existing test coupons
        Coupon::where('coupon_code', 'LIKE', 'DATEDTEST%')->delete();

        // Create coupon with date restrictions using unique code
        $validFrom = now()->addDay();
        $validTo = now()->addDays(2);
        $uniqueCode = 'DATEDTEST_' . uniqid();

        $couponData = [
            'coupon_code' => $uniqueCode,
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
            'is_active' => 1
        ];

        // Debug the creation
        $coupon = Coupon::create($couponData);

        // Verify coupon dates
        $savedCoupon = Coupon::find($coupon->id);
        $this->assertEquals($validFrom->format('Y-m-d H:i:s'), $savedCoupon->valid_from);
        $this->assertEquals($validTo->format('Y-m-d H:i:s'), $savedCoupon->valid_to);

        // Test before valid date
        $this->_addProductToCart('Product 1', 50);
        $result = coupon_apply(['coupon_code' => $uniqueCode]);



        // Check response
        if ($result['success'] ?? false) {
            $this->fail('Coupon should not be valid before start date. Response: ' . json_encode($result));
        } else {
            $this->assertStringContainsString(
                'not valid',
                $result['message'] ?? json_encode($result),
                'Expected error about coupon not being valid yet. Response: ' . json_encode($result)
            );
        }

        // Test during valid period (mock current time)
        $this->travelTo(now()->addDay()->addHour());
        $result = coupon_apply(['coupon_code' => 'DATEDTEST']);
        $this->assertStringNotContainsString('not yet valid', $result['message'] ?? json_encode($result));
        $this->assertStringNotContainsString('expired', $result['message'] ?? json_encode($result));
        if (isset($result['success'])) {
            $this->assertTrue($result['success']);
        }
        $this->travelBack();

        // Test after expiration
        $this->travelTo(now()->addDays(3));
        $result = coupon_apply(['coupon_code' => $uniqueCode]);
        $this->assertStringContainsString('expired', $result['message'] ?? json_encode($result));
        if (isset($result['success'])) {
            $this->assertFalse($result['success']);
        }
        if (isset($result['error'])) {
            $this->assertTrue($result['error']);
        }
        $this->travelBack();
    }

    public function testProductSpecificRestrictions()
    {
        // Clean up any existing coupons
        Coupon::where('coupon_code', 'PRODUCTTEST')->delete();

        // Add test product and detect its actual ID
        $product = $this->_addProductToCart('Restricted Product', 30);
        $cartItems = app('cart_manager')->get_cart([]);
        $restrictedProductId = $cartItems[0]['rel_id'];
        $nonRestrictedProductId = $restrictedProductId + 1; // Ensure different ID


        // Verify cart contains exactly the restricted product
        $cartItems = app('cart_manager')->get_cart([]);
        $cartProductIds = array_column($cartItems, 'rel_id');
         $this->assertContains($restrictedProductId, $cartProductIds);
        $this->assertNotContains($nonRestrictedProductId, $cartProductIds);



        // Create coupon with correct product restriction
        $couponData = [
            'coupon_code' => 'PRODUCTTEST',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'product_ids' => (string)$restrictedProductId,
            'is_active' => 1
        ];
         $coupon = Coupon::create($couponData);

        // Test with restricted product in cart
        $cartBefore = app('cart_manager')->get_cart([]);
        $cartProductIds = array_column($cartBefore, 'rel_id');

        $result = coupon_apply(['coupon_code' => 'PRODUCTTEST']);

        $this->assertArrayHasKey('success', $result);

        // Test without restricted product
        empty_cart();
        $this->_addProductToCart('Non-Restricted Product', 30, ['id' => 1002]);

        // Get cart items properly
        $cartItems = app('cart_manager')->get_cart([]);
        $productIds = array_column($cartItems, 'product_id');

        // Debug which coupon is being loaded
        $appliedCoupon = Coupon::where('coupon_code', 'PRODUCTTEST')->first();

        $result = coupon_apply(['coupon_code' => 'PRODUCTTEST']);

        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('not applicable', $result['message']);
    }
}
