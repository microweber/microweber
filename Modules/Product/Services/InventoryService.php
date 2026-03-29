<?php

namespace Modules\Product\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Content\Models\Content;
use Modules\Product\Models\ProductInventoryAlert;
use Modules\Product\Models\ProductInventoryMovement;
use Modules\Product\Models\ProductStockReservation;
use Modules\Product\Models\ProductVariantCombination;

/**
 * Inventory Service
 *
 * Handles all inventory-related operations including:
 * - Stock tracking and updates
 * - Inventory movements logging
 * - Stock reservations
 * - Low stock alerts
 * - Available quantity calculations
 */
class InventoryService
{
    /**
     * Default reservation time in minutes
     */
    public const DEFAULT_RESERVATION_MINUTES = 30;

    /**
     * Default low stock threshold
     */
    public const DEFAULT_LOW_STOCK_THRESHOLD = 10;

    /**
     * Get current stock quantity for a product or variant
     */
    public function getStock(int $productId, ?int $variantId = null): int
    {
        if ($variantId) {
            $variant = ProductVariantCombination::find($variantId);
            return $variant ? $variant->quantity : 0;
        }

        // For non-variant products, get from content_data
        $product = Content::find($productId);
        if (!$product) {
            return 0;
        }

        $contentData = $product->contentData()->where('field_name', 'qty')->first();
        $quantity = $contentData ? $contentData->field_value : 'nolimit';

        return $quantity === 'nolimit' ? PHP_INT_MAX : (int) $quantity;
    }

    /**
     * Get available quantity (stock minus reservations)
     */
    public function getAvailableQuantity(int $productId, ?int $variantId = null): int
    {
        $stock = $this->getStock($productId, $variantId);

        if ($stock === PHP_INT_MAX) {
            return PHP_INT_MAX;
        }

        $reserved = $this->getReservedQuantity($productId, $variantId);

        return max(0, $stock - $reserved);
    }

    /**
     * Get reserved quantity for a product or variant
     */
    public function getReservedQuantity(int $productId, ?int $variantId = null): int
    {
        $query = ProductStockReservation::active()
            ->forProduct($productId);

        if ($variantId) {
            $query->forVariant($variantId);
        }

        return $query->sum('quantity');
    }

    /**
     * Check if stock is available for a given quantity
     */
    public function hasStock(int $productId, int $quantity, ?int $variantId = null): bool
    {
        $available = $this->getAvailableQuantity($productId, $variantId);

        if ($available === PHP_INT_MAX) {
            return true;
        }

        return $available >= $quantity;
    }

