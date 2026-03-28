<?php

namespace Modules\Product\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductVariantAttribute;
use Modules\Product\Models\ProductVariantAttributeValue;
use Modules\Product\Models\ProductVariantCombination;
use Modules\Product\Services\ProductVariantService;
use Tests\TestCase;

class ProductVariantSystemTest extends TestCase
{
    use DatabaseTransactions;

    protected ProductVariantService $variantService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->variantService = new ProductVariantService();
    }

    /**
     * Test creating a variant attribute
     */
    public function test_can_create_variant_attribute(): void
    {
        $data = [
            'name' => 'Test Size',
            'key' => 'test-size-' . uniqid(),
            'description' => 'Product size options',
            'type' => 'select',
            'position' => 1,
            'is_active' => true,
        ];

        $attribute = $this->variantService->createAttribute($data);

        $this->assertInstanceOf(ProductVariantAttribute::class, $attribute);
        $this->assertEquals('Test Size', $attribute->name);
        $this->assertEquals($data['key'], $attribute->key);
        $this->assertEquals('select', $attribute->type);

        $this->assertDatabaseHas('product_variant_attributes', [
            'name' => 'Test Size',
            'key' => $data['key'],
        ]);
    }

    /**
     * Test creating attribute with values
     */
    public function test_can_create_attribute_with_values(): void
    {
        $uniqueId = uniqid();
        $data = [
            'name' => 'Test Color ' . $uniqueId,
            'key' => 'test-color-' . $uniqueId,
            'type' => 'color',
            'values' => [
                ['value' => 'Test Red', 'key' => 'test-red-' . $uniqueId, 'color_code' => '#FF0000'],
                ['value' => 'Test Blue', 'key' => 'test-blue-' . $uniqueId, 'color_code' => '#0000FF'],
                ['value' => 'Test Green', 'key' => 'test-green-' . $uniqueId, 'color_code' => '#00FF00'],
            ],
        ];

        $attribute = $this->variantService->createAttribute($data);

        $this->assertInstanceOf(ProductVariantAttribute::class, $attribute);
        $this->assertEquals(3, $attribute->values()->count());

        $this->assertDatabaseHas('product_variant_attribute_values', [
            'value' => 'Test Red',
            'color_code' => '#FF0000',
        ]);
    }

    /**
     * Test generating variant combinations
     */
    public function test_can_generate_variant_combinations(): void
    {
        $uniqueId = uniqid();
        // Create attributes
        $size = $this->variantService->createAttribute([
            'name' => 'Test Size ' . $uniqueId,
            'key' => 'test-size-' . $uniqueId,
            'type' => 'select',
            'values' => [
                ['value' => 'Small', 'key' => 'small'],
                ['value' => 'Medium', 'key' => 'medium'],
                ['value' => 'Large', 'key' => 'large'],
            ],
        ]);

        $color = $this->variantService->createAttribute([
            'name' => 'Test Color ' . $uniqueId,
            'key' => 'test-color-' . $uniqueId,
            'type' => 'color',
            'values' => [
                ['value' => 'Red', 'key' => 'red'],
                ['value' => 'Blue', 'key' => 'blue'],
            ],
        ]);

        // Create a product
        $product = Product::create([
            'title' => 'Test Variant Product',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        // Generate combinations
        $combinations = $this->variantService->generateVariantCombinations(
            $product,
            [$size->id, $color->id]
        );

        // Should create 6 combinations (3 sizes x 2 colors)
        $this->assertCount(6, $combinations);

        // Verify database
        $this->assertDatabaseHas('product_variants_combinations', [
            'product_id' => $product->id,
        ]);

        // Check pivot table
        $combinationCount = DB::table('product_variant_combination_attributes')->count();
        $this->assertEquals(12, $combinationCount); // 6 combinations x 2 values each
    }

    /**
     * Test finding variant by attributes
     */
    public function test_can_find_variant_by_attributes(): void
    {
        $uniqueId = uniqid();
        // Setup
        $size = $this->variantService->createAttribute([
            'name' => 'Test Size ' . $uniqueId,
            'key' => 'test-size-' . $uniqueId,
            'type' => 'select',
            'values' => [
                ['value' => 'Small', 'key' => 'small'],
                ['value' => 'Large', 'key' => 'large'],
            ],
        ]);

        $color = $this->variantService->createAttribute([
            'name' => 'Test Color ' . $uniqueId,
            'key' => 'test-color-' . $uniqueId,
            'type' => 'color',
            'values' => [
                ['value' => 'Red', 'key' => 'red'],
                ['value' => 'Blue', 'key' => 'blue'],
            ],
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        $this->variantService->generateVariantCombinations($product, [$size->id, $color->id]);

        // Test finding
        $combination = $this->variantService->findCombinationByAttributeKeys(
            $product->id,
            ['size' => 'small', 'color' => 'red']
        );

        $this->assertInstanceOf(ProductVariantCombination::class, $combination);
    }

    /**
     * Test updating variant combination
     */
    public function test_can_update_variant_combination(): void
    {
        $uniqueId = uniqid();
        // Setup
        $attribute = $this->variantService->createAttribute([
            'name' => 'Test Size ' . $uniqueId,
            'key' => 'test-size-' . $uniqueId,
            'type' => 'select',
            'values' => [['value' => 'Small', 'key' => 'small']],
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        $combinations = $this->variantService->generateVariantCombinations($product, [$attribute->id]);
        $combination = $combinations->first();

        // Update
        $updated = $this->variantService->updateCombination($combination->id, [
            'sku' => 'TEST-001',
            'price' => 29.99,
            'quantity' => 100,
            'track_quantity' => true,
        ]);

        $this->assertEquals('TEST-001', $updated->sku);
        $this->assertEquals(29.99, $updated->price);
        $this->assertEquals(100, $updated->quantity);

        $this->assertDatabaseHas('product_variants_combinations', [
            'id' => $combination->id,
            'sku' => 'TEST-001',
            'price' => 29.99,
        ]);
    }

    /**
     * Test stock tracking
     */
    public function test_can_track_variant_stock(): void
    {
        // Setup
        $attribute = $this->variantService->createAttribute([
            'name' => 'Size',
            'key' => 'size',
            'type' => 'select',
            'values' => [['value' => 'Medium', 'key' => 'medium']],
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        $combinations = $this->variantService->generateVariantCombinations($product, [$attribute->id]);
        $combination = $combinations->first();

        // Initial quantity
        $this->variantService->updateInventory($combination->id, 50, true);

        // Check stock status
        $combination->refresh();
        $this->assertTrue($combination->isInStock());
        $this->assertEquals(50, $combination->getAvailableQuantity());

        // Update inventory
        $this->variantService->updateInventory($combination->id, 25);
        $combination->refresh();
        $this->assertEquals(25, $combination->quantity);
    }

    /**
     * Test setting default variant
     */
    public function test_can_set_default_variant(): void
    {
        // Setup
        $attribute = $this->variantService->createAttribute([
            'name' => 'Size',
            'key' => 'size',
            'type' => 'select',
            'values' => [
                ['value' => 'Small', 'key' => 'small'],
                ['value' => 'Large', 'key' => 'large'],
            ],
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        $combinations = $this->variantService->generateVariantCombinations($product, [$attribute->id]);

        // Set first as default
        $this->variantService->setDefaultVariant($product->id, $combinations->first()->id);

        $default = ProductVariantCombination::where('product_id', $product->id)
            ->where('is_default', true)
            ->first();

        $this->assertNotNull($default);
        $this->assertEquals($combinations->first()->id, $default->id);

        // Set second as default - should unset first
        $this->variantService->setDefaultVariant($product->id, $combinations->last()->id);

        $default = ProductVariantCombination::where('product_id', $product->id)
            ->where('is_default', true)
            ->first();

        $this->assertEquals($combinations->last()->id, $default->id);
        $this->assertEquals(1, ProductVariantCombination::where('is_default', true)->count());
    }

    /**
     * Test getting variant options for frontend
     */
    public function test_can_get_variant_options_for_frontend(): void
    {
        // Setup
        $size = $this->variantService->createAttribute([
            'name' => 'Size',
            'key' => 'size',
            'type' => 'select',
            'values' => [
                ['value' => 'Small', 'key' => 'small'],
                ['value' => 'Large', 'key' => 'large'],
            ],
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        $this->variantService->generateVariantCombinations($product, [$size->id]);

        $options = $this->variantService->getVariantOptions($product->id);

        $this->assertArrayHasKey('attributes', $options);
        $this->assertArrayHasKey('variants', $options);
        $this->assertCount(1, $options['attributes']);
        $this->assertCount(2, $options['variants']);

        // Check attribute structure
        $attribute = $options['attributes'][0];
        $this->assertArrayHasKey('name', $attribute);
        $this->assertArrayHasKey('key', $attribute);
        $this->assertArrayHasKey('values', $attribute);
    }

    /**
     * Test importing variants
     */
    public function test_can_import_variants_from_array(): void
    {
        // Pre-create attribute
        $this->variantService->createAttribute([
            'name' => 'Size',
            'key' => 'size',
            'type' => 'select',
            'values' => [
                ['value' => 'Small', 'key' => 'small'],
                ['value' => 'Medium', 'key' => 'medium'],
            ],
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        $variantsData = [
            [
                'attributes' => ['size' => 'small'],
                'sku' => 'SKU-SMALL',
                'price' => 19.99,
                'quantity' => 50,
            ],
            [
                'attributes' => ['size' => 'medium'],
                'sku' => 'SKU-MEDIUM',
                'price' => 24.99,
                'quantity' => 75,
            ],
        ];

        $imported = $this->variantService->importVariants($product->id, $variantsData);

        $this->assertCount(2, $imported);

        $this->assertDatabaseHas('product_variants_combinations', [
            'product_id' => $product->id,
            'sku' => 'SKU-SMALL',
            'price' => 19.99,
        ]);
    }

    /**
     * Test auto-generating key from name
     */
    public function test_auto_generates_key_from_name(): void
    {
        $attribute = ProductVariantAttribute::create([
            'name' => 'Material Type',
            'type' => 'select',
        ]);

        $this->assertEquals('material-type', $attribute->key);
    }

    /**
     * Test variant attribute scopes
     */
    public function test_variant_attribute_scopes(): void
    {
        ProductVariantAttribute::create(['name' => 'Test Active', 'key' => 'test-active-' . uniqid(), 'is_active' => true]);
        ProductVariantAttribute::create(['name' => 'Test Inactive', 'key' => 'test-inactive-' . uniqid(), 'is_active' => false]);

        $active = ProductVariantAttribute::active()->get();
        $this->assertCount(1, $active);
        $this->assertEquals('Test Active', $active->first()->name);
    }

    /**
     * Test duplicate combination prevention
     */
    public function test_prevents_duplicate_combinations(): void
    {
        $size = $this->variantService->createAttribute([
            'name' => 'Size',
            'key' => 'size',
            'type' => 'select',
            'values' => [['value' => 'Medium', 'key' => 'medium']],
        ]);

        $product = Product::create([
            'title' => 'Test Product',
            'content_type' => 'product',
            'is_active' => true,
        ]);

        // Generate first time
        $this->variantService->generateVariantCombinations($product, [$size->id]);
        $count1 = ProductVariantCombination::where('product_id', $product->id)->count();

        // Generate again - should not create duplicates
        $this->variantService->generateVariantCombinations($product, [$size->id]);
        $count2 = ProductVariantCombination::where('product_id', $product->id)->count();

        $this->assertEquals($count1, $count2);
    }
}
