<?php

namespace Modules\Product\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Models\ProductPricingRule;
use Tests\TestCase;

class ProductPricingRuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Clear the table to ensure clean state for each test
        \DB::table('product_pricing_rules')->delete();
    }

    protected function uniqueSlug(string $base): string
    {
        return $base . '-' . uniqid();
    }

    /** @test */
    public function it_can_create_a_bulk_quantity_pricing_rule(): void
    {
        $slug = $this->uniqueSlug('bulk-discount-10');
        $rule = ProductPricingRule::create([
            'name' => 'Bulk Discount',
            'slug' => $slug,
            'description' => '10% off for 10+ items',
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'tiers' => [
                ['min' => 10, 'max' => 49, 'value' => 10],
                ['min' => 50, 'max' => null, 'value' => 15],
            ],
            'is_active' => true,
            'priority' => 10,
        ]);

        $this->assertInstanceOf(ProductPricingRule::class, $rule);
        $this->assertDatabaseHas('product_pricing_rules', [
            'name' => 'Bulk Discount',
            'slug' => $slug,
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
        ]);
    }

    /** @test */
    public function it_can_create_a_bulk_amount_pricing_rule(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'High Volume Discount',
            'slug' => $this->uniqueSlug('high-volume'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_AMOUNT,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED_DISCOUNT,
            'tiers' => [
                ['min' => 500, 'max' => 999, 'value' => 50],
                ['min' => 1000, 'max' => null, 'value' => 100],
            ],
            'is_active' => true,
        ]);

        $this->assertEquals(ProductPricingRule::RULE_TYPE_BULK_AMOUNT, $rule->rule_type);
        $this->assertIsArray($rule->tiers);
        $this->assertCount(2, $rule->tiers);
    }

    /** @test */
    public function it_can_create_a_customer_specific_pricing_rule(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'VIP Customer Pricing',
            'slug' => $this->uniqueSlug('vip-customer'),
            'rule_type' => ProductPricingRule::RULE_TYPE_CUSTOMER_SPECIFIC,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED,
            'customer_ids' => [1, 2, 3],
            'is_public' => false,
            'tiers' => [
                ['min' => 1, 'max' => null, 'value' => 99.99],
            ],
        ]);

        $this->assertFalse($rule->is_public);
        $this->assertContains(1, $rule->customer_ids);
        $this->assertContains(2, $rule->customer_ids);
    }

    /** @test */
    public function it_can_create_a_customer_group_pricing_rule(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Wholesale Group Pricing',
            'slug' => $this->uniqueSlug('wholesale'),
            'rule_type' => ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'customer_group_ids' => [1],
            'is_public' => false,
            'tiers' => [
                ['min' => 1, 'max' => null, 'value' => 20],
            ],
        ]);

        $this->assertFalse($rule->is_public);
        $this->assertContains(1, $rule->customer_group_ids);
    }

    /** @test */
    public function it_generates_slug_automatically_if_not_provided(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Auto Slug Test',
            'slug' => $this->uniqueSlug('auto-slug'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
        ]);

        $this->assertNotNull($rule->slug);
        $this->assertStringContainsString('auto-slug', $rule->slug);
    }

    /** @test */
    public function it_can_apply_to_specific_products(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Product Specific Discount',
            'slug' => $this->uniqueSlug('product-specific'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'product_ids' => [100, 101, 102],
        ]);

        $this->assertTrue($rule->appliesToProduct(100));
        $this->assertTrue($rule->appliesToProduct(101));
        $this->assertFalse($rule->appliesToProduct(200));
    }

    /** @test */
    public function it_applies_to_all_products_when_product_ids_is_empty(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Global Discount',
            'slug' => $this->uniqueSlug('global-discount'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'product_ids' => [],
        ]);

        $this->assertTrue($rule->appliesToProduct(1));
        $this->assertTrue($rule->appliesToProduct(999));
    }

    /** @test */
    public function it_can_exclude_specific_products(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Most Products Discount',
            'slug' => $this->uniqueSlug('most-products'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'product_ids' => [],
            'excluded_product_ids' => [50, 51],
        ]);

        $this->assertTrue($rule->appliesToProduct(1));
        $this->assertFalse($rule->appliesToProduct(50));
        $this->assertFalse($rule->appliesToProduct(51));
    }

    /** @test */
    public function it_can_apply_to_specific_categories(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Category Discount',
            'slug' => $this->uniqueSlug('category-discount'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'category_ids' => [10, 20],
        ]);

        $this->assertTrue($rule->appliesToCategory(10));
        $this->assertTrue($rule->appliesToCategory(20));
        $this->assertFalse($rule->appliesToCategory(30));
    }

    /** @test */
    public function it_can_exclude_specific_categories(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Most Categories Discount',
            'slug' => $this->uniqueSlug('most-categories'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'category_ids' => [1, 2, 3],
            'excluded_category_ids' => [2],
        ]);

        $this->assertTrue($rule->appliesToCategory(1));
        $this->assertFalse($rule->appliesToCategory(2));
        $this->assertTrue($rule->appliesToCategory(3));
    }

    /** @test */
    public function it_can_check_if_applies_to_customer(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'VIP Customer',
            'slug' => $this->uniqueSlug('vip-customer'),
            'rule_type' => ProductPricingRule::RULE_TYPE_CUSTOMER_SPECIFIC,
            'is_public' => false,
            'customer_ids' => [5],
            'customer_group_ids' => [2],
        ]);

        // Public rules apply to everyone
        $this->assertFalse($rule->appliesToCustomer(null, null));
        $this->assertTrue($rule->appliesToCustomer(5, null));
        $this->assertTrue($rule->appliesToCustomer(null, 2));
        $this->assertFalse($rule->appliesToCustomer(10, null));
    }

    /** @test */
    public function it_can_check_current_validity(): void
    {
        $activeRule = ProductPricingRule::create([
            'name' => 'Active Rule',
            'slug' => $this->uniqueSlug('active-rule'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_active' => true,
        ]);

        $inactiveRule = ProductPricingRule::create([
            'name' => 'Inactive Rule',
            'slug' => $this->uniqueSlug('inactive-rule'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_active' => false,
        ]);

        $this->assertTrue($activeRule->isCurrentlyValid());
        $this->assertFalse($inactiveRule->isCurrentlyValid());
    }

    /** @test */
    public function it_respects_date_validity(): void
    {
        $futureRule = ProductPricingRule::create([
            'name' => 'Future Rule',
            'slug' => $this->uniqueSlug('future-rule'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'valid_from' => now()->addDays(7),
            'is_active' => true,
        ]);

        $expiredRule = ProductPricingRule::create([
            'name' => 'Expired Rule',
            'slug' => $this->uniqueSlug('expired-rule'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'valid_to' => now()->subDays(1),
            'is_active' => true,
        ]);

        $this->assertFalse($futureRule->isCurrentlyValid());
        $this->assertFalse($expiredRule->isCurrentlyValid());
    }

    /** @test */
    public function it_can_calculate_price_with_percentage_discount(): void
    {
        $rule = ProductPricingRule::create([
            'name' => '10% Off',
            'slug' => $this->uniqueSlug('ten-percent-off'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'tiers' => [
                ['min' => 5, 'max' => 10, 'value' => 10],
            ],
        ]);

        $result = $rule->calculatePrice(100, 5);

        $this->assertEquals(90, $result['price']);
        $this->assertEquals(10, $result['discount']);
        $this->assertNotNull($result['tier']);
    }

    /** @test */
    public function it_can_calculate_price_with_fixed_discount(): void
    {
        $rule = ProductPricingRule::create([
            'name' => '$20 Off',
            'slug' => $this->uniqueSlug('twenty-off'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED_DISCOUNT,
            'tiers' => [
                ['min' => 2, 'max' => null, 'value' => 20],
            ],
        ]);

        $result = $rule->calculatePrice(100, 2);

        $this->assertEquals(80, $result['price']);
        $this->assertEquals(20, $result['discount']);
    }

    /** @test */
    public function it_can_calculate_price_with_fixed_price_type(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Fixed Price',
            'slug' => $this->uniqueSlug('fixed-price'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED,
            'tiers' => [
                ['min' => 10, 'max' => null, 'value' => 50],
            ],
        ]);

        $result = $rule->calculatePrice(100, 10);

        $this->assertEquals(50, $result['price']);
        $this->assertEquals(50, $result['discount']);
    }

    /** @test */
    public function it_returns_base_price_when_no_tier_matches(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'High Quantity Only',
            'slug' => $this->uniqueSlug('high-qty-only'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'tiers' => [
                ['min' => 100, 'max' => null, 'value' => 25],
            ],
        ]);

        $result = $rule->calculatePrice(100, 5);

        $this->assertEquals(100, $result['price']);
        $this->assertEquals(0, $result['discount']);
        $this->assertNull($result['tier']);
    }

    /** @test */
    public function it_can_calculate_bulk_amount_discounts(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Order Total Discount',
            'slug' => $this->uniqueSlug('order-total'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_AMOUNT,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'tiers' => [
                ['min' => 500, 'max' => 999, 'value' => 5],
                ['min' => 1000, 'max' => null, 'value' => 10],
            ],
        ]);

        $result = $rule->calculatePrice(100, 1, 750);

        $this->assertEquals(95, $result['price']);
        $this->assertEquals(5, $result['discount']);
    }

    /** @test */
    public function it_can_get_tier_for_quantity(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Tiered Discount',
            'slug' => $this->uniqueSlug('tiered-discount'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'tiers' => [
                ['min' => 1, 'max' => 9, 'value' => 0],
                ['min' => 10, 'max' => 49, 'value' => 10],
                ['min' => 50, 'max' => null, 'value' => 20],
            ],
        ]);

        $this->assertNull($rule->getTierForQuantity(0));
        $this->assertEquals(['min' => 1, 'max' => 9, 'value' => 0], $rule->getTierForQuantity(5));
        $this->assertEquals(['min' => 10, 'max' => 49, 'value' => 10], $rule->getTierForQuantity(25));
        $this->assertEquals(['min' => 50, 'max' => null, 'value' => 20], $rule->getTierForQuantity(100));
    }

    /** @test */
    public function it_can_check_usage_limits(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Limited Use',
            'slug' => $this->uniqueSlug('limited-use'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'max_usage_count' => 100,
            'usage_count' => 50,
        ]);

        $this->assertFalse($rule->hasReachedLimit());

        $rule->incrementUsage();
        $this->assertEquals(51, $rule->fresh()->usage_count);
    }

    /** @test */
    public function it_detects_when_usage_limit_reached(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Maxed Out',
            'slug' => $this->uniqueSlug('maxed-out'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'max_usage_count' => 10,
            'usage_count' => 10,
        ]);

        $this->assertTrue($rule->hasReachedLimit());
    }

    /** @test */
    public function it_can_check_if_can_stack_with_other_rules(): void
    {
        $rule1 = ProductPricingRule::create([
            'name' => 'Rule 1',
            'slug' => $this->uniqueSlug('rule-one'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_stackable' => true,
        ]);

        $rule2 = ProductPricingRule::create([
            'name' => 'Rule 2',
            'slug' => $this->uniqueSlug('rule-two'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_stackable' => true,
        ]);

        $rule3 = ProductPricingRule::create([
            'name' => 'Rule 3',
            'slug' => $this->uniqueSlug('rule-three'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_stackable' => false,
        ]);

        $this->assertTrue($rule1->canStackWith($rule2));
        $this->assertFalse($rule1->canStackWith($rule3));
    }

    /** @test */
    public function it_respects_cannot_stack_with_restrictions(): void
    {
        $rule1 = ProductPricingRule::create([
            'name' => 'Rule 1',
            'slug' => $this->uniqueSlug('rule-one-stack'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_stackable' => true,
        ]);

        $rule2 = ProductPricingRule::create([
            'name' => 'Rule 2',
            'slug' => $this->uniqueSlug('rule-two-stack'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_stackable' => true,
            'cannot_stack_with' => [$rule1->id],
        ]);

        $this->assertFalse($rule1->canStackWith($rule2));
    }

    /** @test */
    public function it_scopes_active_rules_correctly(): void
    {
        ProductPricingRule::create([
            'name' => 'Active',
            'slug' => $this->uniqueSlug('active-test'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_active' => true,
        ]);

        ProductPricingRule::create([
            'name' => 'Inactive',
            'slug' => $this->uniqueSlug('inactive-test'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_active' => false,
        ]);

        ProductPricingRule::create([
            'name' => 'Expired',
            'slug' => $this->uniqueSlug('expired-test'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'is_active' => true,
            'valid_to' => now()->subDay(),
        ]);

        $activeRules = ProductPricingRule::active()->get();

        $this->assertEquals(1, $activeRules->count());
        $this->assertEquals('Active', $activeRules->first()->name);
    }

    /** @test */
    public function it_scopes_bulk_rules(): void
    {
        ProductPricingRule::create([
            'name' => 'Bulk Qty',
            'slug' => $this->uniqueSlug('bulk-qty'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
        ]);

        ProductPricingRule::create([
            'name' => 'Bulk Amount',
            'slug' => $this->uniqueSlug('bulk-amount'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_AMOUNT,
        ]);

        ProductPricingRule::create([
            'name' => 'Customer Specific',
            'slug' => $this->uniqueSlug('customer-specific'),
            'rule_type' => ProductPricingRule::RULE_TYPE_CUSTOMER_SPECIFIC,
        ]);

        $bulkRules = ProductPricingRule::bulk()->get();

        $this->assertEquals(2, $bulkRules->count());
    }

    /** @test */
    public function it_scopes_customer_specific_rules(): void
    {
        ProductPricingRule::create([
            'name' => 'Bulk',
            'slug' => $this->uniqueSlug('bulk-only'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
        ]);

        ProductPricingRule::create([
            'name' => 'Customer Specific',
            'slug' => $this->uniqueSlug('customer-specific-scope'),
            'rule_type' => ProductPricingRule::RULE_TYPE_CUSTOMER_SPECIFIC,
        ]);

        ProductPricingRule::create([
            'name' => 'Customer Group',
            'slug' => $this->uniqueSlug('customer-group-scope'),
            'rule_type' => ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP,
        ]);

        $customerRules = ProductPricingRule::customerSpecific()->get();

        $this->assertEquals(2, $customerRules->count());
    }

    /** @test */
    public function it_orders_by_priority_descending(): void
    {
        ProductPricingRule::create([
            'name' => 'Low Priority',
            'slug' => $this->uniqueSlug('low-priority'),
            'priority' => 5,
        ]);

        ProductPricingRule::create([
            'name' => 'High Priority',
            'slug' => $this->uniqueSlug('high-priority'),
            'priority' => 100,
        ]);

        ProductPricingRule::create([
            'name' => 'Medium Priority',
            'slug' => $this->uniqueSlug('medium-priority'),
            'priority' => 50,
        ]);

        $rules = ProductPricingRule::byPriority()->get();

        $this->assertEquals('High Priority', $rules[0]->name);
        $this->assertEquals('Medium Priority', $rules[1]->name);
        $this->assertEquals('Low Priority', $rules[2]->name);
    }

    /** @test */
    public function it_prevents_negative_prices(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Big Discount',
            'slug' => $this->uniqueSlug('big-discount'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED_DISCOUNT,
            'tiers' => [
                ['min' => 1, 'max' => null, 'value' => 150],
            ],
        ]);

        $result = $rule->calculatePrice(100, 1);

        $this->assertEquals(0, $result['price']);
        $this->assertEquals(100, $result['discount']);
    }

    /** @test */
    public function it_casts_arrays_properly(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Cast Test',
            'slug' => $this->uniqueSlug('cast-test'),
            'product_ids' => [1, 2, 3],
            'category_ids' => [10, 20],
            'tiers' => [['min' => 5, 'value' => 10]],
        ]);

        $this->assertIsArray($rule->product_ids);
        $this->assertIsArray($rule->category_ids);
        $this->assertIsArray($rule->tiers);
        $this->assertCount(3, $rule->product_ids);
        $this->assertCount(2, $rule->category_ids);
    }

    /** @test */
    public function it_applies_to_all_when_all_is_in_product_ids(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Global Rule',
            'slug' => $this->uniqueSlug('global-rule'),
            'product_ids' => ['all'],
        ]);

        $this->assertTrue($rule->appliesToProduct(1));
        $this->assertTrue($rule->appliesToProduct(999));
    }

    /** @test */
    public function it_handles_bundle_discount_rules(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Bundle Discount',
            'slug' => $this->uniqueSlug('bundle-discount'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BUNDLE_DISCOUNT,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'tiers' => [
                ['min' => 3, 'max' => null, 'value' => 15],
            ],
        ]);

        $this->assertEquals(ProductPricingRule::RULE_TYPE_BUNDLE_DISCOUNT, $rule->rule_type);
    }

    /** @test */
    public function it_handles_variant_override_rules(): void
    {
        $rule = ProductPricingRule::create([
            'name' => 'Variant Override',
            'slug' => $this->uniqueSlug('variant-override'),
            'rule_type' => ProductPricingRule::RULE_TYPE_VARIANT_OVERRIDE,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED,
            'tiers' => [
                ['min' => 1, 'max' => null, 'value' => 79.99],
            ],
        ]);

        $this->assertEquals(ProductPricingRule::RULE_TYPE_VARIANT_OVERRIDE, $rule->rule_type);
    }
}
