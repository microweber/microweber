<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use Illuminate\Support\Str;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Customer\Models\Customer;

abstract class AbstractBillingTool extends BaseTool
{
    protected string $domain = 'billing';

    protected array $requiredPermissions = ['view billing'];

    protected function safeLimit(mixed $limit, int $default = 20, int $max = 50): int
    {
        return max(1, min($max, (int) ($limit ?? $default)));
    }

    protected function normalizeBooleanString(mixed $value, bool $default = false): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = strtolower(trim((string) $value));

        if ($normalized === '') {
            return $default;
        }

        return in_array($normalized, ['1', 'true', 'yes', 'y'], true);
    }

    protected function normalizePeriodDays(mixed $value, int $default = 30): int
    {
        return max(1, min(365, (int) ($value ?: $default)));
    }

    protected function maskEmail(string $email): string
    {
        if (! str_contains($email, '@')) {
            return 'Hidden';
        }

        [$local, $domain] = explode('@', $email, 2);
        $visible = Str::substr($local, 0, 2);

        if ($visible === '') {
            $visible = '*';
        }

        return $visible . str_repeat('*', max(2, strlen($local) - strlen($visible))) . '@' . $domain;
    }

    protected function maskStripeId(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return 'Not set';
        }

        if (strlen($value) <= 12) {
            return Str::substr($value, 0, 4) . '...' . Str::substr($value, -2);
        }

        return Str::substr($value, 0, 8) . '...' . Str::substr($value, -4);
    }

    protected function maskPaymentMethod(?string $lastFour): string
    {
        $lastFour = preg_replace('/\D+/', '', (string) $lastFour) ?? '';

        if ($lastFour === '') {
            return 'No payment method';
        }

        return '****' . substr($lastFour, -4);
    }

    protected function formatMoney(mixed $amount, string $currency = 'USD'): string
    {
        return number_format((float) $amount, 2) . ' ' . strtoupper($currency ?: 'USD');
    }

    protected function yesNoLabel(bool $value): string
    {
        return $value ? 'Yes' : 'No';
    }

    protected function subscriptionStatusLabel(Subscription $subscription): string
    {
        if ($subscription->onGracePeriod()) {
            return 'grace_period';
        }

        return (string) ($subscription->stripe_status ?: 'unknown');
    }

    protected function monthlyRecurringRevenue(iterable $subscriptions): float
    {
        $total = 0.0;

        foreach ($subscriptions as $subscription) {
            $plan = $subscription->plan;

            if (! $plan instanceof SubscriptionPlan) {
                continue;
            }

            $price = (float) $plan->price;
            $interval = strtolower((string) ($plan->billing_interval ?: 'monthly'));

            $total += $interval === 'yearly' ? ($price / 12) : $price;
        }

        return $total;
    }

    protected function customerDisplayName(Customer $customer): string
    {
        $fullName = trim((string) ($customer->first_name . ' ' . $customer->last_name));

        if ($fullName !== '') {
            return $fullName;
        }

        if ((string) $customer->name !== '') {
            return (string) $customer->name;
        }

        return 'Customer #' . $customer->id;
    }
}
