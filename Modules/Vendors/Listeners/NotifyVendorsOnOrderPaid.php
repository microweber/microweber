<?php

namespace Modules\Vendors\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Vendors\Services\VendorOrderNotifier;

/**
 * When an order is paid, notify each vendor whose products it contains.
 */
class NotifyVendorsOnOrderPaid
{
    protected VendorOrderNotifier $notifier;

    public function __construct(VendorOrderNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function handle($event): void
    {
        $order = $event->order ?? null;
        if (! $order) {
            return;
        }

        try {
            $this->notifier->notifyForOrder($order);
        } catch (\Throwable $e) {
            Log::error('Vendors: failed to notify vendors on order paid', [
                'order_id' => $order->id ?? null,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
