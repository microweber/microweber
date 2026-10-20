<?php

namespace Modules\DigitalProduct\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\DigitalProduct\Services\DigitalProductService;

/**
 * When an order is created, immediately grant downloads for any FREE digital
 * line items (price <= 0) so free products (lead magnets, free ebooks) don't
 * require a payment step. Paid items are granted on OrderWasPaid instead.
 */
class GrantFreeDownloadsOnOrderCreated
{
    protected DigitalProductService $service;

    public function __construct(DigitalProductService $service)
    {
        $this->service = $service;
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

        if (!$order) {
            return;
        }

        try {
            $this->service->grantForOrder($order, true);
            $this->service->notifyForOrder($order);
        } catch (\Throwable $e) {
            Log::error('DigitalProduct: failed to grant free downloads on order created', [
                'order_id' => $order->id ?? null,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
