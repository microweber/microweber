<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingPaymentProviderHealthTool extends AbstractPaymentTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_payment_provider_health',
            'Summarize payment provider transaction health, success rate, and recent volume.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(name: 'provider', type: PropertyType::STRING, description: 'Optional provider code such as stripe or paypal.', required: false),
            new ToolProperty(name: 'period_days', type: PropertyType::INTEGER, description: 'Lookback window in days (1-365). Default is 30.', required: false),
            new ToolProperty(name: 'include_breakdown', type: PropertyType::STRING, description: 'Set to yes to include payment status breakdown rows.', required: false),
        ];
    }

    public function __invoke(...$args): string
    {
        $provider = strtolower(trim((string) ($args['provider'] ?? '')));
        $periodDays = $this->normalizePeriodDays($args['period_days'] ?? 30, 30);
        $includeBreakdown = $this->normalizeBooleanString($args['include_breakdown'] ?? false, false);
        $windowStart = now()->subDays($periodDays);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view payment provider health.');
        }

        try {
            $providerQuery = PaymentProvider::query();

            if ($provider !== '') {
                $providerQuery->where('provider', $provider);
            }

            $providers = $providerQuery->orderBy('position')->get();

            $rows = $providers->map(function (PaymentProvider $paymentProvider) use ($windowStart): array {
                $payments = Payment::query()
                    ->where('payment_provider', (string) $paymentProvider->provider)
                    ->where('created_at', '>=', $windowStart)
                    ->get();

                $completed = $payments->where('status', 'completed');
                $failed = $payments->where('status', 'failed');
                $pending = $payments->where('status', 'pending');
                $successRate = $payments->count() > 0 ? round(($completed->count() / $payments->count()) * 100, 1) : 0.0;

                return [
                    'provider' => (string) ($paymentProvider->name ?: $paymentProvider->provider),
                    'code' => (string) ($paymentProvider->provider ?: 'unknown'),
                    'active' => (int) $paymentProvider->is_active === 1 ? 'Yes' : 'No',
                    'default' => (int) $paymentProvider->is_default === 1 ? 'Yes' : 'No',
                    'payments' => (string) $payments->count(),
                    'completed' => (string) $completed->count(),
                    'failed' => (string) $failed->count(),
                    'pending' => (string) $pending->count(),
                    'success_rate' => number_format($successRate, 1) . '%',
                    'completed_volume' => $this->formatMoney((float) $completed->sum('amount'), (string) ($payments->first()?->currency ?: 'USD')),
                ];
            })->all();

            $response = '<h4>Payment provider health</h4>' . $this->formatAsHtmlTable(
                $rows,
                [
                    'provider' => 'Provider',
                    'code' => 'Code',
                    'active' => 'Active',
                    'default' => 'Default',
                    'payments' => 'Payments',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                    'pending' => 'Pending',
                    'success_rate' => 'Success rate',
                    'completed_volume' => 'Completed volume',
                ],
                'No payment providers matched the requested filters.',
                'billing-payment-provider-health-results'
            );

            if (! $includeBreakdown) {
                return $response;
            }

            $breakdownQuery = Payment::query()
                ->selectRaw('payment_provider, status, COUNT(*) as aggregate_count')
                ->where('created_at', '>=', $windowStart);

            if ($provider !== '') {
                $breakdownQuery->where('payment_provider', $provider);
            }

            $breakdownRows = $breakdownQuery
                ->groupBy('payment_provider', 'status')
                ->orderBy('payment_provider')
                ->orderByDesc('aggregate_count')
                ->get()
                ->map(fn ($row): array => [
                    'provider' => (string) ($row->payment_provider ?: 'unknown'),
                    'status' => $this->paymentStatusLabel((string) $row->status),
                    'count' => (string) $row->aggregate_count,
                ])
                ->all();

            return $response . '<h4>Provider payment breakdown</h4>' . $this->formatAsHtmlTable(
                $breakdownRows,
                [
                    'provider' => 'Provider',
                    'status' => 'Status',
                    'count' => 'Count',
                ],
                'No payment breakdown data is available.',
                'billing-payment-provider-breakdown'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading payment provider health: ' . $exception->getMessage());
        }
    }
}
