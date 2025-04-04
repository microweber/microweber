<?php

namespace Modules\Cart\Tests\Integration;

use Tests\TestCase;
use Modules\Cart\Services\CartManager;
use Modules\Coupons\Models\Coupon;
use Modules\Products\Models\Product;

class CouponIntegrationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bypass all application bootstrapping
        $this->withoutMiddleware();
        $this->withoutEvents();
        
        // Use test database
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);
        
        // Manually create tables
        \Artisan::call('migrate:fresh', [
            '--path' => 'modules/Coupons/Database/Migrations',
            '--realpath' => true
        ]);
    }

    public function testCouponWorkflow()
    {
        // Create test data directly
        $product = Product::create(['title' => 'Test', 'price' => 100]);
        $coupon = Coupon::create([
            'coupon_code' => 'TEST10',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => true
        ]);

        // Test core functionality
        $cart = new CartManager();
        $cart->add($product, 1);
        $result = $cart->applyCoupon($coupon->coupon_code);
        
        $this->assertTrue($result);
        $this->assertEquals(90, $cart->total());
    }
}