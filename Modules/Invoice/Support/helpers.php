<?php

use Modules\Invoice\Models\Invoice;

if (!function_exists('invoice_get_by_id')) {
    function invoice_get_by_id(int $invoice_id): ?array
    {
        $invoice = app()->invoice_service->getInvoiceById($invoice_id);
        return $invoice ? $invoice->toArray() : null;
    }
}

if (!function_exists('invoice_get_all')) {
    function invoice_get_all(): array
    {
        return app()->invoice_service->getAllInvoices();
    }
}

if (!function_exists('invoice_get_by_customer_id')) {
    function invoice_get_by_customer_id(int $customer_id): array
    {
        return app()->invoice_service->getInvoicesByCustomerId($customer_id);
    }
}

if (!function_exists('invoice_save')) {
    function invoice_save(array $data): array
    {
        return app()->invoice_service->saveInvoice($data);
    }
}

if (!function_exists('invoice_delete')) {
    function invoice_delete(int $invoice_id): array
    {
        return app()->invoice_service->deleteInvoice($invoice_id);
    }
}

if (!function_exists('invoice_generate')) {
    function invoice_generate(array $params = []): array
    {
        return app()->invoice_service->generateInvoice($params);
    }
}

if (!function_exists('invoice_update_status')) {
    function invoice_update_status(int $invoice_id, string $status): array
    {
        return app()->invoice_service->updateInvoiceStatus($invoice_id, $status);
    }
}

if (!function_exists('invoice_update_paid_status')) {
    function invoice_update_paid_status(int $invoice_id, string $paid_status): array
    {
        return app()->invoice_service->updateInvoicePaidStatus($invoice_id, $paid_status);
    }
}

if (!function_exists('invoice_get_statuses')) {
    function invoice_get_statuses(): array
    {
        return [
            Invoice::STATUS_DRAFT,
            Invoice::STATUS_SENT,
            Invoice::STATUS_VIEWED,
            Invoice::STATUS_OVERDUE,
            Invoice::STATUS_PAID,
            Invoice::STATUS_COMPLETED,
            Invoice::STATUS_VOID
        ];
    }
}

if (!function_exists('invoice_get_paid_statuses')) {
    function invoice_get_paid_statuses(): array
    {
        return [
            Invoice::STATUS_UNPAID,
            Invoice::STATUS_PARTIALLY_PAID,
            Invoice::STATUS_PAID,
            Invoice::STATUS_REFUNDED
        ];
    }
}
