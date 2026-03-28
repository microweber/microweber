<?php

namespace Modules\Coupons\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Models\CouponLog;
use Modules\Coupons\Services\CouponService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CouponAdvancedRulesTest extends TestCase
{
    private $couponService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->couponService = app(CouponService::class);
        Session::flush();
        Coupon::query()->delete();
        CouponLog::query()->delete();
        DB::table('cart_coupon_logs')->delete();
    }

    #[Test]
    public function it_validates_stacking_rules(): void
    {
        // Create a non-stackable coupon
        $nonStackable = Coupon::create([
            'coupon_name' => 'Non-Stackable',
            'coupon_code' => 'NONSTACK',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'is_stackable' => false,
        ]);

        // Create a stackable coupon
        $stackable = Coupon::create([
            'coupon_name' => 'Stackable',
            'coupon_code' => 'STACK',
            'discount_type' => 'fixed_amount',
            'discount_value' => 5,
            'is_active' => 1,
            'is_stackable' => true,
        ]);

        // Apply non-stackable first
        $result1 = $this->couponService->applyCoupon('NONSTACK', 100);
        $this->assertTrue($result1['success']);

        // Try to apply another non-stackable (should fail)
        $context = ['cart_product_ids' => [], 'cart_category_ids' => []];
        $result2 = $this->couponService->canApplyCoupon('STACK', 100, $context);
        $this->assertFalse($result2['can_apply']);
        $this->assertStringContainsString('cannot be combined', $result2['message']);
    }

    #[Test]
    public function it_validates_customer_group_restrictions(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'VIP Only',
            'coupon_code' => 'VIP10',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'customer_group_ids' => '1,2', // Only groups 1 and 2
        ]);

        // Test with allowed group
        $result1 = $this->couponService->canApplyCoupon('VIP10', 100, [
            'customer_group_id' => 1,
            'cart_product_ids' => [],
            'cart_category_ids' => [],
        ]);
        $this->assertTrue($result1['can_apply']);

        // Test with disallowed group
        $result2 = $this->couponService->canApplyCoupon('VIP10', 100, [
            'customer_group_id' => 5,
            'cart_product_ids' => [],
            'cart_category_ids' => [],
        ]);
        $this->assertFalse($result2['can_apply']);
        $this->assertStringContainsString('customer group', $result2['message']);
    }

    #[Test]
    public function it_validates_category_restrictions(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Electronics Only',
            'coupon_code' => 'ELEC20',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'is_active' => 1,
            'category_ids' => '1,2,3', // Only categories 1, 2, 3
        ]);

        // Test with allowed category
        $result1 = $this->couponService->canApplyCoupon('ELEC20', 100, [
            'cart_category_ids' => [1],
            'cart_product_ids' => [],
        ]);
        $this->assertTrue($result1['can_apply']);

        // Test with disallowed category
        $result2 = $this->couponService->canApplyCoupon('ELEC20', 100, [
            'cart_category_ids' => [10, 11],
            'cart_product_ids' => [],
        ]);
        $this->assertFalse($result2['can_apply']);
        $this->assertStringContainsString('categories', $result2['message']);
    }

    #[Test]
    public function it_validates_product_exclusions(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Excluding Sale Items',
            'coupon_code' => 'NOSALE',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'excluded_product_ids' => '100,101,102', // Exclude these products
        ]);

        // Test with all excluded products (should fail)
        $result = $this->couponService->canApplyCoupon('NOSALE', 100, [
            'cart_product_ids' => [100, 101],
            'cart_category_ids' => [],
        ]);
        $this->assertFalse($result['can_apply']);
        $this->assertStringContainsString('cannot be applied', $result['message']);

        // Test with mixed products (should succeed)
        $result2 = $this->couponService->canApplyCoupon('NOSALE', 100, [
            'cart_product_ids' => [100, 200],
            'cart_category_ids' => [],
        ]);
        $this->assertTrue($result2['can_apply']);
    }

    #[Test]
    public function it_validates_first_time_customer_restriction(): void
    {
        // Skip if cart_orders table doesn't have user_id column
        if (!Schema::hasColumn('cart_orders', 'user_id')) {
            $this->markTestSkipped('cart_orders table missing user_id column');
        }

        // Create a user with completed orders
        $user = \App\Models\User::factory()->create();
        DB::table('cart_orders')->insert([
            'user_id' => $user->id,
            'email' => $user->email,
            'order_status' => 'completed',
            'amount' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $coupon = Coupon::create([
            'coupon_name' => 'Welcome First Order',
            'coupon_code' => 'WELCOME',
            'discount_type' => 'fixed_amount',
            'discount_value' => 15,
            'is_active' => 1,
            'first_time_only' => true,
        ]);

        // Test with returning customer
        $result1 = $this->couponService->canApplyCoupon('WELCOME', 100, [
            'user_id' => $user->id,
            'customer_email' => $user->email,
            'cart_product_ids' => [],
            'cart_category_ids' => [],
        ]);
        $this->assertFalse($result1['can_apply']);
        $this->assertStringContainsString('first-time', $result1['message']);

        // Test with new customer (no user ID, no email in orders)
        $result2 = $this->couponService->canApplyCoupon('WELCOME', 100, [
            'user_id' => null,
            'customer_email' => 'newcustomer@example.com',
            'cart_product_ids' => [],
            'cart_category_ids' => [],
        ]);
        $this->assertTrue($result2['can_apply']);
    }

    #[Test]
    public function it_enforces_maximum_discount_cap(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => '50% Off Max $20',
            'coupon_code' => '50MAX20',
            'discount_type' => 'percentage',
            'discount_value' => 50,
            'max_discount_amount' => 20,
            'is_active' => 1,
        ]);

        // Calculate discount on $100 cart - should be capped at $20
        $discount = $coupon->calculateDiscount(100);
        $this->assertEquals(20, $discount);

        // Calculate discount on $30 cart - should be $15 (50% of 30)
        $discount2 = $coupon->calculateDiscount(30);
        $this->assertEquals(15, $discount2);
    }

    #[Test]
    public function it_handles_free_shipping_coupon(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Free Shipping',
            'coupon_code' => 'FREESHIP',
            'discount_type' => 'free_shipping',
            'discount_value' => 0,
            'is_active' => 1,
            'free_shipping' => true,
        ]);

        // Free shipping coupons should return 0 discount
        $discount = $coupon->calculateDiscount(100);
        $this->assertEquals(0, $discount);

        // But should be recognized as free shipping
        $this->assertTrue($coupon->free_shipping);
    }

    #[Test]
    public function it_tracks_usage_statistics(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Trackable Coupon',
            'coupon_code' => 'TRACK',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'uses_per_coupon' => 100,
        ]);

        // Get initial stats
        $stats = $coupon->getUsageStats();
        $this->assertEquals(0, $stats['total_uses']);
        $this->assertEquals(100, $stats['remaining_uses']);
        $this->assertEquals(0, $stats['usage_percentage']);

        // Simulate usage by creating a log entry
        CouponLog::create([
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->coupon_code,
            'customer_email' => 'test@example.com',
            'customer_ip' => '127.0.0.1',
            'uses_count' => 1,
        ]);

        // Increment usage
        $coupon->incrementUsage(10);

        // Refresh and check updated stats
        $coupon->refresh();
        $stats2 = $coupon->getUsageStats();
        $this->assertEquals(1, $stats2['total_uses']); // Uses logs()->count()
        $this->assertEquals(10, $stats2['total_discount_given']);
        $this->assertEquals(99, $stats2['remaining_uses']);
        $this->assertEquals(1, $stats2['usage_percentage']);
    }

    #[Test]
    public function it_generates_unique_coupon_codes(): void
    {
        // Create first coupon
        $code1 = $this->couponService->generateUniqueCouponCode('TEST', 8);
        $this->assertStringStartsWith('TEST-', $code1);
        $this->assertEquals(13, strlen($code1)); // TEST- + 8 chars

        // Save it
        Coupon::create([
            'coupon_name' => 'Test Coupon',
            'coupon_code' => $code1,
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
        ]);

        // Generate another - should be different
        $code2 = $this->couponService->generateUniqueCouponCode('TEST', 8);
        $this->assertNotEquals($code1, $code2);
        $this->assertStringStartsWith('TEST-', $code2);
    }

    #[Test]
    public function it_calculates_discount_for_specific_items(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => '20% Off Specific',
            'coupon_code' => 'SPEC20',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'is_active' => 1,
            'product_ids' => '1,2', // Only for products 1 and 2
        ]);

        $items = [
            ['product_id' => 1, 'price' => 50, 'qty' => 1], // Applicable
            ['product_id' => 2, 'price' => 30, 'qty' => 1], // Applicable
            ['product_id' => 3, 'price' => 20, 'qty' => 1], // Not applicable
        ];

        // Discount should be 20% of (50 + 30) = 16
        $discount = $coupon->calculateDiscountForItems($items);
        $this->assertEquals(16, $discount);
    }

    #[Test]
    public function it_validates_comprehensive_context(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Complex Rules',
            'coupon_code' => 'COMPLEX',
            'discount_type' => 'fixed_amount',
            'discount_value' => 25,
            'is_active' => 1,
            'is_stackable' => false,
            'customer_group_ids' => '1,2',
            'category_ids' => '5,6',
            'first_time_only' => true,
        ]);

        // Comprehensive validation with all context
        $result = $this->couponService->canApplyCoupon('COMPLEX', 100, [
            'user_id' => null,
            'customer_email' => 'new@example.com',
            'customer_group_id' => 1,
            'cart_product_ids' => [10, 20],
            'cart_category_ids' => [5, 6],
        ]);

        $this->assertTrue($result['can_apply']);

        // Test with wrong customer group
        $result2 = $this->couponService->canApplyCoupon('COMPLEX', 100, [
            'user_id' => null,
            'customer_email' => 'new@example.com',
            'customer_group_id' => 10,
            'cart_product_ids' => [10, 20],
            'cart_category_ids' => [5, 6],
        ]);

        $this->assertFalse($result2['can_apply']);
    }
}
