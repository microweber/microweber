<?php

namespace Modules\Booking\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Booking\Services\BookingService;

/**
 * When an order is paid, confirm its bookable line items into Booking rows that
 * consume availability. Handles both the checkout OrderWasPaid event and the
 * admin manual "mark paid" path (OrderWasUpdated with is_paid), mirroring how
 * DigitalProduct grants downloads. confirmForOrder() is idempotent.
 */
class ConfirmBookingsOnOrderPaid
{
    public function __construct(protected BookingService $service)
    {
    }

    public function handle($event): void
    {
        $order = $this->resolveOrder($event);
        if (!$order) {
            return;
        }

        // OrderWasUpdated fires on every update; only act once the order is paid.
        if (!isset($event->order) && empty($order->is_paid)) {
            return;
        }

        try {
            $this->service->confirmForOrder($order);
        } catch (\Throwable $e) {
            Log::error('Booking: failed to confirm bookings on order paid', [
                'order_id' => $order->id ?? null,
                'exception' => $e->getMessage(),
            ]);
        }
    }

    private function resolveOrder($event)
    {
        if (is_object($event)) {
            if (isset($event->order)) {
                return $event->order; // OrderWasPaid
            }
            if (method_exists($event, 'getModel')) {
                return $event->getModel(); // OrderWasUpdated
            }
        }

        return null;
    }
}
