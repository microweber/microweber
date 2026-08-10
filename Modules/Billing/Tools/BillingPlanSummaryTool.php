<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Modules\Billing\Models\SubscriptionPlan;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingPlanSummaryTool extends AbstractBillingTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_plan_summary',
            'List subscription plans with pricing, intervals, discounts, and feature summaries.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'group_sku',
                type: PropertyType::STRING,
                description: 'Optional plan-group SKU filter.',
                required: false,
            ),
            new ToolProperty(
                name: 'include_inactive',
                type: PropertyType::STRING,
                description: 'Set to "yes" to include inactive plans. Default is "no".',
                required: false,
            ),
            new ToolProperty(
                name: 'currency',
                type: PropertyType::STRING,
                description: 'Optional currency filter such as USD or EUR.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of plans to return (1-50). Default is 20.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $groupSku = trim((string) ($args['group_sku'] ?? ''));
        $includeInactive = $this->normalizeBooleanString($args['include_inactive'] ?? false, false);
        $currency = strtoupper(trim((string) ($args['currency'] ?? '')));
        $limit = $this->safeLimit($args['limit'] ?? 20);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view billing plans.');
        }

        try {
            $query = SubscriptionPlan::query()->with(['group', 'features']);

            if (! $includeInactive && \Schema::hasColumn('subscription_plans', 'is_active')) {
                $query->where('is_active', true);
            }

            if ($groupSku !== '') {
                $query->whereHas('group', function ($builder) use ($groupSku): void {
                    $builder->where('sku', $groupSku);
                });
            }

            if ($currency !== '' && \Schema::hasColumn('subscription_plans', 'currency')) {
                $query->where('currency', $currency);
            }

            $plans = $query
                ->orderBy('sort_order')
                ->orderBy('id')
                ->limit($limit)
                ->get();

            if ($plans->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'plan' => 'Plan',
                        'price' => 'Price',
                    ],
                    'No billing plans matched the current filters.',
                    'billing-plan-summary-empty'
                );
            }

            $rows = $plans->map(function (SubscriptionPlan $plan): array {
                $featureSummary = $plan->features
                    ->take(3)
                    ->map(function ($feature): string {
                        $label = (string) ($feature->key ?: 'feature');
                        $value = trim((string) ($feature->value ?: $feature->description ?: 'enabled'));

                        if ((string) $feature->limit !== '') {
                            $value .= ' (limit ' . $feature->limit . ')';
                        }

                        return $label . ': ' . $value;
                    })
                    ->implode('; ');

                if ($featureSummary === '') {
                    $featureSummary = 'No feature records';
                }

                return [
                    'plan' => '#' . $plan->id . ' ' . $plan->name,
                    'group' => $plan->group?->name ?: 'Ungrouped',
                    'sku' => (string) ($plan->sku ?: 'n/a'),
                    'price' => $this->formatMoney($plan->price, (string) ($plan->currency ?: 'USD')),
                    'interval' => (string) ($plan->billing_interval ?: 'monthly'),
                    'discount' => (string) ($plan->discount_price !== null && $plan->discount_price !== '' ? $this->formatMoney($plan->discount_price, (string) ($plan->currency ?: 'USD')) : 'None'),
                    'trial_days' => (string) ((int) ($plan->trial_days ?? 0)),
                    'features' => $featureSummary,
                ];
            })->all();

            return $this->formatAsHtmlTable(
                $rows,
                [
                    'plan' => 'Plan',
                    'group' => 'Group',
                    'sku' => 'SKU',
                    'price' => 'Price',
                    'interval' => 'Interval',
                    'discount' => 'Discount',
                    'trial_days' => 'Trial days',
                    'features' => 'Feature summary',
                ],
                '',
                'billing-plan-summary-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error summarizing billing plans: ' . $exception->getMessage());
        }
    }
}
