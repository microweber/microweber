<?php

namespace Modules\Billing\Models;

use DateTimeZone;
use Modules\Billing\Database\Factories\SubscriptionFactory;
use Illuminate\Support\Facades\Log;

class Subscription extends \Laravel\Cashier\Subscription
{
    protected $table = 'subscriptions';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): SubscriptionFactory
    {
        return new SubscriptionFactory();
    }


    public function getNameAttribute()
    {
        $stripePriceId = $this->stripe_price;
        $findPlan = SubscriptionPlan::where('remote_provider_price_id', $stripePriceId)->first();
        if ($findPlan) {
            return $findPlan->name;
        }

        return '';
    }

    public function getUsernameAttribute()
    {
        return user_name($this->owner()->first()->user_id);
    }

    public function getEmailAttribute()
    {
        return user_email($this->owner()->first()->user_id);
    }

    public function isActive()
    {
        return $this->stripe_status === 'active' || $this->stripe_status === 'trialing';
    }

    public function isExpired()
    {
        return $this->stripe_status === 'expired' || ($this->ends_at && $this->ends_at->isPast());
    }

    /**
     * Check if the subscription is in a canceled state.
     *
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->stripe_status === 'canceled' || $this->ends_at !== null;
    }

    /**
     * Check if the subscription is past due.
     *
     * @return bool
     */
    public function isPastDue(): bool
    {
        return $this->stripe_status === 'past_due';
    }

    /**
     * Check if the subscription is unpaid.
     *
     * @return bool
     */
    public function isUnpaid(): bool
    {
        return $this->stripe_status === 'unpaid';
    }

    /**
     * Check if the subscription is incomplete.
     *
     * @return bool
     */
    public function isIncomplete(): bool
    {
        return $this->stripe_status === 'incomplete';
    }

    /**
     * Check if the subscription will be canceled at period end.
     *
     * @return bool
     */
    public function onGracePeriod(): bool
    {
        return $this->ends_at !== null &&
            $this->ends_at->isFuture() &&
            in_array($this->stripe_status, ['active', 'trialing']);
    }

    /**
     * Cancel the subscription at the end of the current billing period.
     *
     * @return $this
     * @throws \Exception
     */
    public function cancelAtPeriodEnd(): self
    {
        try {
            $stripe = $this->owner()->stripe();

            $stripe->subscriptions->update($this->stripe_id, [
                'cancel_at_period_end' => true,
            ]);

            $this->update([
                'ends_at' => $this->currentPeriodEnd(),
            ]);

            Log::info('Subscription canceled at period end', [
                'subscription_id' => $this->id,
                'stripe_id' => $this->stripe_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to cancel subscription at period end', [
                'subscription_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        return $this;
    }

    /**
     * Cancel the subscription immediately.
     *
     * @return $this
     * @throws \Exception
     */
    public function cancelImmediately(): self
    {
        try {
            $stripe = $this->owner()->stripe();

            $stripe->subscriptions->cancel($this->stripe_id);

            $this->update([
                'stripe_status' => 'canceled',
                'ends_at' => now(),
            ]);

            Log::info('Subscription canceled immediately', [
                'subscription_id' => $this->id,
                'stripe_id' => $this->stripe_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to cancel subscription immediately', [
                'subscription_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        return $this;
    }

    /**
     * Resume a canceled subscription that hasn't ended yet.
     *
     * @return $this
     * @throws \Exception
     */
    public function resume(): self
    {
        try {
            if (!$this->onGracePeriod()) {
                throw new \Exception('Cannot resume a subscription that is not within its grace period.');
            }

            $stripe = $this->owner()->stripe();

            $stripe->subscriptions->update($this->stripe_id, [
                'cancel_at_period_end' => false,
            ]);

            $this->update([
                'ends_at' => null,
            ]);

            Log::info('Subscription resumed', [
                'subscription_id' => $this->id,
                'stripe_id' => $this->stripe_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to resume subscription', [
                'subscription_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        return $this;
    }

    /**
     * Renew a subscription by extending the end date.
     * Note: This is typically handled automatically by Stripe's billing cycle,
     * but this method can be used for manual renewals or grace periods.
     *
     * @return $this
     */
    public function renew(): self
    {
        // For Stripe, renewal happens automatically
        // This method is mainly for tracking and logging purposes
        Log::info('Subscription renewal processed', [
            'subscription_id' => $this->id,
            'stripe_id' => $this->stripe_id,
        ]);

        return $this;
    }

    /**
     * Get the next billing date.
     *
     * @return \Carbon\Carbon|null
     */
    public function nextBillingDate(): ?\Carbon\Carbon
    {
        if ($this->isCanceled() || $this->isExpired()) {
            return null;
        }

        if ($this->onTrial()) {
            return $this->trial_ends_at;
        }

        return $this->currentPeriodEnd();
    }

    /**
     * Get the current period end date.
     *
     * @param DateTimeZone|string|int|null $timezone
     * @return \Carbon\CarbonInterface|null
     */
    public function currentPeriodEnd(DateTimeZone|string|int|null $timezone = null): ?\Carbon\CarbonInterface
    {
        if ($this->ends_at) {
            return $this->ends_at;
        }

        // Fallback: calculate based on plan billing interval
        if ($this->starts_at && $this->plan) {
            $interval = $this->plan->billing_interval ?? 'monthly';

            return match ($interval) {
                'yearly' => $this->starts_at->copy()->addYear(),
                'monthly' => $this->starts_at->copy()->addMonth(),
                default => $this->starts_at->copy()->addMonth(),
            };
        }

        return null;
    }

    /**
     * Update the subscription's Stripe status.
     *
     * @param string $status
     * @return $this
     */
    public function updateStatus(string $status): self
    {
        $this->update(['stripe_status' => $status]);

        Log::info('Subscription status updated', [
            'subscription_id' => $this->id,
            'stripe_id' => $this->stripe_id,
            'status' => $status,
        ]);

        return $this;
    }

    /**
     * Mark the subscription as past due.
     *
     * @return $this
     */
    public function markAsPastDue(): self
    {
        return $this->updateStatus('past_due');
    }

    /**
     * Mark the subscription as unpaid.
     *
     * @return $this
     */
    public function markAsUnpaid(): self
    {
        return $this->updateStatus('unpaid');
    }

    /**
     * Get the subscription plan associated with this subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
