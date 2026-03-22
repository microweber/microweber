<?php

namespace Modules\Cart\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Cart\Services\CartTotalsService;

class CartTotalsServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        empty_cart();
        app()->database_manager->extended_save_set_permission(true);
    }

    protected function tearDown(): void
    {
        empty_cart();
        session()->forget(['checkout_address', 'shipping_address']);
        parent::tearDown();
    }

    private function createProduct(float $price): int
    {
        $params = [
            'title' => 'Test Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => [
                ['type' => 'price', 'name' => 'Price', 'value' => (string)$price],
            ],
            'is_active' => 1,
        ];
        return save_content($params);
    }

    private function addToCart(int $contentId, int $qty = 1): void
    {
        $result = update_cart([
            'content_id' => $contentId,
            'qty' => $qty,
        ]);
        
        if (!isset($result['success'])) {
            throw new \Exception('Failed to add item to cart: ' . json_encode($result));
        }
    }

    #[Test]
    public function it_calculates_cart_sum(): void
    {
        $service = new CartTotalsService(app());
        
        // Add items to cart
        $productId = $this->createProduct(25.00);
        $this->addToCart($productId, 2);
        
        $sum = $service->sum();
        
        $this->assertEquals(50.00, $sum);
    }

    #[Test]
    public function it_counts_cart_items(): void
    {
        $service = new CartTotalsService(app());
        
        // Add items to cart
        $productId1 = $this->createProduct(10.00);
        $productId2 = $this->createProduct(20.00);
        $this->addToCart($productId1, 2);
        $this->addToCart($productId2, 3);
        
        $count = $service->sum(false);
        
        $this->assertEquals(5, $count);
    }

    #[Test]
    public function it_calculates_totals_with_empty_cart(): void
    {
        $service = new CartTotalsService(app());
        
        $totals = $service->totals();
        
        $this->assertArrayHasKey('subtotal', $totals);
        $this->assertArrayHasKey('total', $totals);
        $this->assertEquals(0.00, $totals['subtotal']['value']);
        $this->assertEquals(0.00, $totals['total']['value']);
    }

    #[Test]
    public function it_returns_specific_total_component(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(50.00);
        $this->addToCart($productId, 1);
        
        $subtotal = $service->totals('subtotal');
        
        $this->assertEquals(50.00, $subtotal['value']);
    }

    #[Test]
    public function it_returns_total_value(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $total = $service->total();
        
        // Should return the total including any shipping (if applicable)
        $this->assertIsFloat($total);
        $this->assertGreaterThanOrEqual(100.00, $total);
    }

    #[Test]
    public function it_handles_multiple_products(): void
    {
        $service = new CartTotalsService(app());
        
        $productId1 = $this->createProduct(30.00);
        $productId2 = $this->createProduct(45.00);
        $productId3 = $this->createProduct(25.00);
        
        $this->addToCart($productId1, 1);
        $this->addToCart($productId2, 2);
        $this->addToCart($productId3, 1);
        
        $sum = $service->sum();
        
        // 30 + (45*2) + 25 = 30 + 90 + 25 = 145
        $this->assertEquals(145.00, $sum);
    }

    #[Test]
    public function it_calculates_subtotal_separately_from_total(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(75.00);
        $this->addToCart($productId, 2);
        
        $totals = $service->totals();
        
        $this->assertArrayHasKey('subtotal', $totals);
        $this->assertEquals(150.00, $totals['subtotal']['value']);
    }

    #[Test]
    public function it_includes_formatted_amounts_in_totals(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(99.99);
        $this->addToCart($productId, 1);
        
        $totals = $service->totals();
        
        $this->assertArrayHasKey('amount', $totals['subtotal']);
        $this->assertNotEmpty($totals['subtotal']['amount']);
        $this->assertStringContainsString('99.99', $totals['subtotal']['amount']);
    }

    #[Test]
    public function it_handles_quantity_updates(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(20.00);
        $this->addToCart($productId, 1);
        
        $sum1 = $service->sum();
        $this->assertEquals(20.00, $sum1);
        
        // Update quantity
        $this->addToCart($productId, 5);
        
        $sum2 = $service->sum();
        // Now should have 6 total (1 + 5)
        $this->assertEquals(120.00, $sum2);
    }

    #[Test]
    public function it_returns_labels_for_total_components(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(50.00);
        $this->addToCart($productId, 1);
        
        $totals = $service->totals();
        
        $this->assertArrayHasKey('label', $totals['subtotal']);
        $this->assertArrayHasKey('label', $totals['total']);
        $this->assertNotEmpty($totals['subtotal']['label']);
        $this->assertNotEmpty($totals['total']['label']);
    }

    #[Test]
    public function it_handles_location_data_from_checkout_session(): void
    {
        $service = new CartTotalsService(app());
        
        // Set checkout address in session
        session([
            'checkout_address' => [
                'country' => 'US',
                'state' => 'CA',
                'city' => 'Los Angeles',
                'zip' => '90001'
            ]
        ]);
        
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        // Should calculate without error
        $totals = $service->totals();
        
        $this->assertArrayHasKey('subtotal', $totals);
        $this->assertEquals(100.00, $totals['subtotal']['value']);
    }

    #[Test]
    public function it_handles_location_data_from_shipping_session(): void
    {
        $service = new CartTotalsService(app());
        
        // Set shipping address in session
        session([
            'shipping_address' => [
                'country' => 'UK',
                'state' => 'London',
                'city' => 'London',
                'zip' => 'SW1A 1AA'
            ]
        ]);
        
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        // Should calculate without error
        $totals = $service->totals();
        
        $this->assertArrayHasKey('subtotal', $totals);
        $this->assertEquals(100.00, $totals['subtotal']['value']);
    }

    #[Test]
    public function it_gets_tax_amount_for_given_amount(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        // Get tax returns a float (may be 0 if no tax rules configured)
        $tax = $service->getTax();
        
        $this->assertIsFloat($tax);
        $this->assertGreaterThanOrEqual(0, $tax);
    }

    #[Test]
    public function it_returns_discount_text_when_no_coupon(): void
    {
        $service = new CartTotalsService(app());
        
        $text = $service->getDiscountText();
        
        $this->assertIsString($text);
        // Should return empty string when no coupon
        $this->assertEquals('', $text);
    }

    #[Test]
    public function it_returns_false_for_discount_value_when_no_coupon(): void
    {
        $service = new CartTotalsService(app());
        
        $discount = $service->getDiscountValue();
        
        $this->assertFalse($discount);
    }

    #[Test]
    public function it_returns_false_for_discount_type_when_no_coupon(): void
    {
        $service = new CartTotalsService(app());
        
        $type = $service->getDiscountType();
        
        $this->assertFalse($type);
    }

    #[Test]
    public function it_returns_tax_breakdown(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $breakdown = $service->getTaxBreakdown();
        
        $this->assertIsArray($breakdown);
        $this->assertArrayHasKey('amount', $breakdown);
        $this->assertArrayHasKey('breakdown', $breakdown);
    }

    #[Test]
    public function it_handles_float_precision_in_calculations(): void
    {
        $service = new CartTotalsService(app());
        
        // Use prices that might cause floating point issues
        $productId = $this->createProduct(9.99);
        $this->addToCart($productId, 3);
        
        $sum = $service->sum();
        
        // 9.99 * 3 = 29.97
        $this->assertEqualsWithDelta(29.97, $sum, 0.01);
    }

    #[Test]
    public function it_preserves_cart_after_totals_calculation(): void
    {
        $service = new CartTotalsService(app());
        
        $productId = $this->createProduct(50.00);
        $this->addToCart($productId, 2);
        
        // Calculate totals multiple times
        $service->totals();
        $service->totals();
        $service->totals();
        
        // Cart should still contain the items
        $cart = get_cart();
        $this->assertCount(1, $cart);
        $this->assertEquals(2, $cart[0]['qty']);
    }
}
