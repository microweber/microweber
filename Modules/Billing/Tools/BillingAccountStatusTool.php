<?php

declare(strict_types=1);

namespace Modules\Billing\Tools;

use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionCustomer;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class BillingAccountStatusTool extends AbstractBillingTool
{
    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'billing_account_status',
            'Inspect a customer billing account with active subscriptions, masked payment details, and next billing state.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'customer_id',
                type: PropertyType::INTEGER,
                description: 'Optional customer ID to inspect.',
                required: false,
            ),
            new ToolProperty(
                name: 'user_id',
                type: PropertyType::INTEGER,
                description: 'Optional user ID to inspect.',
                required: false,
            ),
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional customer search term for email or name.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $customerId = isset($args['customer_id']) ? (int) $args['customer_id'] : null;
        $userId = isset($args['user_id']) ? (int) $args['user_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view billing account status.');
        }

        try {
            $query = SubscriptionCustomer::query()->with(['subscriptions.plan', 'user']);

            if ($customerId !== null && $customerId > 0) {
                $query->where('id', $customerId);
            }

            if ($userId !== null && $userId > 0) {
                $query->where('user_id', $userId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                });
            }

            $customer = $query->first();

            if (! $customer instanceof SubscriptionCustomer) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'customer' => 'Customer',
                    ],
                    'No billing customer matched the requested account lookup.',
                    'billing-account-status-empty'
                );
            }

            $subscriptions = $customer->subscriptions
                ->sortByDesc('created_at')
                ->values();

            $activeSubscriptions = $subscriptions->filter(fn (Subscription $subscription): bool => $subscription->isActive());
            $pastDueSubscriptions = $subscriptions->filter(fn (Subscription $subscription): bool => $subscription->isPastDue() || $subscription->isUnpaid());

            $summary = $this->formatAsHtmlTable(
                [[
                    'customer' => '#' . $customer->id . ' ' . $this->customerDisplayName($customer),
                    'email' => $this->maskEmail((string) $customer->email),
                    'customer_status' => (string) ($customer->status ?: 'unknown'),
                    'payment_method' => (($customer->pm_type ?: 'Card') . ' ' . $this->maskPaymentMethod($customer->pm_last_four)),
                    'subscriptions' => (string) $subscriptions->count(),
                    'active' => (string) $activeSubscriptions->count(),
                    'past_due' => (string) $pastDueSubscriptions->count(),
                    'trial_ends' => (string) ($customer->trial_ends_at ?: 'No customer trial'),
                ]],
                [
                    'customer' => 'Customer',
                    'email' => 'Email',
                    'customer_status' => 'Status',
                    'payment_method' => 'Payment method',
                    'subscriptions' => 'Subscriptions',
                    'active' => 'Active',
                    'past_due' => 'Past due',
                    'trial_ends' => 'Customer trial ends',
                ],
                '',
                'billing-account-status-summary'
            );

            $subscriptionRows = $subscriptions->map(function (Subscription $subscription): array {
                $plan = $subscription->plan;

                return [
                    'subscription' => '#' . $subscription->id . ' ' . $this->maskStripeId((string) $subscription->stripe_id),
                    'plan' => $plan?->name ?: ($subscription->name ?: 'Unknown plan'),
                    'status' => $this->subscriptionStatusLabel($subscription),
                    'next_billing' => (string) ($subscription->nextBillingDate() ?: 'Not scheduled'),
                    'billing' => $plan
                        ? $this->formatMoney($plan->price, (string) ($plan->currency ?: 'USD')) . ' / ' . ($plan->billing_interval ?: 'monthly')
                        : 'Unknown',
                ];
            })->all();

            return '<h4>Billing account summary</h4>'
                . $summary
                . '<h4>Subscriptions</h4>'
                . $this->formatAsHtmlTable(
                    $subscriptionRows,
                    [
                        'subscription' => 'Subscription',
                        'plan' => 'Plan',
                        'status' => 'Status',
                        'next_billing' => 'Next billing',
                        'billing' => 'Billing',
                    ],
                    'This billing account has no subscriptions.',
                    'billing-account-status-subscriptions'
                );
        } catch (\Throwable $exception) {
            return $this->handleError('Error loading billing account status: ' . $exception->getMessage());
        }
    }
}
