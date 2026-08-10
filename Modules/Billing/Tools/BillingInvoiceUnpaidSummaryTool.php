<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Illuminate\Support\Collection;
use Modules\Invoice\Models\Invoice;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingInvoiceUnpaidSummaryTool extends AbstractInvoiceTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_invoice_unpaid_summary',
            'Summarize unpaid and overdue invoices with outstanding balance, aging, and priority collections rows.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'overdue_only', type: PropertyType::STRING, description: 'Optional yes/no flag to only return overdue invoices. Defaults to yes.', required: false),
            new ToolProperty(name: 'days_past_due', type: PropertyType::INTEGER, description: 'Optional minimum number of days overdue.', required: false),
            new ToolProperty(name: 'customer_id', type: PropertyType::INTEGER, description: 'Optional customer ID filter.', required: false),
            new ToolProperty(name: 'sort_by', type: PropertyType::STRING, description: 'Optional sort field: due_date, amount, or days_overdue.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum invoices to include (1-100). Default is 20.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $overdueOnly = $this->normalizeBooleanString($args['overdue_only'] ?? 'yes', true);
        $daysPastDue = max(0, (int) ($args['days_past_due'] ?? 0));
        $customerId = isset($args['customer_id']) ? (int) $args['customer_id'] : null;
        $sortBy = strtolower(trim((string) ($args['sort_by'] ?? 'due_date')));
        $limit = $this->safeLimit($args['limit'] ?? 20, 20, 100);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view unpaid invoice summaries.');
        }

        try {
            $query = Invoice::query()
                ->with('customer')
                ->whereIn('paid_status', [Invoice::STATUS_UNPAID, Invoice::STATUS_PARTIALLY_PAID])
                ->where('due_amount', '>', 0);

            if ($customerId !== null && $customerId > 0) {
                $query->where('customer_id', $customerId);
            }

            if ($overdueOnly) {
                $query->where('due_date', '<', now()->toDateString())
                    ->whereNotIn('status', [Invoice::STATUS_PAID, Invoice::STATUS_COMPLETED, Invoice::STATUS_VOID]);
            }

            /** @var Collection<int, Invoice> $invoices */
            $invoices = $query->get()->filter(function (Invoice $invoice) use ($daysPastDue): bool {
                return $this->daysOverdue($invoice) >= $daysPastDue;
            })->values();

            $sorted = match ($sortBy) {
                'amount' => $invoices->sortByDesc(fn (Invoice $invoice): int => (int) ($invoice->due_amount ?? 0)),
                'days_overdue' => $invoices->sortByDesc(fn (Invoice $invoice): int => $this->daysOverdue($invoice)),
                default => $invoices->sortBy(fn (Invoice $invoice): string => (string) ($invoice->due_date?->toDateString() ?: '9999-12-31')),
            };
            $sorted = $sorted->take($limit)->values();

            $totalOutstanding = $invoices->sum(fn (Invoice $invoice): int => (int) ($invoice->due_amount ?? 0));
            $totalOverdue = $invoices->filter(fn (Invoice $invoice): bool => $invoice->isOverdue())->sum(fn (Invoice $invoice): int => (int) ($invoice->due_amount ?? 0));
            $oldestDueDate = $invoices->filter(fn (Invoice $invoice): bool => $invoice->due_date !== null)->min(fn (Invoice $invoice): string => (string) $invoice->due_date?->toDateString());

            $summary = $this->formatAsHtmlTable(
                [[
                    'open_invoices' => (string) $invoices->count(),
                    'outstanding_balance' => $this->formatInvoiceMoney($totalOutstanding),
                    'overdue_balance' => $this->formatInvoiceMoney($totalOverdue),
                    'oldest_due_date' => $oldestDueDate ? \Illuminate\Support\Carbon::parse($oldestDueDate)->format('M j, Y') : 'None',
                ]],
                [
                    'open_invoices' => 'Open invoices',
                    'outstanding_balance' => 'Outstanding balance',
                    'overdue_balance' => 'Overdue balance',
                    'oldest_due_date' => 'Oldest due date',
                ],
                '',
                'billing-invoice-unpaid-summary'
            );

            $rows = $sorted->map(function (Invoice $invoice): array {
                return [
                    'invoice' => $this->formatInvoiceIdentifier($invoice),
                    'customer' => $this->invoiceCustomerSummary($invoice->customer),
                    'due_date' => (string) ($invoice->due_date?->format('M j, Y') ?: 'Not set'),
                    'days_overdue' => (string) $this->daysOverdue($invoice),
                    'paid_status' => $this->invoicePaidLabel((string) $invoice->paid_status),
                    'due_amount' => $this->formatInvoiceMoney($invoice->due_amount),
                ];
            })->all();

            return '<h4>Unpaid invoice summary</h4>'
                . $summary
                . '<h4>Collections queue</h4>'
                . $this->formatAsHtmlTable(
                    $rows,
                    [
                        'invoice' => 'Invoice',
                        'customer' => 'Customer',
                        'due_date' => 'Due date',
                        'days_overdue' => 'Days overdue',
                        'paid_status' => 'Paid status',
                        'due_amount' => 'Due amount',
                    ],
                    'No unpaid invoices matched the requested filters.',
                    'billing-invoice-unpaid-results'
                );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading unpaid invoice summary: ' . $exception->getMessage());
        }
    }
}
