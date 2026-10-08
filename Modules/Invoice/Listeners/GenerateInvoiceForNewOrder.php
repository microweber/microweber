<?php

namespace Modules\Invoice\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Services\InvoiceService;
use Modules\Order\Events\OrderWasCreated;

/**
 * Auto-generate a draft invoice when a new order is placed, if the shop
 * invoice settings have "Auto-generate invoices for new orders" enabled.
 *
 * Idempotent (InvoiceService::generateFromOrder dedupes by order reference)
 * and fail-safe: any error is logged and never bubbles up to break the
 * order-placement flow.
 */
class GenerateInvoiceForNewOrder
{
    public function __construct(private InvoiceService $invoiceService)
    {
    }

    public function handle(OrderWasCreated $event): void
    {
        try {
            $order = $event->getModel();

            if (! $order || empty($order->id)) {
                return;
            }

            if (! $this->invoiceService->autoGenerateEnabled()) {
                return;
            }

            // Already invoiced — nothing to do.
            if (! empty($order->invoice_id)) {
                return;
            }

            $result = $this->invoiceService->generateFromOrder($order->id, [
                'status' => Invoice::STATUS_DRAFT,
                'due_date' => now()->addDays(14),
            ]);

            if (empty($result['success'])) {
                Log::warning('Auto invoice generation did not succeed', [
                    'order_id' => $order->id,
                    'result' => $result,
                ]);

                return;
            }

            // generateFromOrder links the invoice to the order, but guard in
            // case the event carried a stale model instance.
            if (empty($order->invoice_id) && ! empty($result['invoice_id'])) {
                $order->invoice_id = $result['invoice_id'];
                $order->save();
            }

            Log::info('Auto-generated invoice for new order', [
                'order_id' => $order->id,
                'invoice_id' => $result['invoice_id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Auto invoice generation failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
