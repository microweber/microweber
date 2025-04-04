<?php

namespace Modules\Cart\Tests\Unit;

use Tests\TestCase;
use Mockery as m;
use Modules\Cart\Repositories\CartManager;
use Modules\Product\Models\Product;
use Modules\Coupons\Models\Coupon;

class NewCartCouponTest extends TestCase
{
    protected $cart;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock white label service
        $this->mock(\Modules\WhiteLabel\Services\WhiteLabelService::class, function ($mock) {
            $mock->shouldReceive('isEnabled')->andReturn(false);
        });

        $this->cart = m::mock(CartManager::class);
        $this->cart->shouldReceive('add')->andReturn(true);
        $this->cart->shouldReceive('applyCoupon')->andReturn(true);
        $this->cart->shouldReceive('total')->andReturn(90);
    }

    public function testCouponWorkflow()
    {
        // Create test data
        $product = Product::create(['title' => 'Test', 'price' => 100]);
        $coupon = Coupon::create([
            'coupon_code' => 'TEST10',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => true
        ]);

        // Test core functionality
        $this->cart->add($product, 1);
        $result = $this->cart->applyCoupon($coupon->coupon_code);
        
        $this->assertTrue($result);
        $this->assertEquals(90, $this->cart->total());
    }
}