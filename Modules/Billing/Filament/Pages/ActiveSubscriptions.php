<?php

namespace Modules\Billing\Filament\Pages;

use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;

class ActiveSubscriptions extends Page
{
    protected static ?string $title = 'Active Subscriptions';

    protected static ?string $slug = 'active-subscriptions';

    protected static bool $shouldRegisterNavigation = true;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?int $navigationSort = 10;

    protected string $view = 'modules.billing::filament.pages.active-subscriptions';

    public array $groupedSubscriptions = [];

    public array $stats = [];

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect(route('login'));
            return;
        }

        $this->loadSubscriptions();
        $this->loadStats();
    }

    private function loadSubscriptions(): void
    {
        $user = Auth::user();

        $subscriptions = Subscription::with(['plan.group'])
            ->where('user_id', $user->id)
            ->whereIn('stripe_status', ['active', 'trialing'])
            ->get();

        $grouped = [];

        foreach ($subscriptions as $subscription) {
            $groupName = $subscription->plan->group->name ?? 'Plans';

            $subscriptionData = [
                'id' => $subscription->id,
                'stripe_id' => $subscription->stripe_id,
                'stripe_status' => $subscription->stripe_status,
                'plan' => [
                    'name' => $subscription->plan->name ?? 'Unknown Plan',
                    'description' => $subscription->plan->description ?? '',
                    'price' => $subscription->plan->price ?? 0,
                    'billing_interval' => $subscription->plan->billing_interval ?? 'month',
                    'currency' => $subscription->plan->currency ?? 'USD',
                ],
                'ends_at' => $subscription->ends_at,
                'current_period_end' => $subscription->current_period_end,
                'trial_ends_at' => $subscription->trial_ends_at,
                'created_at' => $subscription->created_at,
            ];

            if (!isset($grouped[$groupName])) {
                $grouped[$groupName] = [];
            }

            $grouped[$groupName][] = $subscriptionData;
        }

        $this->groupedSubscriptions = $grouped;
    }

    private function loadStats(): void
    {
        $user = Auth::user();

        $activeCount = Subscription::where('user_id', $user->id)
            ->whereIn('stripe_status', ['active', 'trialing'])
            ->count();

        $trialingCount = Subscription::where('user_id', $user->id)
            ->where('stripe_status', 'trialing')
            ->count();

        $monthlySpend = Subscription::where('user_id', $user->id)
            ->whereIn('stripe_status', ['active', 'trialing'])
            ->with('plan')
            ->get()
            ->sum(function ($sub) {
                return $sub->plan->price ?? 0;
            });

        $nextBilling = Subscription::where('user_id', $user->id)
            ->whereIn('stripe_status', ['active', 'trialing'])
            ->whereNotNull('ends_at')
            ->orderBy('ends_at', 'asc')
            ->first();

        $this->stats = [
            'active_count' => $activeCount,
            'trialing_count' => $trialingCount,
            'monthly_spend' => $monthlySpend,
            'next_billing_date' => $nextBilling?->ends_at,
            'next_billing_plan' => $nextBilling?->plan?->name ?? null,
        ];
    }

    public function cancelSubscription(int $subscriptionId): void
    {
        $user = Auth::user();

        $subscription = Subscription::where('id', $subscriptionId)
            ->where('user_id', $user->id)
            ->first();

        if (!$subscription) {
            return;
        }

        try {
            $customer = $subscription->owner;
            if ($customer) {
                $stripe = $customer->stripe();
                $stripe->subscriptions->cancel($subscription->stripe_id);

                $subscription->update([
                    'stripe_status' => 'canceled',
                    'ends_at' => now(),
                ]);

                // Refresh the data
                $this->loadSubscriptions();
                $this->loadStats();
            }
        } catch (\Exception $e) {
            // Log error
        }
    }

    public function getBreadcrumb(): string
    {
        return 'Active Subscriptions';
    }
}
