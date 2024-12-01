<?php

namespace Modules\Cart\Tests\Unit;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Cart\Repositories\CartRepository;

class CartRepositoryTest extends TestCase
{
    protected CartRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CartRepository();

        // Run migrations if needed
        if (!Schema::hasTable('cart')) {
            $this->artisan('module:migrate', ['module' => 'Cart']);
        }
    }

    public function testGetCartItems()
    {
        // Create test cart items
        $sessionId = app()->user_manager->session_id();

        DB::table('cart')->insert([
            'session_id' => $sessionId,
            'order_completed' => 0,
            'price' => 100,
            'qty' => 2,
            'title' => 'Test Product 1',
            'rel_id' => 1,
            'rel_type' => 'content',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('cart')->insert([
            'session_id' => $sessionId,
            'order_completed' => 0,
            'price' => 150,
            'qty' => 1,
            'title' => 'Test Product 2',
            'rel_id' => 2,
            'rel_type' => 'content',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $cartItems = $this->repository->getCartItems();

        $this->assertIsArray($cartItems);
        $this->assertCount(2, $cartItems);
        $this->assertEquals(2, $cartItems[0]['qty']);
        $this->assertEquals(1, $cartItems[1]['qty']);
        $this->assertEquals(100, $cartItems[0]['price']);
        $this->assertEquals(150, $cartItems[1]['price']);
    }

    public function testGetCartAmount()
    {
        // Create test cart items
        $sessionId = app()->user_manager->session_id();

        DB::table('cart')->insert([
            'session_id' => $sessionId,
            'order_completed' => 0,
            'price' => 100,
            'qty' => 2,
            'title' => 'Test Product 1',
            'rel_id' => 1,
            'rel_type' => 'content',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('cart')->insert([
            'session_id' => $sessionId,
            'order_completed' => 0,
            'price' => 150,
            'qty' => 1,
            'title' => 'Test Product 2',
            'rel_id' => 2,
            'rel_type' => 'content',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $amount = $this->repository->getCartAmount();

        // Expected: (100 * 2) + (150 * 1) = 350
        $this->assertEquals(350, $amount);
    }

    public function testGetCartItemsCount()
    {
        // Create test cart items
        $sessionId = app()->user_manager->session_id();

        DB::table('cart')->insert([
            'session_id' => $sessionId,
            'order_completed' => 0,
            'price' => 100,
            'qty' => 2,
            'title' => 'Test Product 1',
            'rel_id' => 1,
            'rel_type' => 'content',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('cart')->insert([
            'session_id' => $sessionId,
            'order_completed' => 0,
            'price' => 150,
            'qty' => 1,
            'title' => 'Test Product 2',
            'rel_id' => 2,
            'rel_type' => 'content',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $count = $this->repository->getCartItemsCount();

        // Expected: 2 + 1 = 3 items total
        $this->assertEquals(3, $count);
    }

    protected function tearDown(): void
    {
        // Clean up test data
        DB::table('cart')->where('session_id', app()->user_manager->session_id())->delete();
        parent::tearDown();
    }
}
