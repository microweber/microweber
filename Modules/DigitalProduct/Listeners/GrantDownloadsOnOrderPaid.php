<?php

namespace Modules\DigitalProduct\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\DigitalProduct\Services\DigitalProductService;
use Modules\Order\Events\OrderWasPaid;

/**
 * When an order is paid, grant the buyer secure download rights for every
 * downloadable file in the order. Mirrors Product\UpdateInventoryOnOrderPaid.
 */
class GrantDownloadsOnOrderPaid
{
    protected DigitalProductService $service;

    public function __construct(DigitalProductService $service)
    {
        $this->service = $service;
    }

    public function handle(OrderWasPaid $event): void
    {
        $order = $event->order ?? null;

        if (!$order) {
            return;
        }

        try {
            $this->service->grantForOrder($order);
        } catch (\Throwable $e) {
            Log::error('DigitalProduct: failed to grant downloads on order paid', [
                'order_id' => $order->id ?? null,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
