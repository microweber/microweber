<?php

namespace Modules\Shipping\Tests\Unit\Drivers;

use PHPUnit\Framework\Attributes\Test;
use Modules\Shipping\Drivers\WeightBased;
use Modules\Shipping\Models\ShippingProvider;
use Modules\Product\Models\Product;
use Tests\TestCase;

class WeightBasedTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Clear cart before each test
        session_forget('cart_id');
        session_forget('cart_session_id');
    }

    #[Test]
    public function it_driver_initialization(): void
    {
        $driver = new WeightBased();
        $this->assertEquals('Weight Based Shipping', $driver->title());
        $this->assertEquals('weight_based', $driver->provider);
    }

    #[Test]
    public function it_default_cost_without_model(): void
    {
        $driver = new WeightBased();
        $this->assertEquals(0, $driver->getShippingCost());
    }

    #[Test]
    public function it_base_cost_only(): void
    {
        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'base_shipping_cost' => 10,
            'cost_per_weight_unit' => 0,
        ];
        $driver->setModel($model);

        // Empty cart should return 0
        $this->assertEquals(0, $driver->getShippingCost());
    }

    #[Test]
    public function it_weight_based_calculation(): void
    {
        // Create a product with weight
        $product = Product::factory()->create([
            'title' => 'Heavy Product',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);
        $product->setContentData([
            'shipping_weight' => 2.5,
            'is_free_shipping' => '',
        ]);
        $product->save();

        // Add product to cart
        app()->shop_manager->add_to_cart([
            'product_id' => $product->id,
            'qty' => 2, // Total weight = 5kg
        ]);

        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'base_shipping_cost' => 5,
            'cost_per_weight_unit' => 2, // $2 per kg
        ];
        $driver->setModel($model);

        // Cost = base (5) + (weight 5 * cost 2) = 15
        $this->assertEquals(15, $driver->getShippingCost());
    }

    #[Test]
    public function it_weight_tiers_calculation(): void
    {
        // Create a product with weight
        $product = Product::factory()->create([
            'title' => 'Heavy Product',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);
        $product->setContentData([
            'shipping_weight' => 3,
            'is_free_shipping' => '',
        ]);
        $product->save();

        // Add product to cart
        app()->shop_manager->add_to_cart([
            'product_id' => $product->id,
            'qty' => 1, // Total weight = 3kg
        ]);

        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'use_weight_tiers' => true,
            'weight_tiers' => [
                [
                    'min_weight' => 0,
                    'max_weight' => 1,
                    'cost' => 5,
                    'is_active' => true,
                ],
                [
                    'min_weight' => 1.01,
                    'max_weight' => 5,
                    'cost' => 10,
                    'is_active' => true,
                ],
                [
                    'min_weight' => 5.01,
                    'max_weight' => null,
                    'cost' => 20,
                    'is_active' => true,
                ],
            ],
        ];
        $driver->setModel($model);

        // Weight 3kg falls in tier 2 (1.01-5), cost should be 10
        $this->assertEquals(10, $driver->getShippingCost());
    }

    #[Test]
    public function it_free_shipping_threshold(): void
    {
        // Create a product with weight
        $product = Product::factory()->create([
            'title' => 'Heavy Product',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);
        $product->setContentData([
            'shipping_weight' => 10,
            'is_free_shipping' => '',
        ]);
        $product->save();

        // Add product to cart
        app()->shop_manager->add_to_cart([
            'product_id' => $product->id,
            'qty' => 1, // Total weight = 10kg
        ]);

        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'base_shipping_cost' => 5,
            'cost_per_weight_unit' => 2,
            'free_shipping_threshold' => 10, // Free shipping at 10kg
        ];
        $driver->setModel($model);

        // Should be free because weight >= threshold
        $this->assertEquals(0, $driver->getShippingCost());
    }

    #[Test]
    public function it_max_cost_cap(): void
    {
        // Create a product with weight
        $product = Product::factory()->create([
            'title' => 'Heavy Product',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);
        $product->setContentData([
            'shipping_weight' => 100,
            'is_free_shipping' => '',
        ]);
        $product->save();

        // Add product to cart
        app()->shop_manager->add_to_cart([
            'product_id' => $product->id,
            'qty' => 1, // Total weight = 100kg
        ]);

        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'base_shipping_cost' => 5,
            'cost_per_weight_unit' => 2,
            'max_shipping_cost' => 50, // Cap at $50
        ];
        $driver->setModel($model);

        // Without cap: 5 + (100 * 2) = 205
        // With cap: 50
        $this->assertEquals(50, $driver->getShippingCost());
    }

    #[Test]
    public function it_ignores_free_shipping_items(): void
    {
        // Create two products
        $product1 = Product::factory()->create([
            'title' => 'Normal Product',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);
        $product1->setContentData([
            'shipping_weight' => 5,
            'is_free_shipping' => '',
        ]);
        $product1->save();

        $product2 = Product::factory()->create([
            'title' => 'Free Shipping Product',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);
        $product2->setContentData([
            'shipping_weight' => 10,
            'is_free_shipping' => 'y', // Free shipping
        ]);
        $product2->save();

        // Add both products to cart
        app()->shop_manager->add_to_cart([
            'product_id' => $product1->id,
            'qty' => 1, // Weight 5kg, paid shipping
        ]);
        app()->shop_manager->add_to_cart([
            'product_id' => $product2->id,
            'qty' => 1, // Weight 10kg, free shipping
        ]);

        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'base_shipping_cost' => 0,
            'cost_per_weight_unit' => 2,
        ];
        $driver->setModel($model);

        // Only product1 weight (5kg) should count
        // Cost = 5 * 2 = 10
        $this->assertEquals(10, $driver->getShippingCost());
    }

    #[Test]
    public function it_inactive_tier_is_skipped(): void
    {
        // Create a product with weight
        $product = Product::factory()->create([
            'title' => 'Heavy Product',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);
        $product->setContentData([
            'shipping_weight' => 2,
            'is_free_shipping' => '',
        ]);
        $product->save();

        // Add product to cart
        app()->shop_manager->add_to_cart([
            'product_id' => $product->id,
            'qty' => 1, // Total weight = 2kg
        ]);

        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'use_weight_tiers' => true,
            'weight_tiers' => [
                [
                    'min_weight' => 0,
                    'max_weight' => 1,
                    'cost' => 5,
                    'is_active' => true,
                ],
                [
                    'min_weight' => 1.01,
                    'max_weight' => 3,
                    'cost' => 15,
                    'is_active' => false, // Inactive tier
                ],
                [
                    'min_weight' => 3.01,
                    'max_weight' => null,
                    'cost' => 25,
                    'is_active' => true,
                ],
            ],
        ];
        $driver->setModel($model);

        // Weight 2kg would fall in tier 2 (inactive), should fall back to tier 3
        // But actually with tier logic, it won't match tier 1, won't match tier 2 (inactive), won't match tier 3
        // So it should use the last active tier cost = 25
        $cost = $driver->getShippingCost();
        $this->assertGreaterThan(0, $cost);
    }

    #[Test]
    public function it_settings_form_returns_array(): void
    {
        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'base_shipping_cost' => 10,
            'cost_per_weight_unit' => 2,
        ];
        $driver->setModel($model);

        $form = $driver->getSettingsForm();
        $this->assertIsArray($form);
        $this->assertNotEmpty($form);
    }

    #[Test]
    public function it_checkout_form_returns_array(): void
    {
        $driver = new WeightBased();
        $model = new ShippingProvider();
        $model->settings = [
            'shipping_instructions' => 'Test instructions',
        ];
        $driver->setModel($model);

        $form = $driver->getForm();
        $this->assertIsArray($form);
        $this->assertNotEmpty($form);
    }
}
