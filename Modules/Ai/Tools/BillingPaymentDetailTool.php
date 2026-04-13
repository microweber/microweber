<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Payment\Models\Payment;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingPaymentDetailTool extends AbstractPaymentTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_payment_detail',
            'Inspect a single payment transaction without exposing raw provider payloads or secrets.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'payment_id', type: PropertyType::INTEGER, description: 'Optional payment ID to inspect.', required: false),
            new ToolProperty(name: 'transaction_id', type: PropertyType::STRING, description: 'Optional transaction ID to inspect.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $paymentId = isset($args['payment_id']) ? (int) $args['payment_id'] : null;
        $transactionId = trim((string) ($args['transaction_id'] ?? ''));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view payment details.');
        }

        if (($paymentId === null || $paymentId <= 0) && $transactionId === '') {
            return $this->handleError('Provide either payment_id or transaction_id to inspect a payment.');
        }

        try {
            $query = Payment::query()->with('paymentProvider');

            if ($paymentId !== null && $paymentId > 0) {
                $query->where('id', $paymentId);
            } else {
                $query->where('transaction_id', $transactionId);
            }

            $payment = $query->first();

            if (! $payment instanceof Payment) {
                return $this->formatAsHtmlTable([], ['payment' => 'Payment'], 'No payment matched the requested identifier.', 'billing-payment-detail-empty');
            }

            $provider = $payment->paymentProvider;

            return '<h4>Payment detail</h4>' . $this->formatAsHtmlTable(
                [[
                    'payment' => $this->paymentIdentifier($payment),
                    'status' => $this->paymentStatusLabel((string) $payment->status),
                    'amount' => $this->formatMoney((float) ($payment->amount ?? 0), (string) ($payment->currency ?: 'USD')),
                    'provider' => (string) ($provider?->name ?: ($payment->payment_provider ?: 'unknown')),
                    'provider_code' => (string) ($payment->payment_provider ?: 'unknown'),
                    'relation' => $this->relationSummary($payment),
                    'created_at' => (string) ($payment->created_at?->format('M j, Y H:i') ?: 'Unknown'),
                    'provider_active' => $provider ? ((int) $provider->is_active === 1 ? 'Yes' : 'No') : 'Unknown',
                    'provider_default' => $provider ? ((int) $provider->is_default === 1 ? 'Yes' : 'No') : 'Unknown',
                    'raw_payload' => 'Hidden for safety',
                ]],
                [
                    'payment' => 'Payment',
                    'status' => 'Status',
                    'amount' => 'Amount',
                    'provider' => 'Provider',
                    'provider_code' => 'Provider code',
                    'relation' => 'Related record',
                    'created_at' => 'Created',
                    'provider_active' => 'Provider active',
                    'provider_default' => 'Default provider',
                    'raw_payload' => 'Provider payload',
                ],
                '',
                'billing-payment-detail-summary'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading payment detail: ' . $exception->getMessage());
        }
    }
}
