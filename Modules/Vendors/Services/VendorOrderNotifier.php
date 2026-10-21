<?php

namespace Modules\Vendors\Services;

use Illuminate\Support\Facades\Log;
use Modules\Product\Models\Product;
use Modules\Vendors\Models\Vendor;
use Modules\Vendors\Models\VendorOrderNotificationLog;
use Modules\Vendors\Notifications\VendorOrderNotification;

/**
 * Turns a paid order into per-vendor notifications.
 *
 * Reads the order's completed cart line items, maps each one to its product
 * owner (content.created_by → Vendor), groups the items per vendor, and
 * notifies each opted-in vendor once about their slice of the order.
 */
class VendorOrderNotifier
{
    /**
     * Notify every vendor who owns a product in this order (once).
     *
     * @return int number of vendors notified
     */
    public function notifyForOrder($order): int
    {
        if (! $order || empty($order->id)) {
            return 0;
        }

        try {
            $items = $order->cart()->where('order_completed', 1)->get();
        } catch (\Throwable $e) {
            Log::warning('Vendors: could not read order cart for notifications', [
                'order_id' => $order->id,
                'exception' => $e->getMessage(),
            ]);
            return 0;
        }

        // Group line items by the owning vendor's user id.
        $byOwner = [];
        foreach ($items as $item) {
            $productId = $item->rel_id ?? null;
            if (! $productId) {
                continue;
            }

            $ownerId = $this->resolveOwnerId($productId);
            if (! $ownerId) {
                continue;
            }

            $byOwner[$ownerId][] = [
                'title' => $item->title ?? (Product::find($productId)?->title ?? __('Product')),
                'qty' => (float) ($item->qty ?? 1),
                'price' => (float) ($item->price ?? 0),
            ];
        }

        if (empty($byOwner)) {
            return 0;
        }

        $notified = 0;
        foreach ($byOwner as $ownerId => $vendorItems) {
            $vendor = Vendor::where('user_id', $ownerId)->first();
            if (! $vendor || ! $vendor->is_approved || ! $vendor->wantsOrderNotifications()) {
                continue;
            }

            // Idempotency: only the first (vendor, order) wins. If the row
            // already exists this order was already notified — skip.
            $ledger = VendorOrderNotificationLog::firstOrCreate([
                'vendor_id' => $vendor->id,
                'order_id' => $order->id,
            ]);
            if (! $ledger->wasRecentlyCreated) {
                continue;
            }

            $user = $vendor->user;
            if (! $user) {
                continue;
            }

            try {
                $user->notify(new VendorOrderNotification(
                    $order,
                    $vendorItems,
                    $vendor->orderNotificationChannels()
                ));
                $notified++;
            } catch (\Throwable $e) {
                Log::error('Vendors: failed to notify vendor of order', [
                    'order_id' => $order->id,
                    'vendor_id' => $vendor->id,
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        return $notified;
    }

    /** Resolve a product id to the user id that owns it (created_by). */
    protected function resolveOwnerId($productId): ?int
    {
        $createdBy = Product::withoutGlobalScopes()
            ->whereKey($productId)
            ->value('created_by');

        return $createdBy ? (int) $createdBy : null;
    }
}
