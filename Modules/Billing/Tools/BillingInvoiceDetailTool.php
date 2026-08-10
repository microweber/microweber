<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceItem;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingInvoiceDetailTool extends AbstractInvoiceTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_invoice_detail',
            'Inspect a single invoice with masked customer data, totals, line items, and order reference context.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'invoice_id', type: PropertyType::INTEGER, description: 'Optional invoice ID to inspect.', required: false),
            new ToolProperty(name: 'invoice_number', type: PropertyType::STRING, description: 'Optional invoice number to inspect.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $invoiceId = isset($args['invoice_id']) ? (int) $args['invoice_id'] : null;
        $invoiceNumber = trim((string) ($args['invoice_number'] ?? ''));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view invoice details.');
        }

        if (($invoiceId === null || $invoiceId <= 0) && $invoiceNumber === '') {
            return $this->handleError('Provide either invoice_id or invoice_number to inspect an invoice.');
        }

        try {
            $query = Invoice::query()->with(['customer', 'items']);

            if ($invoiceId !== null && $invoiceId > 0) {
                $query->where('id', $invoiceId);
            } else {
                $query->where('invoice_number', $invoiceNumber);
            }

            $invoice = $query->first();

            if (! $invoice instanceof Invoice) {
                return $this->formatAsHtmlTable(
                    [],
                    ['invoice' => 'Invoice'],
                    'No invoice matched the requested identifier.',
                    'billing-invoice-detail-empty'
                );
            }

            $summary = $this->formatAsHtmlTable(
                [[
                    'invoice' => $this->formatInvoiceIdentifier($invoice),
                    'customer' => $this->invoiceCustomerSummary($invoice->customer),
                    'invoice_date' => (string) ($invoice->invoice_date?->format('M j, Y') ?: 'Not set'),
                    'due_date' => (string) ($invoice->due_date?->format('M j, Y') ?: 'Not set'),
                    'status' => $this->invoiceWorkflowLabel((string) $invoice->status),
                    'paid_status' => $this->invoicePaidLabel((string) $invoice->paid_status),
                    'sub_total' => $this->formatInvoiceMoney($invoice->sub_total),
                    'discount' => $this->formatInvoiceMoney($invoice->discount_val) . (($invoice->discount_type ?: '') !== '' ? ' (' . e((string) $invoice->discount_type) . ')' : ''),
                    'tax' => $this->formatInvoiceMoney((int) round(((float) ($invoice->tax ?? 0)) * 100)),
                    'total' => $this->formatInvoiceMoney($invoice->total),
                    'due_amount' => $this->formatInvoiceMoney($invoice->due_amount),
                    'overdue' => $invoice->isOverdue() ? ('Yes, ' . $this->daysOverdue($invoice) . ' day(s)') : 'No',
                ]],
                [
                    'invoice' => 'Invoice',
                    'customer' => 'Customer',
                    'invoice_date' => 'Invoice date',
                    'due_date' => 'Due date',
                    'status' => 'Status',
                    'paid_status' => 'Paid status',
                    'sub_total' => 'Subtotal',
                    'discount' => 'Discount',
                    'tax' => 'Tax',
                    'total' => 'Total',
                    'due_amount' => 'Due amount',
                    'overdue' => 'Overdue',
                ],
                '',
                'billing-invoice-detail-summary'
            );

            $items = $invoice->items
                ->map(function (InvoiceItem $item): array {
                    return [
                        'item' => (string) ($item->name ?: 'Line item'),
                        'description' => (string) ($item->description ?: 'No description'),
                        'price' => $this->formatInvoiceMoney($item->price),
                        'quantity' => (string) ($item->quantity ?: 0),
                        'subtotal' => $this->formatInvoiceMoney($item->subtotal),
                    ];
                })
                ->all();

            $meta = '';
            if ((string) $invoice->reference_number !== '' || (string) $invoice->notes !== '' || $invoice->tax_per_item || $invoice->discount_per_item) {
                $meta = '<h4>Invoice metadata</h4>' . $this->formatAsHtmlTable(
                    [[
                        'reference' => (string) ($invoice->reference_number ?: 'Not linked'),
                        'tax_mode' => $invoice->tax_per_item ? 'Per item' : 'Invoice level',
                        'discount_mode' => $invoice->discount_per_item ? 'Per item' : 'Invoice level',
                        'notes' => (string) ($invoice->notes ?: 'No notes'),
                    ]],
                    [
                        'reference' => 'Reference',
                        'tax_mode' => 'Tax mode',
                        'discount_mode' => 'Discount mode',
                        'notes' => 'Notes',
                    ],
                    '',
                    'billing-invoice-detail-meta'
                );
            }

            return '<h4>Invoice detail</h4>'
                . $summary
                . '<h4>Line items</h4>'
                . $this->formatAsHtmlTable(
                    $items,
                    [
                        'item' => 'Item',
                        'description' => 'Description',
                        'price' => 'Price',
                        'quantity' => 'Qty',
                        'subtotal' => 'Subtotal',
                    ],
                    'This invoice has no line items.',
                    'billing-invoice-detail-items'
                )
                . $meta;
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading invoice detail: ' . $exception->getMessage());
        }
    }
}
