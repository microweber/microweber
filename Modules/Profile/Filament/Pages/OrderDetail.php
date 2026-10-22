<?php

namespace Modules\Profile\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Services\InvoiceService;
use Modules\Order\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Profile\Filament\Concerns\InteractsWithCurrentCustomer;

/**
 * Full-page order detail (replaces the old overflowing modal). Shows the
 * order, customer, shipping, items and totals, plus any related invoices and
 * payments. Scoped to the signed-in buyer — a user can only open their own
 * orders (404 otherwise). Reached from Order History's "View Details".
 */
class OrderDetail extends Page
{
    use InteractsWithCurrentCustomer;

    protected string $view = 'modules.profile::pages.order-detail';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $slug = 'order';

    public ?Order $order = null;

    public function mount(): void
    {
        $id = (int) request()->query('order');

        $order = $id
            ? Order::with(['cart.products', 'customer'])->find($id)
            : null;

        if (! $order || (int) $order->customer_id !== (int) $this->currentCustomerId()) {
            abort(404);
        }

        $this->order = $order;
    }

    public function getTitle(): string
    {
        return __('Order') . ' #' . ($this->order?->order_reference_id ?: $this->order?->id);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // reached from Order History, not a sidebar item
    }

    /** Related invoices for this order (by the order's invoice_id). */
    public function relatedInvoices()
    {
        if (! $this->order) {
            return collect();
        }
        return Invoice::query()
            ->when($this->order->invoice_id, fn ($q) => $q->where('id', $this->order->invoice_id), fn ($q) => $q->whereRaw('1 = 0'))
            ->get();
    }

    /** Related payments for this order. */
    public function relatedPayments()
    {
        if (! $this->order) {
            return collect();
        }
        return Payment::query()
            ->where('rel_type', Order::class)
            ->where('rel_id', $this->order->id)
            ->orderByDesc('created_at')
            ->get();
    }

    protected function getViewData(): array
    {
        return [
            'order' => $this->order,
            'invoices' => $this->relatedInvoices(),
            'payments' => $this->relatedPayments(),
        ];
    }

    /** Download one of this order's invoices (ownership-guarded). */
    public function downloadInvoice(int $invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if (! $invoice || (int) $invoice->customer_id !== (int) $this->currentCustomerId()) {
            abort(403);
        }

        $response = app(InvoiceService::class)->downloadPdf($invoice->id);
        if ($response === false) {
            Notification::make()->title(__('Could not generate the PDF'))->danger()->send();
            return;
        }
        return $response;
    }
}
