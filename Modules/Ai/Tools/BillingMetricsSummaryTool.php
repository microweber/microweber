<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Carbon\Carbon;
use Modules\Billing\Models\Subscription;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingMetricsSummaryTool extends AbstractBillingTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_metrics_summary',
            'Summarize recurring billing metrics such as MRR, active subscriptions, trialing subscriptions, and churn.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'period_days',
                type: PropertyType::INTEGER,
                description: 'Churn lookback window in days (1-365). Default is 30.',
                required: false,
            ),
            new ToolProperty(
                name: 'include_breakdown',
                type: PropertyType::STRING,
                description: 'Set to "yes" to include status breakdown rows. Default is "no".',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $periodDays = $this->normalizePeriodDays($args['period_days'] ?? 30, 30);
        $includeBreakdown = $this->normalizeBooleanString($args['include_breakdown'] ?? false, false);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view billing metrics.');
        }

        try {
            $activeSubscriptions = Subscription::query()
                ->with('plan')
                ->where('stripe_status', 'active')
                ->get();

            $trialingCount = Subscription::query()->where('stripe_status', 'trialing')->count();
            $totalCount = Subscription::query()->count();
            $pastDueCount = Subscription::query()->whereIn('stripe_status', ['past_due', 'unpaid'])->count();

            $mrr = $this->monthlyRecurringRevenue($activeSubscriptions);
            $windowStart = Carbon::now()->subDays($periodDays);

            $canceledInWindow = Subscription::query()
                ->where('stripe_status', 'canceled')
                ->where('updated_at', '>=', $windowStart)
                ->count();

            $baseline = Subscription::query()
                ->where('created_at', '<', $windowStart)
                ->count();

            $churnRate = $baseline > 0
                ? round(($canceledInWindow / $baseline) * 100, 2)
                : 0.0;

            $summary = $this->formatAsHtmlTable(
                [[
                    'mrr' => $this->formatMoney($mrr, 'USD'),
                    'active_subscriptions' => (string) $activeSubscriptions->count(),
                    'trialing_subscriptions' => (string) $trialingCount,
                    'past_due_or_unpaid' => (string) $pastDueCount,
                    'total_subscriptions' => (string) $totalCount,
                    'churn_rate' => number_format($churnRate, 2) . '%',
                    'window' => (string) $periodDays . ' days',
                ]],
                [
                    'mrr' => 'MRR',
                    'active_subscriptions' => 'Active',
                    'trialing_subscriptions' => 'Trialing',
                    'past_due_or_unpaid' => 'Past due / unpaid',
                    'total_subscriptions' => 'Total',
                    'churn_rate' => 'Churn rate',
                    'window' => 'Window',
                ],
                '',
                'billing-metrics-summary-results'
            );

            if (! $includeBreakdown) {
                return $summary;
            }

            $breakdownRows = Subscription::query()
                ->selectRaw('stripe_status, COUNT(*) as aggregate_count')
                ->groupBy('stripe_status')
                ->orderByDesc('aggregate_count')
                ->get()
                ->map(fn ($row): array => [
                    'status' => (string) ($row->stripe_status ?: 'unknown'),
                    'count' => (string) $row->aggregate_count,
                ])
                ->all();

            return $summary . '<h4>Status breakdown</h4>' . $this->formatAsHtmlTable(
                $breakdownRows,
                [
                    'status' => 'Status',
                    'count' => 'Count',
                ],
                'No subscription status data is available.',
                'billing-metrics-status-breakdown'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error summarizing billing metrics: ' . $exception->getMessage());
        }
    }
}
