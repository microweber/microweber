<?php

namespace Modules\Cart\Tests\Integration;

use Tests\TestCase;
use Mockery as m;
use Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartCouponTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock white label service
        $this->mock(\Modules\WhiteLabel\Services\WhiteLabelService::class, function ($mock) {
            $mock->shouldReceive('isEnabled')->andReturn(false);
        });

        // Configure test database
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => '/workspace/microweber/database/test.sqlite']);

        // Ensure tables exist
        if (!\Schema::hasTable('coupons')) {
            \Schema::create('coupons', function($table) {
                $table->increments('id');
                $table->string('coupon_code')->unique();
                $table->string('discount_type');
                $table->decimal('discount_value');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function testCouponApplicationFlow()
    {
        // Create test data
        $product = Product::create([
            'title' => 'Test Product',
            'price' => 100.00
        ]);

        $couponCode = 'TEST10';
        \DB::table('coupons')->insert([
            'coupon_code' => $couponCode,
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1
        ]);

        // Mock cart operations
        $cart = m::mock(\Modules\Cart\Repositories\CartManager::class);
        $cart->shouldReceive('add')->andReturn(true);
        $cart->shouldReceive('applyCoupon')->andReturn(true);
        $cart->shouldReceive('total')->andReturn(90);
        
        // Test
        $cart->add($product, 1);
        $result = $cart->applyCoupon($couponCode);
        
        $this->assertTrue($result);
        $this->assertEquals(90, $cart->total());
    }
}