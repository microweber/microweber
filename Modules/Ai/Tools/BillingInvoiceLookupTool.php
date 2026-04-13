<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Invoice\Models\Invoice;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingInvoiceLookupTool extends AbstractInvoiceTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_invoice_lookup',
            'Search invoices by invoice number, reference, customer, workflow status, payment status, and date range.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'invoice_id', type: PropertyType::INTEGER, description: 'Optional invoice ID to fetch directly.', required: false),
            new ToolProperty(name: 'search_term', type: PropertyType::STRING, description: 'Optional invoice number, reference number, customer name, or customer email fragment.', required: false),
            new ToolProperty(name: 'status', type: PropertyType::STRING, description: 'Optional invoice workflow status filter.', required: false),
            new ToolProperty(name: 'paid_status', type: PropertyType::STRING, description: 'Optional invoice payment status filter.', required: false),
            new ToolProperty(name: 'date_from', type: PropertyType::STRING, description: 'Optional invoice date lower bound in YYYY-MM-DD format.', required: false),
            new ToolProperty(name: 'date_to', type: PropertyType::STRING, description: 'Optional invoice date upper bound in YYYY-MM-DD format.', required: false),
            new ToolProperty(name: 'overdue_only', type: PropertyType::STRING, description: 'Optional yes/no flag to only include overdue invoices.', required: false),
            new ToolProperty(name: 'customer_id', type: PropertyType::INTEGER, description: 'Optional customer ID filter.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum invoices to return (1-50). Default is 10.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $invoiceId = isset($args['invoice_id']) ? (int) $args['invoice_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $status = $this->normalizeInvoiceStatus($args['status'] ?? '');
        $paidStatus = $this->normalizeInvoicePaidStatus($args['paid_status'] ?? '');
        $dateFrom = $this->normalizeDate($args['date_from'] ?? null);
        $dateTo = $this->normalizeDate($args['date_to'] ?? null);
        $overdueOnly = $this->normalizeBooleanString($args['overdue_only'] ?? false);
        $customerId = isset($args['customer_id']) ? (int) $args['customer_id'] : null;
        $limit = $this->safeLimit($args['limit'] ?? 10, 10, 50);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view invoices.');
        }

        try {
            $query = Invoice::query()->with('customer');

            if ($invoiceId !== null && $invoiceId > 0) {
                $query->where('id', $invoiceId);
            }

            if ($customerId !== null && $customerId > 0) {
                $query->where('customer_id', $customerId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('invoice_number', 'like', '%' . $searchTerm . '%')
                        ->orWhere('reference_number', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('customer', function ($customerQuery) use ($searchTerm): void {
                            $customerQuery->where('email', 'like', '%' . $searchTerm . '%')
                                ->orWhere('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                        });
                });
            }

            if ($status !== '') {
                $query->where('status', $status);
            }

            if ($paidStatus !== '') {
                $query->where('paid_status', $paidStatus);
            }

            if ($dateFrom) {
                $query->whereDate('invoice_date', '>=', $dateFrom->toDateString());
            }

            if ($dateTo) {
                $query->whereDate('invoice_date', '<=', $dateTo->toDateString());
            }

            if ($overdueOnly) {
                $query->where('due_date', '<', now()->toDateString())
                    ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_COMPLETED, Invoice::STATUS_VOID]);
            }

            $invoices = $query
                ->orderByDesc('invoice_date')
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();

            $summaryBits = [];
            if ($searchTerm !== '') {
                $summaryBits[] = 'Search: "' . e($searchTerm) . '"';
            }
            if ($status !== '') {
                $summaryBits[] = 'Status: ' . $this->invoiceWorkflowLabel($status);
            }
            if ($paidStatus !== '') {
                $summaryBits[] = 'Paid status: ' . $this->invoicePaidLabel($paidStatus);
            }
            if ($overdueOnly) {
                $summaryBits[] = 'Overdue only';
            }

            $header = '<h4>Invoice lookup</h4><p>'
                . ($summaryBits !== [] ? implode(' | ', $summaryBits) . ' | ' : '')
                . '<strong>Found:</strong> ' . $invoices->count() . ' invoice(s)</p>';

            $rows = $invoices->map(function (Invoice $invoice): array {
                return [
                    'invoice' => $this->formatInvoiceIdentifier($invoice),
                    'customer' => $this->invoiceCustomerSummary($invoice->customer),
                    'invoice_date' => (string) ($invoice->invoice_date?->format('M j, Y') ?: 'Not set'),
                    'due_date' => (string) ($invoice->due_date?->format('M j, Y') ?: 'Not set'),
                    'status' => $this->invoiceWorkflowLabel((string) $invoice->status),
                    'paid_status' => $this->invoicePaidLabel((string) $invoice->paid_status),
                    'total' => $this->formatInvoiceMoney($invoice->total),
                    'due_amount' => $this->formatInvoiceMoney($invoice->due_amount),
                ];
            })->all();

            return $header . $this->formatAsHtmlTable(
                $rows,
                [
                    'invoice' => 'Invoice',
                    'customer' => 'Customer',
                    'invoice_date' => 'Invoice date',
                    'due_date' => 'Due date',
                    'status' => 'Status',
                    'paid_status' => 'Paid status',
                    'total' => 'Total',
                    'due_amount' => 'Due',
                ],
                'No invoices matched the requested filters.',
                'billing-invoice-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading invoice lookup: ' . $exception->getMessage());
        }
    }
}
