<?php

namespace Modules\Cart\Tests\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartCouponIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bypass all white label checks
        \Illuminate\Support\Facades\App::bind('white_label', function() {
            return new class {
                public function isEnabled() { return false; }
            };
        });

        // Use test database
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => '/workspace/microweber/database/test.sqlite']);
        
        // Ensure clean state
        \DB::table('products')->delete();
        \DB::table('coupons')->delete();
    }

    public function testCouponWorkflow()
    {
        // Create test product
        \DB::table('products')->insert([
            'title' => 'Test Product',
            'price' => 100.00
        ]);

        // Create test coupon
        \DB::table('coupons')->insert([
            'coupon_code' => 'TEST10',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1
        ]);

        // Test cart operations
        $cart = app('cart');
        $cart->add(\DB::table('products')->first()->id, 1);
        
        $result = $cart->applyCoupon('TEST10');
        
        $this->assertTrue($result);
        $this->assertEquals(90, $cart->total());
    }
}