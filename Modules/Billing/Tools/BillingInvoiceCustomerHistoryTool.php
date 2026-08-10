<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingInvoiceCustomerHistoryTool extends AbstractInvoiceTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_invoice_customer_history',
            'Review a customer invoice history with masked contact data, outstanding balance, and recent invoice activity.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'customer_id', type: PropertyType::INTEGER, description: 'Optional customer ID to inspect.', required: false),
            new ToolProperty(name: 'search_term', type: PropertyType::STRING, description: 'Optional customer email or name fragment.', required: false),
            new ToolProperty(name: 'include_paid', type: PropertyType::STRING, description: 'Optional yes/no flag to include fully paid invoices. Defaults to yes.', required: false),
            new ToolProperty(name: 'months_back', type: PropertyType::INTEGER, description: 'Optional number of months of invoice history to include. Default is 12.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $customerId = isset($args['customer_id']) ? (int) $args['customer_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $includePaid = $this->normalizeBooleanString($args['include_paid'] ?? 'yes', true);
        $monthsBack = max(1, min(60, (int) ($args['months_back'] ?? 12)));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view invoice customer history.');
        }

        if (($customerId === null || $customerId <= 0) && $searchTerm === '') {
            return $this->handleError('Provide either customer_id or search_term to inspect invoice history.');
        }

        try {
            $customerQuery = Customer::query();

            if ($customerId !== null && $customerId > 0) {
                $customerQuery->where('id', $customerId);
            }

            if ($searchTerm !== '') {
                $customerQuery->where(function ($builder) use ($searchTerm): void {
                    $builder->where('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                });
            }

            $customer = $customerQuery->first();

            if (! $customer instanceof Customer) {
                return $this->formatAsHtmlTable(
                    [],
                    ['customer' => 'Customer'],
                    'No customer matched the requested invoice history lookup.',
                    'billing-invoice-customer-history-empty'
                );
            }

            $invoiceQuery = Invoice::query()
                ->with('customer')
                ->where('customer_id', $customer->id)
                ->where(function ($builder) use ($monthsBack): void {
                    $builder->whereDate('invoice_date', '>=', now()->subMonths($monthsBack)->toDateString())
                        ->orWhereDate('created_at', '>=', now()->subMonths($monthsBack)->toDateString());
                });

            if (! $includePaid) {
                $invoiceQuery->where('paid_status', '!=', Invoice::STATUS_PAID);
            }

            $invoices = $invoiceQuery
                ->orderByDesc('invoice_date')
                ->orderByDesc('created_at')
                ->get();

            $lifetimeInvoices = Invoice::query()->where('customer_id', $customer->id)->get();
            $lifetimeTotal = $lifetimeInvoices->sum(fn (Invoice $invoice): int => (int) ($invoice->total ?? 0));
            $outstandingBalance = $lifetimeInvoices->sum(fn (Invoice $invoice): int => $this->invoiceIsOutstanding($invoice) ? (int) ($invoice->due_amount ?? 0) : 0);
            $overdueCount = $lifetimeInvoices->filter(fn (Invoice $invoice): bool => $invoice->isOverdue())->count();

            $summary = $this->formatAsHtmlTable(
                [[
                    'customer' => '#' . $customer->id . ' ' . $this->invoiceCustomerName($customer),
                    'email' => $this->maskEmail((string) $customer->email),
                    'invoice_count' => (string) $lifetimeInvoices->count(),
                    'lifetime_total' => $this->formatInvoiceMoney($lifetimeTotal),
                    'outstanding_balance' => $this->formatInvoiceMoney($outstandingBalance),
                    'overdue_invoices' => (string) $overdueCount,
                ]],
                [
                    'customer' => 'Customer',
                    'email' => 'Email',
                    'invoice_count' => 'Invoices',
                    'lifetime_total' => 'Lifetime total',
                    'outstanding_balance' => 'Outstanding',
                    'overdue_invoices' => 'Overdue',
                ],
                '',
                'billing-invoice-customer-history-summary'
            );

            $rows = $invoices->map(function (Invoice $invoice): array {
                return [
                    'invoice' => $this->formatInvoiceIdentifier($invoice),
                    'invoice_date' => (string) ($invoice->invoice_date?->format('M j, Y') ?: 'Not set'),
                    'due_date' => (string) ($invoice->due_date?->format('M j, Y') ?: 'Not set'),
                    'status' => $this->invoiceWorkflowLabel((string) $invoice->status),
                    'paid_status' => $this->invoicePaidLabel((string) $invoice->paid_status),
                    'total' => $this->formatInvoiceMoney($invoice->total),
                    'due_amount' => $this->formatInvoiceMoney($invoice->due_amount),
                ];
            })->all();

            return '<h4>Customer invoice history</h4>'
                . $summary
                . '<h4>Recent invoices</h4>'
                . $this->formatAsHtmlTable(
                    $rows,
                    [
                        'invoice' => 'Invoice',
                        'invoice_date' => 'Invoice date',
                        'due_date' => 'Due date',
                        'status' => 'Status',
                        'paid_status' => 'Paid status',
                        'total' => 'Total',
                        'due_amount' => 'Due amount',
                    ],
                    'This customer has no invoices in the requested lookback window.',
                    'billing-invoice-customer-history-results'
                );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading customer invoice history: ' . $exception->getMessage());
        }
    }
}
