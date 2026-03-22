<?php

namespace Modules\Coupons\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Services\CouponService;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AdvancedDiscountRulesTest extends TestCase
{
    private $couponService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->couponService = app(CouponService::class);
        Session::flush();
        Coupon::truncate();
    }

    #[Test]
    public function it_calculates_bogo_discount_correctly(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Buy 2 Get 1 Free',
            'coupon_code' => 'B2G1',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'bogo_enabled' => true,
            'bogo_buy_quantity' => 2,
            'bogo_get_quantity' => 1,
            'bogo_discount_percent' => 100,
        ]);

        $items = [
            ['product_id' => 1, 'price' => 50, 'qty' => 3],
        ];

        // Buy 3 items, get 1 free (1 * 50 = 50)
        $discount = $coupon->calculateBogoDiscount($items);
        $this->assertEquals(50, $discount);

        // Buy 4 items, get 1 free
        $items[0]['qty'] = 4;
        $discount = $coupon->calculateBogoDiscount($items);
        $this->assertEquals(50, $discount);

        // Buy 6 items, get 2 free
        $items[0]['qty'] = 6;
        $discount = $coupon->calculateBogoDiscount($items);
        $this->assertEquals(100, $discount);
    }

    #[Test]
    public function it_calculates_bogo_discount_with_partial_percentage(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Buy 2 Get 1 50%',
            'coupon_code' => 'B2G150',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'bogo_enabled' => true,
            'bogo_buy_quantity' => 2,
            'bogo_get_quantity' => 1,
            'bogo_discount_percent' => 50,
        ]);

        $items = [
            ['product_id' => 1, 'price' => 100, 'qty' => 3],
        ];

        // Buy 3 items, get 1 at 50% off (1 * 100 * 0.5 = 50)
        $discount = $coupon->calculateBogoDiscount($items);
        $this->assertEquals(50, $discount);
    }

    #[Test]
    public function it_returns_zero_bogo_discount_when_disabled(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Regular Coupon',
            'coupon_code' => 'REGULAR',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'bogo_enabled' => false,
        ]);

        $items = [
            ['product_id' => 1, 'price' => 50, 'qty' => 3],
        ];

        $discount = $coupon->calculateBogoDiscount($items);
        $this->assertEquals(0, $discount);
    }

    #[Test]
    public function it_gets_applicable_tier_for_volume_discounts(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Tiered Discount',
            'coupon_code' => 'TIERED',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'tiered_enabled' => true,
            'tiered_rules' => [
                ['type' => 'amount', 'threshold' => 50, 'discount_type' => 'percentage', 'discount_value' => 5],
                ['type' => 'amount', 'threshold' => 100, 'discount_type' => 'percentage', 'discount_value' => 10],
                ['type' => 'amount', 'threshold' => 200, 'discount_type' => 'percentage', 'discount_value' => 15],
            ],
        ]);

        // Cart total $30 - no tier
        $tier = $coupon->getApplicableTier(30);
        $this->assertNull($tier);

        // Cart total $75 - should get 5% tier
        $tier = $coupon->getApplicableTier(75);
        $this->assertNotNull($tier);
        $this->assertEquals(5, $tier['discount_value']);

        // Cart total $150 - should get 10% tier
        $tier = $coupon->getApplicableTier(150);
        $this->assertNotNull($tier);
        $this->assertEquals(10, $tier['discount_value']);

        // Cart total $250 - should get 15% tier
        $tier = $coupon->getApplicableTier(250);
        $this->assertNotNull($tier);
        $this->assertEquals(15, $tier['discount_value']);
    }

    #[Test]
    public function it_calculates_tiered_discount_correctly(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Tiered Discount',
            'coupon_code' => 'TIERED',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'tiered_enabled' => true,
            'tiered_rules' => [
                ['type' => 'amount', 'threshold' => 100, 'discount_type' => 'percentage', 'discount_value' => 10],
                ['type' => 'amount', 'threshold' => 200, 'discount_type' => 'percentage', 'discount_value' => 15],
            ],
        ]);

        // $150 cart with 10% tier = $15
        $discount = $coupon->calculateTieredDiscount(150);
        $this->assertEquals(15, $discount);

        // $250 cart with 15% tier = $37.50
        $discount = $coupon->calculateTieredDiscount(250);
        $this->assertEquals(37.50, $discount);
    }

    #[Test]
    public function it_calculates_tiered_discount_with_quantity_thresholds(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Quantity Tiered',
            'coupon_code' => 'QTYTIER',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'tiered_enabled' => true,
            'tiered_rules' => [
                ['type' => 'quantity', 'threshold' => 5, 'discount_type' => 'percentage', 'discount_value' => 5],
                ['type' => 'quantity', 'threshold' => 10, 'discount_type' => 'percentage', 'discount_value' => 10],
            ],
        ]);

        // 7 items with 5 item threshold = 5%
        $tier = $coupon->getApplicableTier(100, 7);
        $this->assertNotNull($tier);
        $this->assertEquals(5, $tier['discount_value']);

        // 12 items with 10 item threshold = 10%
        $tier = $coupon->getApplicableTier(100, 12);
        $this->assertNotNull($tier);
        $this->assertEquals(10, $tier['discount_value']);
    }

    #[Test]
    public function it_applies_max_discount_cap_to_tiered_discounts(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Tiered With Cap',
            'coupon_code' => 'TIEREDCAP',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'tiered_enabled' => true,
            'tiered_rules' => [
                ['type' => 'amount', 'threshold' => 100, 'discount_type' => 'percentage', 'discount_value' => 50, 'max_discount' => 30],
            ],
        ]);

        // $100 * 50% = $50, but capped at $30
        $discount = $coupon->calculateTieredDiscount(100);
        $this->assertEquals(30, $discount);
    }

    #[Test]
    public function it_evaluates_conditional_rules_for_cart_total(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Conditional Discount',
            'coupon_code' => 'CONDITIONAL',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'conditional_rules' => [
                [
                    'condition' => 'cart_total',
                    'operator' => '>',
                    'value' => 100,
                    'discount_type' => 'percentage',
                    'discount_value' => 10,
                ],
            ],
        ]);

        $context = [
            'cart_total' => 150,
            'item_count' => 3,
            'product_ids' => [1, 2],
            'category_ids' => [1],
        ];

        $result = $coupon->evaluateConditionalRules($context);
        $this->assertTrue($result['applicable']);
        $this->assertEquals(15, $result['discount']); // 10% of 150

        // Test with cart total below threshold
        $context['cart_total'] = 50;
        $result = $coupon->evaluateConditionalRules($context);
        $this->assertFalse($result['applicable']);
        $this->assertEquals(0, $result['discount']);
    }

    #[Test]
    public function it_evaluates_conditional_rules_for_item_count(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Conditional Count',
            'coupon_code' => 'CONDCOUNT',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'conditional_rules' => [
                [
                    'condition' => 'item_count',
                    'operator' => '>=',
                    'value' => 5,
                    'discount_type' => 'fixed',
                    'discount_value' => 20,
                ],
            ],
        ]);

        $context = [
            'cart_total' => 200,
            'item_count' => 6,
            'product_ids' => [1, 2],
            'category_ids' => [1],
        ];

        $result = $coupon->evaluateConditionalRules($context);
        $this->assertTrue($result['applicable']);
        $this->assertEquals(20, $result['discount']);

        // Test with fewer items
        $context['item_count'] = 3;
        $result = $coupon->evaluateConditionalRules($context);
        $this->assertFalse($result['applicable']);
    }

    #[Test]
    public function it_evaluates_conditional_rules_for_specific_products(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Conditional Product',
            'coupon_code' => 'CONDPROD',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'conditional_rules' => [
                [
                    'condition' => 'specific_product',
                    'operator' => 'has',
                    'value' => '5',
                    'discount_type' => 'percentage',
                    'discount_value' => 15,
                ],
            ],
        ]);

        $context = [
            'cart_total' => 100,
            'item_count' => 3,
            'product_ids' => [1, 5, 10],
            'category_ids' => [1],
        ];

        $result = $coupon->evaluateConditionalRules($context);
        $this->assertTrue($result['applicable']);
        $this->assertEquals(15, $result['discount']);

        // Test without specific product
        $context['product_ids'] = [1, 2, 3];
        $result = $coupon->evaluateConditionalRules($context);
        $this->assertFalse($result['applicable']);
    }

    #[Test]
    public function it_evaluates_conditional_rules_for_specific_categories(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Conditional Category',
            'coupon_code' => 'CONDCAT',
            'discount_type' => 'fixed_amount',
            'discount_value' => 0,
            'is_active' => 1,
            'conditional_rules' => [
                [
                    'condition' => 'specific_category',
                    'operator' => 'has',
                    'value' => '2',
                    'discount_type' => 'fixed',
                    'discount_value' => 10,
                ],
            ],
        ]);

        $context = [
            'cart_total' => 100,
            'item_count' => 3,
            'product_ids' => [1, 2, 3],
            'category_ids' => [1, 2],
        ];

        $result = $coupon->evaluateConditionalRules($context);
        $this->assertTrue($result['applicable']);
        $this->assertEquals(10, $result['discount']);

        // Test without specific category
        $context['category_ids'] = [1, 3];
        $result = $coupon->evaluateConditionalRules($context);
        $this->assertFalse($result['applicable']);
    }

    #[Test]
    public function it_validates_min_items_requirement(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Min Items Coupon',
            'coupon_code' => 'MINITEMS',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'min_items_count' => 3,
        ]);

        $this->assertTrue($coupon->meetsMinItemsRequirement(5));
        $this->assertTrue($coupon->meetsMinItemsRequirement(3));
        $this->assertFalse($coupon->meetsMinItemsRequirement(2));

        // Test with no min set
        $couponNoMin = Coupon::create([
            'coupon_name' => 'No Min Items Coupon',
            'coupon_code' => 'NOMINITEMS',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'min_items_count' => null,
        ]);
        $this->assertTrue($couponNoMin->meetsMinItemsRequirement(0)); // No min set
    }

    #[Test]
    public function it_validates_max_items_limit(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Max Items Coupon',
            'coupon_code' => 'MAXITEMS',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'max_items_count' => 5,
        ]);

        $this->assertFalse($coupon->exceedsMaxItemsLimit(3));
        $this->assertFalse($coupon->exceedsMaxItemsLimit(5));
        $this->assertTrue($coupon->exceedsMaxItemsLimit(6));

        // Test with no max set
        $couponNoMax = Coupon::create([
            'coupon_name' => 'No Max Items Coupon',
            'coupon_code' => 'NOMAXITEMS',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'max_items_count' => null,
        ]);
        $this->assertFalse($couponNoMax->exceedsMaxItemsLimit(10)); // No max set
    }

    #[Test]
    public function it_validates_advanced_rules_comprehensive(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Advanced Rules Coupon',
            'coupon_code' => 'ADVANCED',
            'discount_type' => 'fixed_amount',
            'discount_value' => 5,
            'is_active' => 1,
            'min_items_count' => 2,
            'max_items_count' => 10,
            'bogo_enabled' => true,
            'bogo_buy_quantity' => 2,
            'bogo_get_quantity' => 1,
            'bogo_discount_percent' => 100,
            'max_discount_per_order' => 50,
        ]);

        $context = [
            'cart_total' => 100,
            'item_count' => 5,
            'items' => [
                ['product_id' => 1, 'price' => 20, 'qty' => 5],
            ],
            'product_ids' => [1],
            'category_ids' => [1],
        ];

        $result = $coupon->validateAdvancedRules($context);
        $this->assertTrue($result['valid']);
        // With 5 items @ $20, BOGO (2 buy, 1 get):
        // Sets: 5 items / (2+1) = 1 full set + 2 remaining
        // Since remaining (2) >= buy (2), add another partial set: 2 sets total
        // Free items: 2 sets * 1 get = 2 free @ $20 = $40
        // Total discount = BOGO discount ($40)
        $this->assertEquals(40, $result['discount']);

        // Test with too many items
        $context['item_count'] = 15;
        $result = $coupon->validateAdvancedRules($context);
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('Maximum', $result['message']);

        // Test with too few items
        $context['item_count'] = 1;
        $result = $coupon->validateAdvancedRules($context);
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('Minimum', $result['message']);
    }

    #[Test]
    public function it_applies_max_discount_per_order_cap(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Capped Discount',
            'coupon_code' => 'CAPPED',
            'discount_type' => 'percentage',
            'discount_value' => 50,
            'is_active' => 1,
            'max_discount_per_order' => 25,
            'tiered_enabled' => true,
            'tiered_rules' => [
                ['type' => 'amount', 'threshold' => 0, 'discount_type' => 'percentage', 'discount_value' => 50],
            ],
        ]);

        $context = [
            'cart_total' => 100,
            'item_count' => 2,
            'items' => [],
            'product_ids' => [],
            'category_ids' => [],
        ];

        $result = $coupon->validateAdvancedRules($context);
        $this->assertTrue($result['valid']);
        // Tiered discount: 50% of $100 = $50, but capped at $25 per order
        $this->assertEquals(25, $result['discount']);
    }

    #[Test]
    public function it_calculates_multiple_advanced_rules_stacked(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Stacked Rules',
            'coupon_code' => 'STACKED',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
            'bogo_enabled' => true,
            'bogo_buy_quantity' => 3,
            'bogo_get_quantity' => 1,
            'tiered_enabled' => true,
            'tiered_rules' => [
                ['type' => 'amount', 'threshold' => 100, 'discount_type' => 'percentage', 'discount_value' => 5],
            ],
            'max_discount_per_order' => 30,
        ]);

        $context = [
            'cart_total' => 200,
            'item_count' => 5,
            'items' => [
                ['product_id' => 1, 'price' => 40, 'qty' => 5],
            ],
            'product_ids' => [1],
            'category_ids' => [1],
        ];

        $result = $coupon->validateAdvancedRules($context);
        $this->assertTrue($result['valid']);
        // Base ($10) + BOGO (1 free item @ $40) + Tiered (5% of $200 = $10) = $60
        // But capped at $30
        $this->assertEquals(30, $result['discount']);
    }

    #[Test]
    public function it_handles_coupon_without_advanced_rules(): void
    {
        $coupon = Coupon::create([
            'coupon_name' => 'Simple Coupon',
            'coupon_code' => 'SIMPLE',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1,
        ]);

        $context = [
            'cart_total' => 100,
            'item_count' => 2,
            'items' => [],
            'product_ids' => [],
            'category_ids' => [],
        ];

        $this->assertFalse($coupon->hasBogoRules());
        $this->assertFalse($coupon->hasTieredRules());
        $this->assertFalse($coupon->hasConditionalRules());

        $result = $coupon->validateAdvancedRules($context);
        $this->assertTrue($result['valid']);
        $this->assertEquals(0, $result['discount']); // No advanced discounts
    }
}
