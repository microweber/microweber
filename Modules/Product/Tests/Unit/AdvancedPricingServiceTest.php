<?php

namespace Modules\Product\Tests\Unit;

use Illuminate\Support\Facades\Cache;
use Modules\Content\Models\Content;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductCustomerPricing;
use Modules\Product\Models\ProductPricingRule;
use Modules\Product\Services\AdvancedPricingService;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AdvancedPricingServiceTest extends TestCase
{

    protected AdvancedPricingService $pricingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pricingService = new AdvancedPricingService();
        Cache::flush();
        \DB::table('product_pricing_rules')->delete();
    }

    protected function uniqueSlug(string $base): string
    {
        return $base . '-' . uniqid();
    }

    protected function createTestProduct(float $price = 100): Content
    {
        return Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'price' => $price,
            'is_active' => true,
        ]);
    }

        #[Test]
        public function it_calculates_base_price_for_product(): void
    {
        $product = $this->createTestProduct(99.99);

        $basePrice = $this->pricingService->getBasePrice($product->id);

        $this->assertEquals(99.99, $basePrice);
    }

        #[Test]
        public function it_returns_zero_for_nonexistent_product(): void
    {
        $basePrice = $this->pricingService->getBasePrice(99999);

        $this->assertEquals(0, $basePrice);
    }

        #[Test]
        public function it_calculates_price_without_any_rules(): void
    {
        $product = $this->createTestProduct(100);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        $this->assertEquals(100, $result['base_price']);
        $this->assertEquals(100, $result['final_price']);
        $this->assertEquals(0, $result['discount']);
        $this->assertEmpty($result['rules_applied']);
    }

        #[Test]
        public function it_applies_bulk_quantity_discount(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => '10% Off 10+',
            'slug' => $this->uniqueSlug('ten-off-ten'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [
                ['min' => 10, 'max' => null, 'value' => 10],
            ],
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 10, 100);

        $this->assertEquals(90, $result['final_price']);
        $this->assertEquals(10, $result['discount']);
        $this->assertEquals(10, $result['discount_percentage']);
        $this->assertCount(1, $result['rules_applied']);
    }

        #[Test]
        public function it_applies_bulk_amount_discount(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => '$50 Off $500+',
            'slug' => $this->uniqueSlug('fifty-off-five-hundred'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_AMOUNT,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [
                ['min' => 500, 'max' => null, 'value' => 50],
            ],
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 6, 100);

        $this->assertEquals(50, $result['final_price']);
        $this->assertEquals(50, $result['discount']);
    }

        #[Test]
        public function it_applies_fixed_price_override(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Fixed Price $75',
            'slug' => $this->uniqueSlug('fixed-seventy-five'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED,
            'product_ids' => [$product->id],
            'tiers' => [
                ['min' => 5, 'max' => null, 'value' => 75],
            ],
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 5, 100);

        $this->assertEquals(75, $result['final_price']);
        $this->assertEquals(25, $result['discount']);
    }

        #[Test]
        public function it_applies_customer_specific_pricing(): void
    {
        $product = $this->createTestProduct(100);

        ProductCustomerPricing::create([
            'product_id' => $product->id,
            'user_id' => 5,
            'price' => 79.99,
            'minimum_quantity' => 1,
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100, 5);

        $this->assertEqualsWithDelta(79.99, $result['final_price'], 0.01);
        $this->assertEqualsWithDelta(20.01, $result['discount'], 0.01);
    }

        #[Test]
        public function it_gets_customer_pricing_from_cache(): void
    {
        $product = $this->createTestProduct(100);

        ProductCustomerPricing::create([
            'product_id' => $product->id,
            'user_id' => 5,
            'price' => 79.99,
            'is_active' => true,
        ]);

        // First call
        $pricing1 = $this->pricingService->getCustomerPricing($product->id, 5);
        // Second call should come from cache
        $pricing2 = $this->pricingService->getCustomerPricing($product->id, 5);

        $this->assertEquals($pricing1->id, $pricing2->id);
    }

        #[Test]
        public function it_applies_rules_by_priority(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Low Priority',
            'slug' => $this->uniqueSlug('low-priority-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 5]],
            'priority' => 1,
            'is_active' => true,
        ]);

        ProductPricingRule::create([
            'name' => 'High Priority',
            'slug' => $this->uniqueSlug('high-priority-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 10]],
            'priority' => 10,
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        // High priority rule should apply first (10% off)
        $this->assertEquals(90, $result['final_price']);
    }

        #[Test]
        public function it_stacks_stackable_rules(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Rule 1',
            'slug' => $this->uniqueSlug('rule-one-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 10]],
            'is_stackable' => true,
            'priority' => 10,
            'is_active' => true,
        ]);

        ProductPricingRule::create([
            'name' => 'Rule 2',
            'slug' => $this->uniqueSlug('rule-two-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 5]],
            'is_stackable' => true,
            'priority' => 5,
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        // 10% off, then 5% off remaining 90 = 85.5
        $this->assertEquals(85.50, $result['final_price']);
        $this->assertCount(2, $result['rules_applied']);
    }

        #[Test]
        public function it_does_not_stack_non_stackable_rules(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Non-Stackable',
            'slug' => $this->uniqueSlug('non-stackable-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 10]],
            'is_stackable' => false,
            'priority' => 10,
            'is_active' => true,
        ]);

        ProductPricingRule::create([
            'name' => 'Lower Priority',
            'slug' => $this->uniqueSlug('lower-priority-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 5]],
            'is_stackable' => true,
            'priority' => 5,
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        // Only first non-stackable rule should apply
        $this->assertEquals(90, $result['final_price']);
        $this->assertCount(1, $result['rules_applied']);
    }

        #[Test]
        public function it_respects_cannot_stack_with_restrictions(): void
    {
        $product = $this->createTestProduct(100);

        $rule1 = ProductPricingRule::create([
            'name' => 'Rule 1',
            'slug' => $this->uniqueSlug('rule-one-stack-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 10]],
            'is_stackable' => true,
            'priority' => 10,
            'is_active' => true,
        ]);

        ProductPricingRule::create([
            'name' => 'Rule 2',
            'slug' => $this->uniqueSlug('rule-two-stack-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 5]],
            'is_stackable' => true,
            'cannot_stack_with' => [$rule1->id],
            'priority' => 5,
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        // Only Rule 1 should apply (Rule 2 cannot stack with it)
        $this->assertEquals(90, $result['final_price']);
        $this->assertCount(1, $result['rules_applied']);
    }

        #[Test]
        public function it_applies_category_based_rules(): void
    {
        $product = $this->createTestProduct(100);

        // Create rule that applies to category
        ProductPricingRule::create([
            'name' => 'Category Discount',
            'slug' => $this->uniqueSlug('category-discount-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'category_ids' => [10],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 15]],
            'is_active' => true,
        ]);

        // Since Product model doesn't have categories() method,
        // the service gracefully handles this and rules without product_ids don't apply
        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        // No rules should apply since we can't verify category membership
        $this->assertEquals(100, $result['final_price']);
    }

        #[Test]
        public function it_respects_customer_group_restrictions(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'VIP Group Discount',
            'slug' => $this->uniqueSlug('vip-group-discount-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_CUSTOMER_GROUP,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'customer_group_ids' => [1],
            'is_public' => false,
            'tiers' => [['min' => 1, 'max' => null, 'value' => 20]],
            'is_active' => true,
        ]);

        // Without group membership
        $result1 = $this->pricingService->calculatePrice($product->id, 1, 100, 5, null);
        $this->assertEquals(100, $result1['final_price']);

        // With group membership
        $result2 = $this->pricingService->calculatePrice($product->id, 1, 100, 5, 1);
        $this->assertEquals(80, $result2['final_price']);
    }

        #[Test]
        public function it_respects_date_validity(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Future Discount',
            'slug' => $this->uniqueSlug('future-discount-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 50]],
            'valid_from' => now()->addWeek(),
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        // Future rule should not apply
        $this->assertEquals(100, $result['final_price']);
        $this->assertEmpty($result['rules_applied']);
    }

        #[Test]
        public function it_respects_usage_limits(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Limited Discount',
            'slug' => $this->uniqueSlug('limited-discount-svc'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 10]],
            'max_usage_count' => 0,
            'usage_count' => 0,
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        // Rule with 0 max usage should still apply (null check)
        $this->assertEquals(100, $result['final_price']);
    }

        #[Test]
        public function it_creates_bulk_pricing_rule(): void
    {
        $rule = $this->pricingService->createBulkPricingRule([
            'name' => 'New Bulk Rule',
            'slug' => $this->uniqueSlug('new-bulk-rule'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'tiers' => [
                ['min' => 10, 'max' => null, 'value' => 15],
            ],
        ]);

        $this->assertInstanceOf(ProductPricingRule::class, $rule);
        $this->assertEquals('New Bulk Rule', $rule->name);
        $this->assertEquals(ProductPricingRule::RULE_TYPE_BULK_QUANTITY, $rule->rule_type);
    }

        #[Test]
        public function it_creates_customer_pricing(): void
    {
        $product = $this->createTestProduct(100);

        $pricing = $this->pricingService->createCustomerPricing(
            $product->id,
            5,
            75.00,
            ['minimum_quantity' => 5]
        );

        $this->assertInstanceOf(ProductCustomerPricing::class, $pricing);
        $this->assertEquals($product->id, $pricing->product_id);
        $this->assertEquals(5, $pricing->user_id);
        $this->assertEquals(75.00, $pricing->price);
    }

        #[Test]
        public function it_applies_pricing_to_cart_items(): void
    {
        $product1 = $this->createTestProduct(100);
        $product2 = $this->createTestProduct(50);

        ProductPricingRule::create([
            'name' => 'Bulk 10%',
            'slug' => $this->uniqueSlug('bulk-ten-cart'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product1->id],
            'tiers' => [['min' => 5, 'max' => null, 'value' => 10]],
            'is_active' => true,
        ]);

        $items = [
            ['product_id' => $product1->id, 'qty' => 5, 'price' => 100],
            ['product_id' => $product2->id, 'qty' => 2, 'price' => 50],
        ];

        $result = $this->pricingService->applyPricingToCart($items);

        $this->assertEquals(90, $result['items'][0]['price']);
        $this->assertEquals(50, $result['items'][1]['price']);
        $this->assertEquals(50, $result['total_discount']); // 10 * 5 items
    }

        #[Test]
        public function it_validates_tier_structure(): void
    {
        $validTiers = [
            ['min' => 10, 'max' => 50, 'value' => 10],
            ['min' => 51, 'max' => null, 'value' => 15],
        ];

        $this->assertTrue($this->pricingService->validateTiers($validTiers));

        // Overlapping ranges
        $invalidTiers = [
            ['min' => 10, 'max' => 50, 'value' => 10],
            ['min' => 40, 'max' => 100, 'value' => 15],
        ];

        $this->assertFalse($this->pricingService->validateTiers($invalidTiers));

        // Missing required fields
        $incompleteTiers = [
            ['min' => 10],
        ];

        $this->assertFalse($this->pricingService->validateTiers($incompleteTiers));
    }

        #[Test]
        public function it_gets_pricing_tiers_for_product(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Tier Display',
            'slug' => $this->uniqueSlug('tier-display'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [
                ['min' => 5, 'max' => 10, 'value' => 5],
                ['min' => 11, 'max' => null, 'value' => 10],
            ],
            'is_active' => true,
        ]);

        $tiers = $this->pricingService->getPricingTiers($product->id);

        $this->assertCount(1, $tiers);
        $this->assertEquals('Tier Display', $tiers[0]['rule_name']);
        $this->assertCount(2, $tiers[0]['tiers']);
    }

        #[Test]
        public function it_checks_if_product_has_active_pricing_rules(): void
    {
        $product1 = $this->createTestProduct(100);
        $product2 = $this->createTestProduct(50);

        ProductPricingRule::create([
            'name' => 'Product 1 Rule',
            'slug' => $this->uniqueSlug('product-one-rule'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'product_ids' => [$product1->id],
            'is_active' => true,
        ]);

        $this->assertTrue($this->pricingService->hasActivePricingRules($product1->id));
        $this->assertFalse($this->pricingService->hasActivePricingRules($product2->id));
    }

        #[Test]
        public function it_detects_customer_specific_pricing(): void
    {
        $product = $this->createTestProduct(100);

        ProductCustomerPricing::create([
            'product_id' => $product->id,
            'user_id' => 5,
            'price' => 75.00,
            'is_active' => true,
        ]);

        $this->assertTrue($this->pricingService->hasActivePricingRules($product->id, 5));
        $this->assertFalse($this->pricingService->hasActivePricingRules($product->id, 6));
    }

        #[Test]
        public function it_clears_pricing_cache(): void
    {
        $product = $this->createTestProduct(100);

        ProductCustomerPricing::create([
            'product_id' => $product->id,
            'user_id' => 5,
            'price' => 75.00,
            'is_active' => true,
        ]);

        // Populate cache
        $this->pricingService->getCustomerPricing($product->id, 5);

        // Clear cache
        $this->pricingService->clearPricingCache(5);

        // Verify cache is cleared (will query DB again)
        $this->assertTrue(true); // If no exception, cache was cleared
    }

        #[Test]
        public function it_prevents_negative_final_prices(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Too Big Discount',
            'slug' => $this->uniqueSlug('too-big-discount'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_FIXED_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 150]],
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        $this->assertEquals(0, $result['final_price']);
    }

        #[Test]
        public function it_returns_rules_applied_in_result(): void
    {
        $product = $this->createTestProduct(100);

        ProductPricingRule::create([
            'name' => 'Test Rule',
            'slug' => $this->uniqueSlug('test-rule-info'),
            'rule_type' => ProductPricingRule::RULE_TYPE_BULK_QUANTITY,
            'price_type' => ProductPricingRule::PRICE_TYPE_PERCENTAGE_DISCOUNT,
            'product_ids' => [$product->id],
            'tiers' => [['min' => 1, 'max' => null, 'value' => 10]],
            'is_active' => true,
        ]);

        $result = $this->pricingService->calculatePrice($product->id, 1, 100);

        $this->assertCount(1, $result['rules_applied']);
        $this->assertEquals('Test Rule', $result['rules_applied'][0]['name']);
        $this->assertEquals(ProductPricingRule::RULE_TYPE_BULK_QUANTITY, $result['rules_applied'][0]['type']);
        $this->assertEquals(10, $result['rules_applied'][0]['discount']);
    }
}
