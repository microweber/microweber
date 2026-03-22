<?php

namespace Modules\Product\Tests\Unit;

use Illuminate\Support\Facades\Notification;
use Modules\Content\Models\Content;
use Modules\Product\Models\ProductInventoryAlert;
use Modules\Product\Models\ProductInventoryMovement;
use Modules\Product\Models\ProductStockReservation;
use Modules\Product\Models\ProductVariantCombination;
use Modules\Product\Notifications\LowStockNotification;
use Modules\Product\Services\InventoryService;
use Modules\User\Models\User;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    protected InventoryService $inventoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->inventoryService = app(InventoryService::class);
        Notification::fake();
    }

    protected function createProduct(array $overrides = []): Content
    {
        $product = Content::factory()->create([
            'content_type' => 'product',
            'title' => $overrides['title'] ?? 'Test Product',
            'is_active' => $overrides['is_active'] ?? 1,
            'is_deleted' => $overrides['is_deleted'] ?? 0,
        ]);

        // Set quantity via content_data using array syntax
        $qty = $overrides['qty'] ?? 100;
        $product->setContentData(['qty' => $qty, 'track_quantity' => 1]);
        $product->save(); // Required to persist content_data

        // Refresh to ensure content data is loaded
        return Content::find($product->id);
    }

    protected function createVariant(int $productId, array $overrides = []): ProductVariantCombination
    {
        return ProductVariantCombination::create(array_merge([
            'product_id' => $productId,
            'sku' => 'VAR-' . uniqid(),
            'quantity' => 50,
            'quantity_type' => 'limited',
            'track_quantity' => true,
            'low_stock_threshold' => 10,
            'is_active' => true,
        ], $overrides));
    }

    /** @test */
    public function it_can_get_stock_quantity_for_product(): void
    {
        $product = $this->createProduct(['qty' => 100]);

        $stock = $this->inventoryService->getStock($product->id);

        $this->assertEquals(100, $stock);
    }

    /** @test */
    public function it_can_get_stock_quantity_for_variant(): void
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, ['quantity' => 50]);

        $stock = $this->inventoryService->getStock($product->id, $variant->id);

        $this->assertEquals(50, $stock);
    }

    /** @test */
    public function it_returns_unlimited_stock_for_nolimit_quantity(): void
    {
        $product = $this->createProduct();
        $product->setContentData(['qty' => 'nolimit']);
        $product->save();

        $stock = $this->inventoryService->getStock($product->id);

        $this->assertEquals(PHP_INT_MAX, $stock);
    }

    /** @test */
    public function it_can_restock_product(): void
    {
        $product = $this->createProduct(['qty' => 50]);

        $result = $this->inventoryService->restock($product->id, 25, null, 'Test restock');

        $this->assertTrue($result);
        $this->assertEquals(75, $this->inventoryService->getStock($product->id));

        // Check movement was logged
        $movement = ProductInventoryMovement::forProduct($product->id)
            ->ofType(ProductInventoryMovement::TYPE_RESTOCK)
            ->first();
        $this->assertNotNull($movement);
        $this->assertEquals(25, $movement->quantity_change);
        $this->assertEquals(50, $movement->quantity_before);
        $this->assertEquals(75, $movement->quantity_after);
    }

    /** @test */
    public function it_can_restock_variant(): void
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, ['quantity' => 25]);

        $result = $this->inventoryService->restock($product->id, 25, $variant->id);

        $this->assertTrue($result);
        $this->assertEquals(50, $this->inventoryService->getStock($product->id, $variant->id));
    }

    /** @test */
    public function it_can_deduct_stock_for_sale(): void
    {
        $product = $this->createProduct(['qty' => 100]);

        $result = $this->inventoryService->deductStock($product->id, 20, null, 'Test sale', null, 'order', 1);

        $this->assertTrue($result);
        $this->assertEquals(80, $this->inventoryService->getStock($product->id));

        // Check movement was logged
        $movement = ProductInventoryMovement::forProduct($product->id)
            ->ofType(ProductInventoryMovement::TYPE_SALE)
            ->first();
        $this->assertNotNull($movement);
        $this->assertEquals(-20, $movement->quantity_change);
        $this->assertEquals('order', $movement->reference_type);
        $this->assertEquals(1, $movement->reference_id);
    }

    /** @test */
    public function it_creates_low_stock_alert_when_threshold_reached(): void
    {
        $product = $this->createProduct(['qty' => 15]);
        $product->low_stock_threshold = 10;
        $product->save();

        // Deduct stock to reach low stock level
        $this->inventoryService->deductStock($product->id, 5);

        // Now quantity is 10, which equals threshold
        // Deduct more to trigger alert
        $this->inventoryService->deductStock($product->id, 2);

        $alert = ProductInventoryAlert::forProduct($product->id)
            ->unresolved()
            ->first();

        $this->assertNotNull($alert);
        $this->assertEquals(ProductInventoryAlert::TYPE_LOW_STOCK, $alert->alert_type);
        $this->assertEquals(8, $alert->current_quantity);
    }

    /** @test */
    public function it_creates_out_of_stock_alert_when_stock_depleted(): void
    {
        $product = $this->createProduct(['qty' => 5]);

        // Deduct all remaining stock
        $this->inventoryService->deductStock($product->id, 5);

        $alert = ProductInventoryAlert::forProduct($product->id)
            ->unresolved()
            ->first();

        $this->assertNotNull($alert);
        $this->assertEquals(ProductInventoryAlert::TYPE_OUT_OF_STOCK, $alert->alert_type);
        $this->assertEquals(0, $alert->current_quantity);
    }

    /** @test */
    public function it_resolves_alerts_when_stock_is_replenished(): void
    {
        $product = $this->createProduct(['qty' => 5]);

        // Create alert by depleting stock
        $this->inventoryService->deductStock($product->id, 5);

        $alert = ProductInventoryAlert::forProduct($product->id)->unresolved()->first();
        $this->assertNotNull($alert);

        // Restock to resolve alert
        $this->inventoryService->restock($product->id, 20);

        $alert->refresh();
        $this->assertTrue($alert->is_resolved);
        $this->assertNotNull($alert->resolved_at);
    }

    /** @test */
    public function it_can_adjust_stock_quantity(): void
    {
        $product = $this->createProduct(['qty' => 50]);

        $result = $this->inventoryService->adjustStock($product->id, 75, null, 'Stock count adjustment', 1);

        $this->assertTrue($result);
        $this->assertEquals(75, $this->inventoryService->getStock($product->id));

        $movement = ProductInventoryMovement::ofType(ProductInventoryMovement::TYPE_ADJUSTMENT)->first();
        $this->assertNotNull($movement);
        $this->assertEquals(25, $movement->quantity_change); // 75 - 50 = 25
        $this->assertEquals(1, $movement->user_id);
    }

    /** @test */
    public function it_throws_exception_for_invalid_restock_quantity(): void
    {
        $product = $this->createProduct();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Restock quantity must be positive');

        $this->inventoryService->restock($product->id, 0);
    }

    /** @test */
    public function it_throws_exception_for_negative_adjustment(): void
    {
        $product = $this->createProduct();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Stock quantity cannot be negative');

        $this->inventoryService->adjustStock($product->id, -1);
    }

    /** @test */
    public function it_can_reserve_stock(): void
    {
        $product = $this->createProduct(['qty' => 50]);

        $reservation = $this->inventoryService->reserveStock(
            $product->id,
            10,
            ProductStockReservation::TYPE_CART,
            null,
            'test-session-id',
            null,
            null,
            30
        );

        $this->assertInstanceOf(ProductStockReservation::class, $reservation);
        $this->assertEquals(10, $reservation->quantity);
        $this->assertEquals('cart', $reservation->reservation_type);
        $this->assertEquals('test-session-id', $reservation->session_id);
        $this->assertTrue($reservation->is_active);
    }

    /** @test */
    public function it_throws_exception_when_insufficient_stock_for_reservation(): void
    {
        $product = $this->createProduct(['qty' => 5]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient stock available');

        $this->inventoryService->reserveStock($product->id, 10, ProductStockReservation::TYPE_CART);
    }

    /** @test */
    public function it_calculates_available_quantity_correctly(): void
    {
        $product = $this->createProduct(['qty' => 50]);

        // Reserve 10 units
        $this->inventoryService->reserveStock(
            $product->id,
            10,
            ProductStockReservation::TYPE_CART,
            null,
            'test-session'
        );

        $available = $this->inventoryService->getAvailableQuantity($product->id);

        $this->assertEquals(40, $available); // 50 - 10 reserved
    }

    /** @test */
    public function it_can_release_reservation(): void
    {
        $product = $this->createProduct(['qty' => 50]);

        $reservation = $this->inventoryService->reserveStock(
            $product->id,
            10,
            ProductStockReservation::TYPE_CART,
            null,
            'test-session'
        );

        $result = $this->inventoryService->releaseReservation($reservation->id, 'Test release');

        $this->assertTrue($result);
        $this->assertFalse($reservation->fresh()->is_active);

        // Check movement was logged
        $movement = ProductInventoryMovement::ofType(ProductInventoryMovement::TYPE_RESERVATION_RELEASED)->first();
        $this->assertNotNull($movement);
        $this->assertEquals(10, $movement->quantity_change);
    }

    /** @test */
    public function it_processes_return_correctly(): void
    {
        $product = $this->createProduct(['qty' => 50]);

        $result = $this->inventoryService->processReturn($product->id, 5, null, 1, 'Customer return');

        $this->assertTrue($result);
        $this->assertEquals(55, $this->inventoryService->getStock($product->id));

        // Returns use RESTOCK type with 'order' reference type
        $movement = ProductInventoryMovement::ofType(ProductInventoryMovement::TYPE_RESTOCK)
            ->where('reference_type', 'order')
            ->first();
        $this->assertNotNull($movement);
        $this->assertEquals(5, $movement->quantity_change);
        $this->assertEquals(50, $movement->quantity_before);
        $this->assertEquals(55, $movement->quantity_after);
        $this->assertEquals('Customer return', $movement->notes);
    }

    /** @test */
    public function it_can_get_inventory_summary(): void
    {
        $product = $this->createProduct(['qty' => 25]);
        $product->low_stock_threshold = 10;
        $product->save();

        // Reserve some stock
        $this->inventoryService->reserveStock(
            $product->id,
            5,
            ProductStockReservation::TYPE_CART,
            null,
            'test-session'
        );

        $summary = $this->inventoryService->getInventorySummary($product->id);

        $this->assertEquals($product->id, $summary['product_id']);
        $this->assertEquals(25, $summary['stock_quantity']);
        $this->assertEquals(5, $summary['reserved_quantity']);
        $this->assertEquals(20, $summary['available_quantity']);
        $this->assertEquals(10, $summary['low_stock_threshold']);
        $this->assertFalse($summary['is_low_stock']);
        $this->assertFalse($summary['is_out_of_stock']);
    }

    /** @test */
    public function it_can_cleanup_expired_reservations(): void
    {
        // Clear existing reservations first
        ProductStockReservation::query()->delete();

        $product = $this->createProduct(['qty' => 100]);

        // Create expired reservation
        $reservation = ProductStockReservation::create([
            'product_id' => $product->id,
            'quantity' => 10,
            'reservation_type' => ProductStockReservation::TYPE_CART,
            'session_id' => 'expired-session',
            'expires_at' => now()->subMinute(), // Already expired
            'is_active' => true,
        ]);

        $count = $this->inventoryService->cleanupExpiredReservations();

        $this->assertEquals(1, $count);
        $this->assertFalse($reservation->fresh()->is_active);
    }

    /** @test */
    public function it_can_get_movement_history(): void
    {
        $product = $this->createProduct(['qty' => 100]);

        // Create some movements
        $this->inventoryService->restock($product->id, 20);
        $this->inventoryService->deductStock($product->id, 10);
        $this->inventoryService->adjustStock($product->id, 120, null, 'Count adjustment');

        $history = $this->inventoryService->getMovementHistory($product->id);

        $this->assertCount(3, $history);
    }

    /** @test */
    public function it_can_bulk_update_stock(): void
    {
        $product1 = $this->createProduct(['qty' => 50]);
        $product2 = $this->createProduct(['qty' => 30]);

        $updates = [
            ['product_id' => $product1->id, 'quantity' => 100],
            ['product_id' => $product2->id, 'quantity' => 75],
        ];

        $results = $this->inventoryService->bulkUpdateStock($updates, 1, 'Bulk stock update');

        $this->assertCount(2, $results['success']);
        $this->assertEmpty($results['failed']);

        $this->assertEquals(100, $this->inventoryService->getStock($product1->id));
        $this->assertEquals(75, $this->inventoryService->getStock($product2->id));
    }

    /** @test */
    public function it_handles_variant_inventory_operations(): void
    {
        $product = $this->createProduct();
        $variant = $this->createVariant($product->id, ['quantity' => 20]);

        // Deduct from variant
        $this->inventoryService->deductStock($product->id, 5, $variant->id);
        $this->assertEquals(15, $this->inventoryService->getStock($product->id, $variant->id));

        // Product stock should be unchanged
        $this->assertEquals(100, $this->inventoryService->getStock($product->id));

        // Check movement was logged for variant
        $movement = ProductInventoryMovement::forVariant($variant->id)->first();
        $this->assertNotNull($movement);
        $this->assertEquals(-5, $movement->quantity_change);
    }

    /** @test */
    public function it_checks_stock_availability_correctly(): void
    {
        $product = $this->createProduct(['qty' => 10]);

        // Reserve 5 units
        $this->inventoryService->reserveStock($product->id, 5, ProductStockReservation::TYPE_CART);

        // Should have 5 available
        $this->assertTrue($this->inventoryService->hasStock($product->id, 5));
        $this->assertFalse($this->inventoryService->hasStock($product->id, 6));

        // Unlimited stock check
        $product2 = $this->createProduct();
        $product2->setContentData(['qty' => 'nolimit']);
        $product2->save();
        $this->assertTrue($this->inventoryService->hasStock($product2->id, 10000));
    }

    /** @test */
    public function it_prevents_negative_stock(): void
    {
        $product = $this->createProduct(['qty' => 5]);

        // Try to deduct more than available
        $this->inventoryService->deductStock($product->id, 10);

        // Stock should be 0, not negative
        $this->assertEquals(0, $this->inventoryService->getStock($product->id));
    }

    /** @test */
    public function it_logs_all_movements_with_correct_quantity_values(): void
    {
        $product = $this->createProduct(['qty' => 100]);

        $this->inventoryService->deductStock($product->id, 25);

        $movement = ProductInventoryMovement::forProduct($product->id)->first();
        $this->assertEquals(-25, $movement->quantity_change);
        $this->assertEquals(100, $movement->quantity_before);
        $this->assertEquals(75, $movement->quantity_after);
    }

    /** @test */
    public function it_can_get_low_stock_alerts(): void
    {
        // Clear any existing alerts first
        ProductInventoryAlert::query()->delete();

        $product1 = $this->createProduct(['qty' => 5]);
        $product2 = $this->createProduct(['qty' => 3]);

        // Create alerts
        ProductInventoryAlert::create([
            'product_id' => $product1->id,
            'alert_type' => ProductInventoryAlert::TYPE_LOW_STOCK,
            'current_quantity' => 5,
            'threshold_quantity' => 10,
            'is_resolved' => false,
        ]);

        ProductInventoryAlert::create([
            'product_id' => $product2->id,
            'alert_type' => ProductInventoryAlert::TYPE_OUT_OF_STOCK,
            'current_quantity' => 0,
            'threshold_quantity' => 10,
            'is_resolved' => false,
        ]);

        $alerts = $this->inventoryService->getLowStockAlerts();

        $this->assertCount(2, $alerts);
    }

    /** @test */
    public function alert_has_correct_severity_attributes(): void
    {
        // Clear existing alerts first
        ProductInventoryAlert::query()->delete();

        $product = $this->createProduct(['qty' => 100]);

        // Deduct to reach low stock (qty 8, threshold 10)
        // 8 <= 10 triggers LOW_STOCK
        $this->inventoryService->deductStock($product->id, 92); // Now at 8

        // Get the most recent alert
        $alert = ProductInventoryAlert::forProduct($product->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Quantity 8 with threshold 10 should be LOW_STOCK
        $this->assertNotNull($alert);
        $this->assertEquals(ProductInventoryAlert::TYPE_LOW_STOCK, $alert->alert_type);
        $this->assertEquals('medium', $alert->severity);
        $this->assertEquals('info', $alert->severity_color);

        // Now test out of stock with a fresh product
        $product2 = $this->createProduct(['qty' => 5]);

        // Deplete stock completely - should create OUT_OF_STOCK
        $this->inventoryService->deductStock($product2->id, 5); // Now at 0

        $alert2 = ProductInventoryAlert::forProduct($product2->id)
            ->where('alert_type', ProductInventoryAlert::TYPE_OUT_OF_STOCK)
            ->first();
        $this->assertNotNull($alert2);
        $this->assertEquals('high', $alert2->severity);
        $this->assertEquals('warning', $alert2->severity_color);
    }

    /** @test */
    public function it_can_resolve_alert_with_user_and_notes(): void
    {
        $product = $this->createProduct(['qty' => 5]);

        $alert = ProductInventoryAlert::create([
            'product_id' => $product->id,
            'alert_type' => ProductInventoryAlert::TYPE_LOW_STOCK,
            'current_quantity' => 5,
            'threshold_quantity' => 10,
            'is_resolved' => false,
        ]);

        $result = $alert->resolve(1, 'Stock replenished from supplier');

        $this->assertTrue($result);
        $this->assertTrue($alert->fresh()->is_resolved);
        $this->assertEquals(1, $alert->resolved_by);
        $this->assertEquals('Stock replenished from supplier', $alert->resolution_notes);
        $this->assertNotNull($alert->resolved_at);
    }

    /** @test */
    public function reservation_is_marked_as_expired_correctly(): void
    {
        $product = $this->createProduct();

        $reservation = ProductStockReservation::create([
            'product_id' => $product->id,
            'quantity' => 10,
            'reservation_type' => ProductStockReservation::TYPE_CART,
            'session_id' => 'test-session',
            'expires_at' => now()->subMinute(),
            'is_active' => true,
        ]);

        $this->assertTrue($reservation->isExpired());
        $this->assertFalse($reservation->isValid());
    }

    /** @test */
    public function reservation_can_be_extended(): void
    {
        $product = $this->createProduct();

        $reservation = ProductStockReservation::create([
            'product_id' => $product->id,
            'quantity' => 10,
            'reservation_type' => ProductStockReservation::TYPE_CART,
            'session_id' => 'test-session',
            'expires_at' => now()->addMinutes(10),
            'is_active' => true,
        ]);

        $originalExpiry = $reservation->expires_at;
        $reservation->extend(30);

        $this->assertTrue($reservation->fresh()->expires_at->greaterThan($originalExpiry));
    }

    /** @test */
    public function it_can_convert_reservation_to_cart_or_order(): void
    {
        $product = $this->createProduct();

        $reservation = ProductStockReservation::create([
            'product_id' => $product->id,
            'quantity' => 10,
            'reservation_type' => ProductStockReservation::TYPE_HOLD,
            'session_id' => 'test-session',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
        ]);

        // Convert to order
        $reservation->convertToOrder(123);

        $this->assertEquals(ProductStockReservation::TYPE_ORDER, $reservation->fresh()->reservation_type);
        $this->assertEquals(123, $reservation->order_id);
        $this->assertNull($reservation->session_id);

        // Convert to cart
        $reservation->convertToCart('new-session');

        $this->assertEquals(ProductStockReservation::TYPE_CART, $reservation->fresh()->reservation_type);
        $this->assertEquals('new-session', $reservation->session_id);
        $this->assertNull($reservation->order_id);
    }
}