    /**
     * Reserve stock for a cart item or order
     */
    public function reserveStock(
        int $productId,
        int $quantity,
        string $reservationType,
        ?int $variantId = null,
        ?string $sessionId = null,
        ?int $userId = null,
        ?int $orderId = null,
        ?int $minutes = null
    ): ProductStockReservation {
        $minutes = $minutes ?? self::DEFAULT_RESERVATION_MINUTES;

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $reservationType,
            $variantId,
            $sessionId,
            $userId,
            $orderId,
            $minutes
        ) {
            // Check if enough stock is available
            if (!$this->hasStock($productId, $quantity, $variantId)) {
                throw new \Exception('Insufficient stock available');
            }

            // Create the reservation
            $reservation = ProductStockReservation::create([
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'reservation_type' => $reservationType,
                'session_id' => $sessionId,
                'user_id' => $userId,
                'order_id' => $orderId,
                'expires_at' => now()->addMinutes($minutes),
                'is_active' => true,
            ]);

            // Log the reservation
            $this->logMovement(
                $productId,
                -$quantity,
                $reservationType,
                'reservation',
                $reservation->id,
                $variantId,
                $userId,
                "Stock reserved for {$reservationType}"
            );

            return $reservation;
        });
    }

    /**
     * Release (remove) a stock reservation
     */
    public function releaseReservation(int $reservationId, ?string $reason = null): bool
    {
        return DB::transaction(function () use ($reservationId, $reason) {
            $reservation = ProductStockReservation::find($reservationId);

            if (!$reservation || !$reservation->is_active) {
                return false;
            }

            $reservation->release();

        // Log the release
        $this->logMovement(
            $reservation->product_id,
            $reservation->quantity,
            ProductInventoryMovement::TYPE_RESERVATION_RELEASED,
            'reservation',
            $reservation->id,
            $reservation->variant_id,
            null,
            $reason ?? 'Reservation released'
        );

            return true;
        });
    }

    /**
     * Restock inventory (add quantity)
     */
    public function restock(
        int $productId,
        int $quantity,
        ?int $variantId = null,
        ?string $notes = null,
        ?int $userId = null,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): bool {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Restock quantity must be positive');
        }

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $variantId,
            $notes,
            $userId,
            $referenceType,
            $referenceId
        ) {
            $quantityBefore = $this->getStock($productId, $variantId);

            if ($variantId) {
                $variant = ProductVariantCombination::find($variantId);
                if ($variant) {
                    $variant->quantity += $quantity;
                    $variant->last_stock_check = now();
                    $variant->save();
                }
        } else {
            $product = Content::find($productId);
            if ($product) {
                $currentQty = $this->getStock($productId);
                if ($currentQty !== PHP_INT_MAX) {
                    $newQty = $currentQty + $quantity;
                    $product->setContentData(['qty' => $newQty]);
                    $product->save();
                }
            }
        }

            $quantityAfter = $this->getStock($productId, $variantId);

            // Log the movement
            $this->logMovement(
                $productId,
                $quantity,
                ProductInventoryMovement::TYPE_RESTOCK,
                $referenceType,
                $referenceId,
                $variantId,
                $userId,
                $notes ?? 'Inventory restocked',
                $quantityBefore,
                $quantityAfter
            );

            // Check if we need to resolve any alerts
            $this->checkAndResolveAlerts($productId, $variantId);

            return true;
        });
    }

    /**
     * Deduct stock (for sales)
     */
    public function deductStock(
        int $productId,
        int $quantity,
        ?int $variantId = null,
        ?string $notes = null,
        ?int $userId = null,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): bool {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Deduct quantity must be positive');
        }

        return DB::transaction(function () use (
            $productId,
            $quantity,
            $variantId,
            $notes,
            $userId,
            $referenceType,
            $referenceId
        ) {
            $quantityBefore = $this->getStock($productId, $variantId);

            if ($variantId) {
                $variant = ProductVariantCombination::find($variantId);
                if ($variant && $variant->track_quantity) {
                    $variant->quantity = max(0, $variant->quantity - $quantity);
                    $variant->last_stock_check = now();
                    $variant->save();
                }
        } else {
            $product = Content::find($productId);
            if ($product) {
                $currentQty = $this->getStock($productId);
                if ($currentQty !== PHP_INT_MAX) {
                    $newQty = max(0, $currentQty - $quantity);
                    $product->setContentData(['qty' => $newQty]);
                    $product->save();
                }
            }
        }

        $quantityAfter = $this->getStock($productId, $variantId);

            // Log the movement
            $this->logMovement(
                $productId,
                -$quantity,
                ProductInventoryMovement::TYPE_SALE,
                $referenceType,
                $referenceId,
                $variantId,
                $userId,
                $notes ?? 'Stock deducted for sale',
                $quantityBefore,
                $quantityAfter
            );

            // Check stock levels and create alerts if needed
            $this->checkStockLevels($productId, $variantId);

            return true;
        });
    }

    /**
     * Adjust stock quantity (for corrections, damages, etc.)
     */
    public function adjustStock(
        int $productId,
        int $newQuantity,
        ?int $variantId = null,
        ?string $reason = null,
        ?int $userId = null
    ): bool {
        if ($newQuantity < 0) {
            throw new \InvalidArgumentException('Stock quantity cannot be negative');
        }

        return DB::transaction(function () use ($productId, $newQuantity, $variantId, $reason, $userId) {
            $quantityBefore = $this->getStock($productId, $variantId);
            $quantityChange = $newQuantity - $quantityBefore;

            if ($variantId) {
                $variant = ProductVariantCombination::find($variantId);
                if ($variant) {
                    $variant->quantity = $newQuantity;
                    $variant->last_stock_check = now();
                    $variant->save();
                }
        } else {
            $product = Content::find($productId);
            if ($product) {
                $product->setContentData(['qty' => $newQuantity]);
                $product->save();
            }
        }

        // Log the movement
            $this->logMovement(
                $productId,
                $quantityChange,
                ProductInventoryMovement::TYPE_ADJUSTMENT,
                null,
                null,
                $variantId,
                $userId,
                $reason ?? 'Stock adjustment',
                $quantityBefore,
                $newQuantity
            );

            // Check stock levels
            $this->checkStockLevels($productId, $variantId);

            return true;
        });
    }

    /**
     * Process a return (add stock back)
     */
    public function processReturn(
        int $productId,
        int $quantity,
        ?int $variantId = null,
        ?int $orderId = null,
        ?string $notes = null,
        ?int $userId = null
    ): bool {
        return $this->restock(
            $productId,
            $quantity,
            $variantId,
            $notes ?? 'Product return',
            $userId,
            'order',
            $orderId
        );
    }

    /**
     * Check stock levels and create alerts if needed
     */
    public function checkStockLevels(int $productId, ?int $variantId = null): void
    {
        $stock = $this->getStock($productId, $variantId);

        if ($stock === PHP_INT_MAX) {
            return; // Unlimited stock
        }

        $threshold = $this->getLowStockThreshold($productId, $variantId);

        // Determine alert type
        if ($stock <= 0) {
            $alertType = ProductInventoryAlert::TYPE_OUT_OF_STOCK;
        } elseif ($stock <= ($threshold / 2)) {
            $alertType = ProductInventoryAlert::TYPE_CRITICAL;
        } elseif ($stock <= $threshold) {
            $alertType = ProductInventoryAlert::TYPE_LOW_STOCK;
        } else {
            // Stock is above threshold, resolve any existing alerts
            $this->resolveAlerts($productId, $variantId);
            return;
        }

        // Check if alert already exists and is unresolved
        $existingAlert = ProductInventoryAlert::unresolved()
            ->forProduct($productId)
            ->when($variantId, function ($query) use ($variantId) {
                return $query->forVariant($variantId);
            })
            ->first();

        if (!$existingAlert) {
            ProductInventoryAlert::create([
                'product_id' => $productId,
                'variant_id' => $variantId,
                'alert_type' => $alertType,
                'current_quantity' => $stock,
                'threshold_quantity' => $threshold,
                'is_resolved' => false,
            ]);

            // TODO: Send notification to admin
            Log::info("Stock alert created", [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'alert_type' => $alertType,
                'quantity' => $stock,
            ]);
        } else {
            // Update existing alert
            $existingAlert->current_quantity = $stock;
            $existingAlert->save();
        }
    }

    /**
     * Resolve stock alerts
     */
    public function resolveAlerts(int $productId, ?int $variantId = null, ?int $userId = null): void
    {
        $alerts = ProductInventoryAlert::unresolved()
            ->forProduct($productId)
            ->when($variantId, function ($query) use ($variantId) {
                return $query->forVariant($variantId);
            })
            ->get();

        foreach ($alerts as $alert) {
            $alert->resolve($userId, 'Stock level restored');
        }
    }

    /**
     * Check and resolve alerts if stock is now sufficient
     */
    protected function checkAndResolveAlerts(int $productId, ?int $variantId = null): void
    {
        $stock = $this->getStock($productId, $variantId);
        $threshold = $this->getLowStockThreshold($productId, $variantId);

        if ($stock > $threshold) {
            $this->resolveAlerts($productId, $variantId);
        }
    }

    /**
     * Get low stock threshold for a product or variant
     */
    protected function getLowStockThreshold(int $productId, ?int $variantId = null): int
    {
        if ($variantId) {
            $variant = ProductVariantCombination::find($variantId);
            return $variant ? ($variant->low_stock_threshold ?? self::DEFAULT_LOW_STOCK_THRESHOLD) : self::DEFAULT_LOW_STOCK_THRESHOLD;
        }

        $product = Content::find($productId);
        return $product ? ($product->low_stock_threshold ?? self::DEFAULT_LOW_STOCK_THRESHOLD) : self::DEFAULT_LOW_STOCK_THRESHOLD;
    }

    /**
     * Log an inventory movement
     */
    protected function logMovement(
        int $productId,
        int $quantityChange,
        string $type,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?int $variantId = null,
        ?int $userId = null,
        ?string $notes = null,
        ?int $quantityBefore = null,
        ?int $quantityAfter = null
    ): ProductInventoryMovement {
        if ($quantityBefore === null) {
            $quantityBefore = $this->getStock($productId, $variantId);
        }

        if ($quantityAfter === null) {
            $quantityAfter = $quantityBefore + $quantityChange;
        }

        return ProductInventoryMovement::create([
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity_change' => $quantityChange,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $quantityAfter,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'notes' => $notes,
            'user_id' => $userId,
        ]);
    }

    /**
     * Get inventory movement history for a product
     */
    public function getMovementHistory(int $productId, ?int $variantId = null, int $limit = 50): array
    {
        $query = ProductInventoryMovement::forProduct($productId)
            ->with(['user', 'variant'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($variantId) {
            $query->forVariant($variantId);
        }

        return $query->get()->toArray();
    }

    /**
     * Get low stock alerts
     */
    public function getLowStockAlerts(int $limit = 50): array
    {
        return ProductInventoryAlert::unresolved()
            ->with(['product', 'variant'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Clean up expired reservations
     */
    public function cleanupExpiredReservations(): int
    {
        $expired = ProductStockReservation::expired()->where('is_active', true)->get();
        $count = 0;

        foreach ($expired as $reservation) {
            $reservation->release();
            $count++;
        }

        Log::info("Cleaned up {$count} expired stock reservations");

        return $count;
    }

    /**
     * Get inventory summary for a product
     */
    public function getInventorySummary(int $productId, ?int $variantId = null): array
    {
        $stock = $this->getStock($productId, $variantId);
        $reserved = $this->getReservedQuantity($productId, $variantId);
        $available = $stock === PHP_INT_MAX ? PHP_INT_MAX : max(0, $stock - $reserved);

        $threshold = $this->getLowStockThreshold($productId, $variantId);

        return [
            'product_id' => $productId,
            'variant_id' => $variantId,
            'stock_quantity' => $stock === PHP_INT_MAX ? 'unlimited' : $stock,
            'reserved_quantity' => $reserved,
            'available_quantity' => $available === PHP_INT_MAX ? 'unlimited' : $available,
            'low_stock_threshold' => $threshold,
            'is_low_stock' => $stock !== PHP_INT_MAX && $stock <= $threshold,
            'is_out_of_stock' => $stock !== PHP_INT_MAX && $stock <= 0,
        ];
    }

    /**
     * Bulk update stock quantities
     */
    public function bulkUpdateStock(array $updates, ?int $userId = null, ?string $reason = null): array
    {
        $results = [
            'success' => [],
            'failed' => [],
        ];

        foreach ($updates as $update) {
            try {
                $productId = $update['product_id'];
                $variantId = $update['variant_id'] ?? null;
                $quantity = $update['quantity'];

                $this->adjustStock($productId, $quantity, $variantId, $reason, $userId);

                $results['success'][] = [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'new_quantity' => $quantity,
                ];
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'product_id' => $productId ?? null,
                    'variant_id' => $variantId ?? null,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Transfer stock between locations (placeholder for multi-location support)
     */
    public function transferStock(
        int $productId,
        int $quantity,
        string $fromLocation,
        string $toLocation,
        ?int $variantId = null,
        ?int $userId = null
    ): bool {
        // This is a placeholder for future multi-location support
        // For now, just log the transfer
        $this->logMovement(
            $productId,
            -$quantity,
            ProductInventoryMovement::TYPE_TRANSFER_OUT,
            'location',
            null,
            $variantId,
            $userId,
            "Transferred {$quantity} units from {$fromLocation} to {$toLocation}"
        );

        $this->logMovement(
            $productId,
            $quantity,
            ProductInventoryMovement::TYPE_TRANSFER_IN,
            'location',
            null,
            $variantId,
            $userId,
            "Received {$quantity} units from {$fromLocation} to {$toLocation}"
        );

        return true;
    }
}
