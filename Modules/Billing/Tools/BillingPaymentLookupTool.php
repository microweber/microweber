<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Modules\Payment\Models\Payment;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingPaymentLookupTool extends AbstractPaymentTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_payment_lookup',
            'Search payments by transaction ID, provider, status, related record, or date range.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'payment_id', type: PropertyType::INTEGER, description: 'Optional payment ID to fetch directly.', required: false),
            new ToolProperty(name: 'search_term', type: PropertyType::STRING, description: 'Optional transaction ID, related ID, or provider search term.', required: false),
            new ToolProperty(name: 'status', type: PropertyType::STRING, description: 'Optional payment status filter.', required: false),
            new ToolProperty(name: 'provider', type: PropertyType::STRING, description: 'Optional provider filter such as stripe or paypal.', required: false),
            new ToolProperty(name: 'rel_type', type: PropertyType::STRING, description: 'Optional related type filter, for example order.', required: false),
            new ToolProperty(name: 'date_from', type: PropertyType::STRING, description: 'Optional lower created-at date in YYYY-MM-DD format.', required: false),
            new ToolProperty(name: 'date_to', type: PropertyType::STRING, description: 'Optional upper created-at date in YYYY-MM-DD format.', required: false),
            new ToolProperty(name: 'limit', type: PropertyType::INTEGER, description: 'Maximum payments to return (1-50). Default is 10.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $paymentId = isset($args['payment_id']) ? (int) $args['payment_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $status = $this->normalizePaymentStatus($args['status'] ?? '');
        $provider = strtolower(trim((string) ($args['provider'] ?? '')));
        $relType = trim((string) ($args['rel_type'] ?? ''));
        $dateFrom = $this->normalizeDate($args['date_from'] ?? null);
        $dateTo = $this->normalizeDate($args['date_to'] ?? null);
        $limit = $this->safeLimit($args['limit'] ?? 10, 10, 50);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view payments.');
        }

        try {
            $query = Payment::query()->with('paymentProvider');

            if ($paymentId !== null && $paymentId > 0) {
                $query->where('id', $paymentId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('transaction_id', 'like', '%' . $searchTerm . '%')
                        ->orWhere('rel_id', 'like', '%' . $searchTerm . '%')
                        ->orWhere('payment_provider', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($status !== '') {
                $query->where('status', $status);
            }

            if ($provider !== '') {
                $query->where('payment_provider', $provider);
            }

            if ($relType !== '') {
                $query->where('rel_type', $relType);
            }

            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom->toDateString());
            }

            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo->toDateString());
            }

            $payments = $query
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();

            $summaryBits = [];
            if ($searchTerm !== '') {
                $summaryBits[] = 'Search: "' . e($searchTerm) . '"';
            }
            if ($status !== '') {
                $summaryBits[] = 'Status: ' . $this->paymentStatusLabel($status);
            }
            if ($provider !== '') {
                $summaryBits[] = 'Provider: ' . $provider;
            }

            $header = '<h4>Payment lookup</h4><p>'
                . ($summaryBits !== [] ? implode(' | ', $summaryBits) . ' | ' : '')
                . '<strong>Found:</strong> ' . $payments->count() . ' payment(s)</p>';

            $rows = $payments->map(function (Payment $payment): array {
                return [
                    'payment' => $this->paymentIdentifier($payment),
                    'provider' => (string) ($payment->payment_provider ?: 'unknown'),
                    'status' => $this->paymentStatusLabel((string) $payment->status),
                    'amount' => $this->formatMoney((float) ($payment->amount ?? 0), (string) ($payment->currency ?: 'USD')),
                    'relation' => $this->relationSummary($payment),
                    'created_at' => (string) ($payment->created_at?->format('M j, Y H:i') ?: 'Unknown'),
                ];
            })->all();

            return $header . $this->formatAsHtmlTable(
                $rows,
                [
                    'payment' => 'Payment',
                    'provider' => 'Provider',
                    'status' => 'Status',
                    'amount' => 'Amount',
                    'relation' => 'Related record',
                    'created_at' => 'Created',
                ],
                'No payments matched the requested filters.',
                'billing-payment-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading payment lookup: ' . $exception->getMessage());
        }
    }
}
