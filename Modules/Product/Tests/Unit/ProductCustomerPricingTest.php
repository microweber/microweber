<?php

namespace Modules\Product\Tests\Unit;

use Modules\Product\Models\ProductCustomerPricing;
use Tests\TestCase;

class ProductCustomerPricingTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        \DB::table('product_customer_pricing')->delete();
    }

    protected function uniqueIds(): array
    {
        return [
            'product_id' => rand(1000, 9999),
            'user_id' => rand(1000, 9999),
        ];
    }

    /** @test */
    public function it_can_create_customer_pricing(): void
    {
        $ids = $this->uniqueIds();
        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 79.99,
            'compare_price' => 99.99,
            'minimum_quantity' => 1,
            'is_active' => true,
        ]);

        $this->assertInstanceOf(ProductCustomerPricing::class, $pricing);
        $this->assertDatabaseHas('product_customer_pricing', [
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 79.99,
        ]);
    }

    /** @test */
    public function it_has_default_minimum_quantity_of_one(): void
    {
        $ids = $this->uniqueIds();
        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 50.00,
        ]);

        $this->assertEquals(1, $pricing->minimum_quantity);
    }

    /** @test */
    public function it_casts_price_as_decimal(): void
    {
        $ids = $this->uniqueIds();
        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 99.999,
        ]);

        // Price is cast to decimal:2 which may return as string from DB
        $this->assertIsNumeric($pricing->price);
        $this->assertEquals(100.00, (float) $pricing->price);
    }

    /** @test */
    public function it_can_set_validity_dates(): void
    {
        $ids = $this->uniqueIds();
        $validFrom = now()->addDay()->startOfMinute();
        $validTo = now()->addMonth()->startOfMinute();

        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 50.00,
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
        ]);

        $this->assertNotNull($pricing->valid_from);
        $this->assertNotNull($pricing->valid_to);
        // Compare dates using format to avoid microsecond differences
        $this->assertEquals($validFrom->format('Y-m-d H:i'), $pricing->valid_from->format('Y-m-d H:i'));
        $this->assertEquals($validTo->format('Y-m-d H:i'), $pricing->valid_to->format('Y-m-d H:i'));
    }

    /** @test */
    public function it_can_check_current_validity(): void
    {
        $ids1 = $this->uniqueIds();
        $active = ProductCustomerPricing::create([
            'product_id' => $ids1['product_id'],
            'user_id' => $ids1['user_id'],
            'price' => 50.00,
            'is_active' => true,
        ]);

        $ids2 = $this->uniqueIds();
        $inactive = ProductCustomerPricing::create([
            'product_id' => $ids2['product_id'],
            'user_id' => $ids2['user_id'],
            'price' => 50.00,
            'is_active' => false,
        ]);

        $ids3 = $this->uniqueIds();
        $expired = ProductCustomerPricing::create([
            'product_id' => $ids3['product_id'],
            'user_id' => $ids3['user_id'],
            'price' => 50.00,
            'is_active' => true,
            'valid_to' => now()->subDay(),
        ]);

        $this->assertTrue($active->isCurrentlyValid());
        $this->assertFalse($inactive->isCurrentlyValid());
        $this->assertFalse($expired->isCurrentlyValid());
    }

    /** @test */
    public function it_can_check_if_applies_to_quantity(): void
    {
        $ids = $this->uniqueIds();
        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 50.00,
            'minimum_quantity' => 5,
        ]);

        $this->assertFalse($pricing->appliesToQuantity(1));
        $this->assertFalse($pricing->appliesToQuantity(4));
        $this->assertTrue($pricing->appliesToQuantity(5));
        $this->assertTrue($pricing->appliesToQuantity(10));
    }

    /** @test */
    public function it_scopes_active_customer_pricing(): void
    {
        $ids1 = $this->uniqueIds();
        ProductCustomerPricing::create([
            'product_id' => $ids1['product_id'],
            'user_id' => $ids1['user_id'],
            'price' => 50.00,
            'is_active' => true,
        ]);

        $ids2 = $this->uniqueIds();
        ProductCustomerPricing::create([
            'product_id' => $ids2['product_id'],
            'user_id' => $ids2['user_id'],
            'price' => 60.00,
            'is_active' => false,
        ]);

        $active = ProductCustomerPricing::active()->get();

        $this->assertEquals(1, $active->count());
        $this->assertEquals($ids1['product_id'], $active->first()->product_id);
    }

    /** @test */
    public function it_scopes_for_specific_customer(): void
    {
        $userId = rand(1000, 9999);

        ProductCustomerPricing::create([
            'product_id' => rand(1000, 9999),
            'user_id' => $userId,
            'price' => 50.00,
        ]);

        ProductCustomerPricing::create([
            'product_id' => rand(1000, 9999),
            'user_id' => rand(10000, 19999),
            'price' => 60.00,
        ]);

        ProductCustomerPricing::create([
            'product_id' => rand(1000, 9999),
            'user_id' => $userId,
            'price' => 70.00,
        ]);

        $customerPricing = ProductCustomerPricing::forCustomer($userId)->get();

        $this->assertEquals(2, $customerPricing->count());
    }

    /** @test */
    public function it_scopes_for_specific_product(): void
    {
        $productId = rand(1000, 9999);

        ProductCustomerPricing::create([
            'product_id' => $productId,
            'user_id' => rand(1000, 9999),
            'price' => 50.00,
        ]);

        ProductCustomerPricing::create([
            'product_id' => $productId,
            'user_id' => rand(1000, 9999),
            'price' => 50.00,
        ]);

        ProductCustomerPricing::create([
            'product_id' => rand(10000, 19999),
            'user_id' => rand(1000, 9999),
            'price' => 60.00,
        ]);

        $productPricing = ProductCustomerPricing::forProduct($productId)->get();

        $this->assertEquals(2, $productPricing->count());
    }

    /** @test */
    public function it_scopes_for_minimum_quantity(): void
    {
        ProductCustomerPricing::create([
            'product_id' => rand(1000, 9999),
            'user_id' => rand(1000, 9999),
            'price' => 50.00,
            'minimum_quantity' => 1,
        ]);

        ProductCustomerPricing::create([
            'product_id' => rand(1000, 9999),
            'user_id' => rand(1000, 9999),
            'price' => 45.00,
            'minimum_quantity' => 5,
        ]);

        ProductCustomerPricing::create([
            'product_id' => rand(1000, 9999),
            'user_id' => rand(1000, 9999),
            'price' => 40.00,
            'minimum_quantity' => 10,
        ]);

        $validForQuantity = ProductCustomerPricing::forQuantity(5)->get();

        $this->assertEquals(2, $validForQuantity->count());
    }

    /** @test */
    public function it_calculates_discount_percentage(): void
    {
        $ids = $this->uniqueIds();
        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 80.00,
            'compare_price' => 100.00,
        ]);

        $this->assertEquals(20.00, $pricing->discount_percentage);
    }

    /** @test */
    public function it_returns_null_for_discount_percentage_when_no_compare_price(): void
    {
        $ids = $this->uniqueIds();
        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 80.00,
            'compare_price' => null,
        ]);

        $this->assertNull($pricing->discount_percentage);
    }

    /** @test */
    public function it_returns_null_for_discount_percentage_when_compare_price_is_zero(): void
    {
        $ids = $this->uniqueIds();
        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 80.00,
            'compare_price' => 0,
        ]);

        $this->assertNull($pricing->discount_percentage);
    }

    /** @test */
    public function it_can_store_metadata(): void
    {
        $ids = $this->uniqueIds();
        $pricing = ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 50.00,
            'metadata' => [
                'contract_id' => 'C-12345',
                'negotiated_by' => 'Sales Team',
                'notes' => 'Annual contract renewal',
            ],
        ]);

        $this->assertIsArray($pricing->metadata);
        $this->assertEquals('C-12345', $pricing->metadata['contract_id']);
    }

    /** @test */
    public function it_enforces_unique_product_customer_combination(): void
    {
        $ids = $this->uniqueIds();
        ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 50.00,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        ProductCustomerPricing::create([
            'product_id' => $ids['product_id'],
            'user_id' => $ids['user_id'],
            'price' => 60.00,
        ]);
    }
}
