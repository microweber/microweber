<?php

namespace Modules\DigitalProduct\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\DigitalProduct\Services\DigitalProductService;

/**
 * Grant downloads when an order is updated and is marked paid.
 *
 * The checkout/payment flows dispatch OrderWasPaid, but marking an order paid
 * MANUALLY in the admin (the is_paid toggle on the order form) only triggers
 * the model's generic OrderWasUpdated event. This listener covers that path so
 * admin-confirmed payments grant downloads too. grantForOrder() uses
 * firstOrCreate, so running on every update of an already-paid order never
 * duplicates grants.
 */
class GrantDownloadsOnOrderUpdated
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

        if (!$order || empty($order->is_paid)) {
            return;
        }

        try {
            $this->service->grantForOrder($order);
        } catch (\Throwable $e) {
            Log::error('DigitalProduct: failed to grant downloads on order updated', [
                'order_id' => $order->id ?? null,
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
