<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Modules\Billing\Models\Subscription;
use Modules\Customer\Models\Customer;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingSubscriptionLookupTool extends AbstractBillingTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_subscription_lookup',
            'Search billing subscriptions by customer, email, plan, status, or provider reference.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional search term for customer email/name, plan name/SKU, Stripe subscription ID, or user ID.',
                required: false,
            ),
            new ToolProperty(
                name: 'status',
                type: PropertyType::STRING,
                description: 'Optional subscription status filter such as "active", "trialing", "canceled", or "past_due".',
                required: false,
            ),
            new ToolProperty(
                name: 'include_canceled',
                type: PropertyType::STRING,
                description: 'Set to "yes" to include canceled subscriptions. Default is "no".',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of subscriptions to return (1-50). Default is 20.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $status = trim((string) ($args['status'] ?? ''));
        $includeCanceled = $this->normalizeBooleanString($args['include_canceled'] ?? false, false);
        $limit = $this->safeLimit($args['limit'] ?? 20);

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view billing subscriptions.');
        }

        try {
            $query = Subscription::query()
                ->with(['plan.group']);

            if (! $includeCanceled) {
                $query->where('stripe_status', '!=', 'canceled');
            }

            if ($status !== '') {
                $query->where('stripe_status', $status);
            }

            if ($searchTerm !== '') {
                $matchingCustomerIds = Customer::query()
                    ->where(function ($customerQuery) use ($searchTerm): void {
                        $customerQuery->where('email', 'like', '%' . $searchTerm . '%')
                            ->orWhere('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                    })
                    ->pluck('id')
                    ->all();

                $query->where(function ($builder) use ($searchTerm, $matchingCustomerIds): void {
                    $builder->where('stripe_id', 'like', '%' . $searchTerm . '%')
                        ->orWhere('stripe_price', 'like', '%' . $searchTerm . '%')
                        ->orWhere('user_id', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('plan', function ($planQuery) use ($searchTerm): void {
                            $planQuery->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('sku', 'like', '%' . $searchTerm . '%');
                        });

                    if ($matchingCustomerIds !== []) {
                        $builder->orWhereIn('customer_id', $matchingCustomerIds);
                    }
                });
            }

            $subscriptions = $query
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get();

            if ($subscriptions->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'subscription' => 'Subscription',
                        'customer' => 'Customer',
                        'status' => 'Status',
                    ],
                    'No billing subscriptions matched the current filters.',
                    'billing-subscription-lookup-empty'
                );
            }

            $owners = Customer::query()
                ->whereIn('id', $subscriptions->pluck('customer_id')->filter()->unique()->all())
                ->get()
                ->keyBy('id');

            $rows = $subscriptions->map(function (Subscription $subscription) use ($owners): array {
                $owner = $owners->get($subscription->customer_id);
                $plan = $subscription->plan;

                return [
                    'subscription' => '#' . $subscription->id . ' ' . $this->maskStripeId((string) $subscription->stripe_id),
                    'customer' => $owner
                        ? ($this->customerDisplayName($owner) . ' / ' . $this->maskEmail((string) $owner->email))
                        : 'Unknown customer',
                    'plan' => $plan
                        ? ($plan->name . ((string) $plan->sku !== '' ? ' (' . $plan->sku . ')' : ''))
                        : ($subscription->name ?: 'Unknown plan'),
                    'status' => $this->subscriptionStatusLabel($subscription),
                    'billing' => $plan
                        ? $this->formatMoney($plan->price, (string) ($plan->currency ?: 'USD')) . ' / ' . ($plan->billing_interval ?: 'monthly')
                        : 'Unknown',
                    'next_billing' => (string) ($subscription->nextBillingDate() ?: 'Not scheduled'),
                    'trial' => $subscription->trial_ends_at ? (string) $subscription->trial_ends_at : 'No trial',
                ];
            })->all();

            return $this->formatAsHtmlTable(
                $rows,
                [
                    'subscription' => 'Subscription',
                    'customer' => 'Customer',
                    'plan' => 'Plan',
                    'status' => 'Status',
                    'billing' => 'Billing',
                    'next_billing' => 'Next billing',
                    'trial' => 'Trial ends',
                ],
                '',
                'billing-subscription-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error looking up billing subscriptions: ' . $exception->getMessage());
        }
    }
}
