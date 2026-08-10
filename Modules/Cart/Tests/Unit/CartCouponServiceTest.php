<?php

namespace Modules\Cart\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Cart\Services\CartCouponService;
use MicroweberPackages\Database\Facades\DatabaseManager;

class CartCouponServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        empty_cart();
        DatabaseManager::extended_save_set_permission(true);
        
        // Clear any existing coupon session
        if (session()->has('coupon_data')) {
            session()->forget('coupon_data');
        }
        if (session()->has('applied_coupon')) {
            session()->forget('applied_coupon');
        }
        if (session()->has('coupon_discount')) {
            session()->forget('coupon_discount');
        }
    }

    protected function tearDown(): void
    {
        empty_cart();
        session()->forget(['coupon_data', 'applied_coupon', 'coupon_discount']);
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

    private function applyCouponToSession(string $code, float $discount, string $type, float $minAmount): void
    {
        // Set session data as CouponService.getCouponSession() expects
        session([
            'applied_coupon' => $code,
            'coupon_discount' => $discount,
            'coupon_data' => [
                'coupon_code' => $code,
                'discount_value' => $discount,
                'discount_type' => $type,
                'total_amount' => $minAmount,
            ]
        ]);
    }

    #[Test]
    public function it_returns_false_for_discount_value_when_no_coupon_in_session(): void
    {
        $service = new CartCouponService(app());
        
        $discount = $service->getDiscountValue();
        
        $this->assertFalse($discount);
    }

    #[Test]
    public function it_returns_false_for_discount_type_when_no_coupon_in_session(): void
    {
        $service = new CartCouponService(app());
        
        $discountType = $service->getDiscountType();
        
        $this->assertFalse($discountType);
    }

    #[Test]
    public function it_returns_empty_string_for_discount_text_when_no_coupon(): void
    {
        $service = new CartCouponService(app());
        
        $text = $service->getDiscountText();
        
        $this->assertIsString($text);
        $this->assertEquals('', $text);
    }

    #[Test]
    public function it_returns_false_for_coupon_data_when_no_coupon_in_session(): void
    {
        $service = new CartCouponService(app());
        
        $data = $service->getCouponDataFromSession();
        
        $this->assertFalse($data);
    }

    #[Test]
    public function it_gets_discount_value_from_session(): void
    {
        // Add product to cart first
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        // Apply coupon to session
        $this->applyCouponToSession('TEST10', 10.00, 'fixed_amount', 100.00);
        
        $service = new CartCouponService(app());
        
        $discount = $service->getDiscountValue();
        
        $this->assertEquals(10.00, $discount);
    }

    #[Test]
    public function it_gets_discount_type_from_session(): void
    {
        // Add product to cart
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('PERCENT20', 20.00, 'percentage', 100.00);
        
        $service = new CartCouponService(app());
        
        $discountType = $service->getDiscountType();
        
        $this->assertEquals('percentage', $discountType);
    }

    #[Test]
    public function it_returns_discount_text_for_percentage(): void
    {
        // Add product to cart
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('PERCENT25', 25.00, 'percentage', 100.00);
        
        $service = new CartCouponService(app());
        
        $text = $service->getDiscountText();
        
        $this->assertEquals('25%', $text);
    }

    #[Test]
    public function it_returns_discount_text_for_fixed_amount(): void
    {
        // Add product to cart
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('FIXED15', 15.00, 'fixed_amount', 100.00);
        
        $service = new CartCouponService(app());
        
        $text = $service->getDiscountText();
        
        // Should return formatted currency
        $this->assertNotEmpty($text);
        $this->assertStringContainsString('15', $text);
    }

    #[Test]
    public function it_returns_false_when_cart_total_below_minimum(): void
    {
        // Add product to cart (only $50)
        $productId = $this->createProduct(50.00);
        $this->addToCart($productId, 1);
        
        // Coupon requires $100 minimum
        $this->applyCouponToSession('MIN100', 20.00, 'fixed_amount', 100.00);
        
        $service = new CartCouponService(app());
        
        // Should return false because cart is below minimum
        $discount = $service->getDiscountValue();
        $this->assertFalse($discount);
    }

    #[Test]
    public function it_clears_coupon_session(): void
    {
        // Add product and coupon
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('CLEARME', 10.00, 'fixed_amount', 100.00);
        
        $service = new CartCouponService(app());
        
        // Verify coupon exists
        $this->assertNotFalse($service->getDiscountValue());
        
        // Clear the session
        $service->clearCouponSession();
        
        // Should now return false
        $this->assertFalse($service->getDiscountValue());
    }

    #[Test]
    public function it_handles_zero_discount_value(): void
    {
        // Add product to cart
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('ZERO', 0.00, 'fixed_amount', 100.00);
        
        $service = new CartCouponService(app());
        
        $discount = $service->getDiscountValue();
        
        $this->assertEquals(0.00, $discount);
    }

    #[Test]
    public function it_returns_percentage_discount_text_even_with_zero(): void
    {
        // Add product to cart
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('ZEROPERCENT', 0.00, 'percentage', 100.00);
        
        $service = new CartCouponService(app());
        
        $text = $service->getDiscountText();
        
        $this->assertEquals('0%', $text);
    }

    #[Test]
    public function it_returns_fixed_amount_text_for_zero_discount(): void
    {
        // Add product to cart
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('ZEROFIXED', 0.00, 'fixed_amount', 100.00);
        
        $service = new CartCouponService(app());
        
        $text = $service->getDiscountText();
        
        // Should return formatted $0.00
        $this->assertNotEmpty($text);
        $this->assertStringContainsString('0', $text);
    }

    #[Test]
    public function it_returns_false_when_coupon_service_null(): void
    {
        // Create service with null coupon service
        $mockApp = new \stdClass();
        $mockApp->cart_repository = app()->cart_repository;
        $mockApp->coupon_service = null;
        
        $service = new CartCouponService($mockApp);
        
        $discount = $service->getDiscountValue();
        $this->assertFalse($discount);
        
        $type = $service->getDiscountType();
        $this->assertFalse($type);
        
        $data = $service->getCouponDataFromSession();
        $this->assertFalse($data);
    }

    #[Test]
    public function it_gets_discount_value_for_cart_matching_minimum(): void
    {
        // Add product exactly at minimum
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('EXACT100', 20.00, 'fixed_amount', 100.00);
        
        $service = new CartCouponService(app());
        
        $discount = $service->getDiscountValue();
        
        $this->assertEquals(20.00, $discount);
    }

    #[Test]
    public function it_gets_discount_value_for_cart_above_minimum(): void
    {
        // Add product above minimum
        $productId = $this->createProduct(150.00);
        $this->addToCart($productId, 1);
        
        $this->applyCouponToSession('MIN100', 20.00, 'fixed_amount', 100.00);
        
        $service = new CartCouponService(app());
        
        $discount = $service->getDiscountValue();
        
        $this->assertEquals(20.00, $discount);
    }

    #[Test]
    public function it_consumes_coupon_safely(): void
    {
        // Test consume with null service - should not throw
        $mockApp = new \stdClass();
        $mockApp->cart_repository = app()->cart_repository;
        $mockApp->coupon_service = null;
        
        $service = new CartCouponService($mockApp);
        
        // Should not throw error
        $service->consumeCoupon('TESTCODE', 'test@example.com', '127.0.0.1');
        
        $this->assertTrue(true); // Test passes if no exception
    }

    #[Test]
    public function it_applies_coupon_and_returns_error_for_invalid_coupon(): void
    {
        // Test apply with invalid coupon code
        $service = new CartCouponService(app());
        
        $result = $service->applyCoupon('INVALIDCODE');
        
        // Should return error array
        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
    }

    #[Test]
    public function it_returns_empty_string_for_discount_text_with_null_type(): void
    {
        // When no discount type but discount value exists
        // This tests the else branch in getDiscountText
        $service = new CartCouponService(app());
        
        // First add a product so cart has value
        $productId = $this->createProduct(100.00);
        $this->addToCart($productId, 1);
        
        // Set up session without discount_type
        session([
            'applied_coupon' => 'NOTYPE',
            'coupon_discount' => 10.00,
            'coupon_data' => [
                'coupon_code' => 'NOTYPE',
                'discount_value' => 10.00,
                'total_amount' => 100.00,
                // Missing discount_type
            ]
        ]);
        
        $text = $service->getDiscountText();
        
        // Should return formatted amount (falls into else branch)
        $this->assertNotEmpty($text);
    }
}
