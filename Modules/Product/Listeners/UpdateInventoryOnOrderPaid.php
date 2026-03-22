<?php

namespace Modules\Product\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Order\Events\OrderWasPaid;
use Modules\Product\Services\InventoryService;

/**
 * Update Inventory On Order Paid Listener
 *
 * Deducts stock when an order is marked as paid.
 */
class UpdateInventoryOnOrderPaid
{
    /**
     * @var InventoryService
     */
    protected $inventoryService;

    /**
     * Create the event listener.
     */
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderWasPaid $event): void
    {
        $order = $event->order;

        if (!$order) {
            Log::warning('UpdateInventoryOnOrderPaid: No order found in event');
            return;
        }

        try {
            // Get cart items for this order
            $cartItems = $order->cart()->where('order_completed', 1)->get();

            foreach ($cartItems as $item) {
                $this->processCartItem($item, $order);
            }

            Log::info('Inventory updated for order', [
                'order_id' => $order->id,
                'items_count' => $cartItems->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update inventory on order paid', [
                'order_id' => $order->id ?? null,
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Process a single cart item.
     */
    protected function processCartItem($item, $order): void
    {
        $productId = $item->rel_id ?? null;
        $quantity = $item->qty ?? 0;

        if (!$productId || $quantity <= 0) {
            return;
        }

        // Check if this is a variant product
        $variantId = null;
        if (isset($item->custom_fields_data) && !empty($item->custom_fields_data)) {
            $customFields = is_string($item->custom_fields_data)
                ? json_decode($item->custom_fields_data, true)
                : $item->custom_fields_data;

            // Try to find variant from custom fields
            $variantId = $this->findVariantFromCustomFields($productId, $customFields);
        }

        // Deduct the stock
        $this->inventoryService->deductStock(
            $productId,
            $quantity,
            $variantId,
            "Order #{$order->id} - Payment confirmed",
            null,
            'order',
            $order->id
        );

        // Release any stock reservations for this item
        $this->releaseCartReservations($productId, $variantId, $item->session_id);
    }

    /**
     * Find variant ID from custom fields.
     */
    protected function findVariantFromCustomFields(int $productId, ?array $customFields): ?int
    {
        if (empty($customFields)) {
            return null;
        }

        // Extract variant attributes from custom fields
        $attributes = [];
        foreach ($customFields as $field) {
            if (isset($field['name']) && isset($field['value'])) {
                $attributes[$field['name']] = $field['value'];
            }
        }

        if (empty($attributes)) {
            return null;
        }

        // Find variant by attributes
        $variantService = app(\Modules\Product\Services\ProductVariantService::class);
        $variant = $variantService->findCombinationByAttributeKeys($productId, $attributes);

        return $variant?->id;
    }

    /**
     * Release cart stock reservations.
     */
    protected function releaseCartReservations(int $productId, ?int $variantId, string $sessionId): void
    {
        try {
            $reservations = \Modules\Product\Models\ProductStockReservation::where('product_id', $productId)
                ->where('session_id', $sessionId)
                ->where('reservation_type', 'cart')
                ->where('is_active', true)
                ->get();

            foreach ($reservations as $reservation) {
                // Only release if variant matches (or if no variant specified)
                if ($variantId === null || $reservation->variant_id === $variantId) {
                    $this->inventoryService->releaseReservation($reservation->id, 'Order completed');
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to release cart reservations', [
                'product_id' => $productId,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
