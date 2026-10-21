<?php

namespace Modules\Vendors\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Vendors\Services\VendorOrderNotifier;

/**
 * Marking an order paid manually in the admin (the is_paid toggle) fires the
 * generic OrderWasUpdated rather than OrderWasPaid. Cover that path too; the
 * notifier's (vendor, order) ledger makes repeat updates a no-op.
 */
class NotifyVendorsOnOrderUpdated
{
    protected VendorOrderNotifier $notifier;

    public function __construct(VendorOrderNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle($event): void
    {
        $order = null;
        if (is_object($event)) {
            if (method_exists($event, 'getModel')) {
                $order = $event->getModel();
            } elseif (isset($event->order)) {
                $order = $event->order;
            }
        }

        if (! $order || empty($order->is_paid)) {
            return;
        }

        try {
            $this->notifier->notifyForOrder($order);
        } catch (\Throwable $e) {
            Log::error('Vendors: failed to notify vendors on order updated', [
                'order_id' => $order->id ?? null,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
