<?php

namespace Modules\Cart\Tests\Integration;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class CouponEndToEndTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Force test environment
        putenv('APP_ENV=testing');
        
        // Configure test database
        config(['database.default' => 'test_sqlite']);
        config(['database.connections.test_sqlite' => [
            'driver' => 'sqlite',
            'database' => '/workspace/microweber/database/test.sqlite',
            'prefix' => '',
        ]]);

        // Refresh connection
        DB::purge();
        DB::reconnect();

        // Debug output
        echo "=== TEST CONFIGURATION ===\n";
        echo "Environment: ".app()->environment()."\n";
        echo "Default connection: ".config('database.default')."\n";
        echo "Database path: ".config('database.connections.test_sqlite.database')."\n";
        echo "Tables: ".implode(', ', DB::connection()->getDoctrineSchemaManager()->listTableNames())."\n";

        // Initialize test data
        DB::connection('test_sqlite')->table('site_options')->insertOrIgnore([
            'option_group' => 'white_label',
            'option_key' => 'enabled',
            'option_value' => '0'
        ]);
    }

    protected function tearDown(): void
    {
        DB::connection('test_sqlite')->table('products')->delete();
        DB::connection('test_sqlite')->table('coupons')->delete();
        parent::tearDown();
    }

    public function testCouponApplication()
    {
        // Create test data
        $productId = DB::table('products')->insertGetId([
            'title' => 'Test Product',
            'price' => 100.00
        ]);

        DB::table('coupons')->insert([
            'coupon_code' => 'TEST10',
            'discount_type' => 'fixed_amount',
            'discount_value' => 10,
            'is_active' => 1
        ]);

        // Test workflow
        $cart = app('cart');
        $cart->add($productId, 1);
        
        $result = $cart->applyCoupon('TEST10');
        
        $this->assertTrue($result);
        $this->assertEquals(90, $cart->total());
    }
}