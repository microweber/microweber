<?php

namespace Modules\Invoice\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Services\InvoiceService;
use Modules\Order\Events\OrderWasPaid;

/**
 * When an order is paid, reflect that on its invoice: mark the invoice paid,
 * generating one first if the order has none yet and auto-generation is enabled.
 *
 * Covers BOTH payment paths:
 *  - OrderWasPaid (checkout / payment-provider flow), and
 *  - OrderWasUpdated when is_paid is set (the admin "mark paid" toggle, which
 *    does not dispatch OrderWasPaid).
 *
 * Idempotent: generateFromOrder dedupes by order reference and re-marking an
 * already-paid invoice is a no-op, so running on every order update is safe.
 */
class MarkInvoicePaidOnOrderPaid
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function handle($event): void
    {
        $order = $this->resolveOrder($event);
        if (! $order || empty($order->id)) {
            return;
        }

        // OrderWasPaid always means paid; OrderWasUpdated only when is_paid set.
        $isPaid = ($event instanceof OrderWasPaid) || ! empty($order->is_paid);
        if (! $isPaid) {
            return;
        }

        try {
            $invoiceId = $order->invoice_id ?? null;

            if (! $invoiceId) {
                // No invoice yet: create one only if the shop opted into
                // auto-generation (respects the invoice settings).
                if (! $this->invoiceService->autoGenerateEnabled()) {
                    return;
                }

                $result = $this->invoiceService->generateFromOrder($order->id, [
                    'status' => Invoice::STATUS_SENT,
                ]);
                $invoiceId = $result['invoice_id'] ?? null;

                if ($invoiceId && empty($order->invoice_id)) {
                    $order->invoice_id = $invoiceId;
                    $order->save();
                }
            }

            if (! $invoiceId) {
                return;
            }

            $this->invoiceService->updateInvoicePaidStatus($invoiceId, Invoice::STATUS_PAID);
            $this->invoiceService->updateInvoiceStatus($invoiceId, Invoice::STATUS_PAID);
        } catch (\Throwable $e) {
            Log::error('Invoice mark-paid on order paid failed', [
                'order_id' => $order->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function resolveOrder($event)
    {
        if (! is_object($event)) {
            return null;
        }
        if (method_exists($event, 'getModel')) {
            return $event->getModel();
        }
        if (isset($event->order)) {
            return $event->order;
        }

        return null;
    }
}
