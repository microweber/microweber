<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;

abstract class AbstractInvoiceTool extends AbstractBillingTool
{
    protected function invoiceStatusOptions(): array
    {
        return [
            Invoice::STATUS_DRAFT => 'Draft',
            Invoice::STATUS_SENT => 'Sent',
            Invoice::STATUS_VIEWED => 'Viewed',
            Invoice::STATUS_OVERDUE => 'Overdue',
            Invoice::STATUS_PAID => 'Paid',
            Invoice::STATUS_COMPLETED => 'Completed',
            Invoice::STATUS_VOID => 'Void',
        ];
    }

    protected function invoicePaidStatusOptions(): array
    {
        return [
            Invoice::STATUS_UNPAID => 'Unpaid',
            Invoice::STATUS_PARTIALLY_PAID => 'Partially paid',
            Invoice::STATUS_PAID => 'Paid',
            Invoice::STATUS_REFUNDED => 'Refunded',
        ];
    }

    protected function normalizeInvoiceStatus(mixed $value): string
    {
        $status = strtolower(trim((string) $value));

        return array_key_exists($status, $this->invoiceStatusOptions()) ? $status : '';
    }

    protected function normalizeInvoicePaidStatus(mixed $value): string
    {
        $status = strtolower(trim((string) $value));

        return array_key_exists($status, $this->invoicePaidStatusOptions()) ? $status : '';
    }

    protected function normalizeDate(mixed $value): ?Carbon
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }

    protected function formatInvoiceMoney(mixed $amount, string $currency = 'USD'): string
    {
        $numeric = is_numeric($amount) ? ((float) $amount / 100) : 0.0;

        return number_format($numeric, 2) . ' ' . strtoupper($currency ?: 'USD');
    }

    protected function invoiceCustomerName(?Customer $customer): string
    {
        if (! $customer instanceof Customer) {
            return 'Unknown customer';
        }

        return $this->customerDisplayName($customer);
    }

    protected function invoiceCustomerSummary(?Customer $customer): string
    {
        if (! $customer instanceof Customer) {
            return 'Unknown customer';
        }

        return $this->invoiceCustomerName($customer)
            . '<br><small class="text-muted">' . e($this->maskEmail((string) $customer->email)) . '</small>';
    }

    protected function formatInvoiceIdentifier(Invoice $invoice): string
    {
        $identifier = (string) ($invoice->invoice_number ?: ('Invoice #' . $invoice->id));

        if ((string) $invoice->reference_number !== '') {
            $identifier .= '<br><small class="text-muted">' . e((string) $invoice->reference_number) . '</small>';
        }

        return $identifier;
    }

    protected function invoiceWorkflowLabel(?string $status): string
    {
        return $this->invoiceStatusOptions()[$status ?? ''] ?? Str::headline((string) $status);
    }

    protected function invoicePaidLabel(?string $status): string
    {
        return $this->invoicePaidStatusOptions()[$status ?? ''] ?? Str::headline((string) $status);
    }

    protected function daysOverdue(Invoice $invoice): int
    {
        if (! $invoice->due_date) {
            return 0;
        }

        $days = (int) $invoice->due_date->startOfDay()->diffInDays(now()->startOfDay(), false);

        return max(0, $days);
    }

    protected function invoiceIsOutstanding(Invoice $invoice): bool
    {
        return in_array((string) $invoice->paid_status, [
            Invoice::STATUS_UNPAID,
            Invoice::STATUS_PARTIALLY_PAID,
        ], true) && (int) ($invoice->due_amount ?? 0) > 0;
    }
}
