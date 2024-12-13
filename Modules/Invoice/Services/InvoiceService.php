<?php

namespace Modules\Invoice\Services;

use Modules\Invoice\Models\Invoice;

class InvoiceService
{
    public function generateInvoice(array $params = []): array
    {
        try {
            // Validate required parameters
            if (!isset($params['customer_id'])) {
                return [
                    'error' => true,
                    'message' => 'Customer ID is required'
                ];
            }

            // Generate invoice number
            $prefix = $params['prefix'] ?? '';
            $invoice_number = $prefix . '-' . Invoice::getNextInvoiceNumber($prefix);

            // Create new invoice
            $invoice = new Invoice();
            $invoice->invoice_number = $invoice_number;
            $invoice->reference_number = $params['reference_number'] ?? null;
            $invoice->customer_id = $params['customer_id'];
            $invoice->company_id = $params['company_id'] ?? null;
            $invoice->invoice_template_id = $params['invoice_template_id'] ?? null;
            $invoice->status = $params['status'] ?? Invoice::STATUS_DRAFT;
            $invoice->paid_status = $params['paid_status'] ?? Invoice::STATUS_UNPAID;
            $invoice->invoice_date = $params['invoice_date'] ?? now();
            $invoice->due_date = $params['due_date'] ?? null;
            $invoice->sub_total = $params['sub_total'] ?? 0;
            $invoice->discount = $params['discount'] ?? null;
            $invoice->discount_type = $params['discount_type'] ?? null;
            $invoice->discount_val = $params['discount_val'] ?? 0;
            $invoice->total = $params['total'] ?? 0;
            $invoice->due_amount = $params['due_amount'] ?? 0;
            $invoice->tax_per_item = $params['tax_per_item'] ?? false;
            $invoice->discount_per_item = $params['discount_per_item'] ?? false;
            $invoice->tax = $params['tax'] ?? null;
            $invoice->notes = $params['notes'] ?? null;
            $invoice->unique_hash = $params['unique_hash'] ?? md5(uniqid());
            $invoice->save();

            return [
                'success' => true,
                'invoice_id' => $invoice->id,
                'message' => 'Invoice generated successfully'
            ];

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getInvoiceById(int $invoice_id): ?Invoice
    {
        return Invoice::with(['items', 'customer'])->find($invoice_id);
    }

    public function getAllInvoices(): array
    {
        return Invoice::with(['items', 'customer'])->get()->toArray();
    }

    public function getInvoicesByCustomerId(int $customer_id): array
    {
        return Invoice::with(['items'])
            ->where('customer_id', $customer_id)
            ->get()
            ->toArray();
    }

    public function saveInvoice(array $data): array
    {
        try {
            $invoice = Invoice::updateOrCreate(
                ['id' => $data['id'] ?? null],
                $data
            );

            return [
                'success' => true,
                'invoice_id' => $invoice->id,
                'success_edit' => true
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteInvoice(int $invoice_id): array
    {
        try {
            $invoice = Invoice::find($invoice_id);
            
            if (!$invoice) {
                return [
                    'status' => 'failed',
                    'message' => 'Invoice not found'
                ];
            }

            $invoice->delete();
            
            return [
                'status' => 'success',
                'message' => 'Invoice deleted successfully'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateInvoiceStatus(int $invoice_id, string $status): array
    {
        try {
            $invoice = Invoice::find($invoice_id);
            
            if (!$invoice) {
                return [
                    'error' => true,
                    'message' => 'Invoice not found'
                ];
            }

            if (!in_array($status, [
                Invoice::STATUS_DRAFT,
                Invoice::STATUS_SENT,
                Invoice::STATUS_VIEWED,
                Invoice::STATUS_OVERDUE,
                Invoice::STATUS_PAID,
                Invoice::STATUS_COMPLETED,
                Invoice::STATUS_VOID
            ])) {
                return [
                    'error' => true,
                    'message' => 'Invalid status'
                ];
            }

            $invoice->status = $status;
            $invoice->save();

            return [
                'success' => true,
                'message' => 'Invoice status updated successfully'
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateInvoicePaidStatus(int $invoice_id, string $paid_status): array
    {
        try {
            $invoice = Invoice::find($invoice_id);
            
            if (!$invoice) {
                return [
                    'error' => true,
                    'message' => 'Invoice not found'
                ];
            }

            if (!in_array($paid_status, [
                Invoice::STATUS_UNPAID,
                Invoice::STATUS_PARTIALLY_PAID,
                Invoice::STATUS_PAID,
                Invoice::STATUS_REFUNDED
            ])) {
                return [
                    'error' => true,
                    'message' => 'Invalid paid status'
                ];
            }

            $invoice->paid_status = $paid_status;
            $invoice->save();

            return [
                'success' => true,
                'message' => 'Invoice paid status updated successfully'
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}
