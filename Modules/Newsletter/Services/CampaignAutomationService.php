<?php

namespace Modules\Newsletter\Services;

use Illuminate\Support\Facades\Log;
use Modules\Cart\Models\Cart;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Order\Models\Order;

class CampaignAutomationService
{
    /**
     * Trigger a campaign by event name.
     *
     * @param string $event The trigger event name (e.g., 'cart_abandoned', 'order_placed')
     * @param array $data Event data including email, user data, etc.
     * @return array Array of queued automation queue records
     */
    public function trigger(string $event, array $data): array
    {
        $email = $data['email'] ?? null;
        if (!$email) {
            Log::warning('CampaignAutomationService: No email provided for event', ['event' => $event]);
            return [];
        }

        // Find active triggered campaigns for this event
        $campaigns = NewsletterCampaign::where('campaign_type', NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED)
            ->where('trigger_event', $event)
            ->where('is_active', true)
            ->get();

        if ($campaigns->isEmpty()) {
            return [];
        }

        $queued = [];
        foreach ($campaigns as $campaign) {
            // Check if conditions match
            if (!$this->matchesConditions($campaign, $data)) {
                continue;
            }

            // Check if subscriber exists, or create one if needed
            $subscriber = $this->getOrCreateSubscriber($email, $data);

            // Check for duplicate pending emails for same campaign/email
            if ($this->hasPendingEmail($campaign->id, $email)) {
                continue;
            }

            // Schedule the email
            $scheduledAt = now()->addMinutes($campaign->delay_minutes ?? 0);

            $queueItem = NewsletterAutomationQueue::create([
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber?->id,
                'email' => $email,
                'trigger_event' => $event,
                'event_data' => $data,
                'scheduled_at' => $scheduledAt,
                'status' => NewsletterAutomationQueue::STATUS_PENDING,
            ]);

            $queued[] = $queueItem;

            Log::info('CampaignAutomationService: Queued triggered email', [
                'campaign_id' => $campaign->id,
                'event' => $event,
                'email' => $email,
                'scheduled_at' => $scheduledAt,
            ]);
        }

        return $queued;
    }

    /**
     * Check if the event data matches campaign trigger conditions.
     *
     * @param NewsletterCampaign $campaign
     * @param array $data
     * @return bool
     */
    protected function matchesConditions(NewsletterCampaign $campaign, array $data): bool
    {
        $conditions = $campaign->trigger_conditions;
        if (!$conditions) {
            return true;
        }

        // Support both array and JSON string
        if (is_string($conditions)) {
            $conditions = json_decode($conditions, true);
        }

        if (!is_array($conditions) || empty($conditions)) {
            return true;
        }

        // Evaluate conditions
        foreach ($conditions as $condition) {
            $field = $condition['field'] ?? null;
            $operator = $condition['operator'] ?? 'equals';
            $value = $condition['value'] ?? null;

            if (!$field) {
                continue;
            }

            $actualValue = $data[$field] ?? null;

            if (!$this->evaluateCondition($actualValue, $operator, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Evaluate a single condition.
     *
     * @param mixed $actualValue
     * @param string $operator
     * @param mixed $expectedValue
     * @return bool
     */
    protected function evaluateCondition($actualValue, string $operator, $expectedValue): bool
    {
        switch ($operator) {
            case 'equals':
                return $actualValue == $expectedValue;
            case 'not_equals':
                return $actualValue != $expectedValue;
            case 'greater_than':
                return $actualValue > $expectedValue;
            case 'less_than':
                return $actualValue < $expectedValue;
            case 'contains':
                return is_string($actualValue) && str_contains($actualValue, $expectedValue);
            case 'not_contains':
                return is_string($actualValue) && !str_contains($actualValue, $expectedValue);
            case 'in':
                return is_array($expectedValue) && in_array($actualValue, $expectedValue);
            case 'not_in':
                return is_array($expectedValue) && !in_array($actualValue, $expectedValue);
            default:
                return true;
        }
    }

    /**
     * Get existing subscriber or create a new one.
     *
     * @param string $email
     * @param array $data
     * @return NewsletterSubscriber|null
     */
    protected function getOrCreateSubscriber(string $email, array $data): ?NewsletterSubscriber
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if (!$subscriber) {
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'name' => $data['first_name'] ?? ($data['name'] ?? null),
                'status' => 'subscribed',
            ]);
        }

        return $subscriber;
    }

    /**
     * Check if there's already a pending email for this campaign and email.
     *
     * @param int $campaignId
     * @param string $email
     * @return bool
     */
    protected function hasPendingEmail(int $campaignId, string $email): bool
    {
        return NewsletterAutomationQueue::where('campaign_id', $campaignId)
            ->where('email', $email)
            ->where('status', NewsletterAutomationQueue::STATUS_PENDING)
            ->exists();
    }

    /**
     * Cancel pending emails for a specific email/campaign combination.
     *
     * @param string $email
     * @param string|null $event Specific event to cancel, or null for all
     * @param int|null $campaignId Specific campaign to cancel, or null for all
     * @return int Number of canceled items
     */
    public function cancelPendingEmails(string $email, ?string $event = null, ?int $campaignId = null): int
    {
        $query = NewsletterAutomationQueue::where('email', $email)
            ->where('status', NewsletterAutomationQueue::STATUS_PENDING);

        if ($event) {
            $query->where('trigger_event', $event);
        }

        if ($campaignId) {
            $query->where('campaign_id', $campaignId);
        }

        $count = $query->count();
        $query->update(['status' => NewsletterAutomationQueue::STATUS_CANCELED]);

        Log::info('CampaignAutomationService: Canceled pending emails', [
            'email' => $email,
            'event' => $event,
            'campaign_id' => $campaignId,
            'count' => $count,
        ]);

        return $count;
    }

    /**
     * Trigger abandoned cart email for a user.
     *
     * @param string $email
     * @param Cart $cart
     * @param array $additionalData
     * @return array
     */
    public function triggerAbandonedCart(string $email, Cart $cart, array $additionalData = []): array
    {
        $data = array_merge([
            'email' => $email,
            'cart_id' => $cart->id,
            'cart_total' => $this->calculateCartTotal($cart),
            'item_count' => $this->getCartItemCount($cart),
            'cart_items' => $this->getCartItems($cart),
        ], $additionalData);

        return $this->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, $data);
    }

    /**
     * Trigger order confirmation email.
     *
     * @param Order $order
     * @return array
     */
    public function triggerOrderPlaced(Order $order): array
    {
        $data = [
            'email' => $order->email,
            'order_id' => $order->id,
            'order_reference' => $order->order_reference_id,
            'order_total' => $order->amount,
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
        ];

        // Cancel any pending abandoned cart emails
        $this->cancelPendingEmails($order->email, NewsletterCampaign::TRIGGER_CART_ABANDONED);

        return $this->trigger(NewsletterCampaign::TRIGGER_ORDER_PLACED, $data);
    }

    /**
     * Trigger order paid email.
     *
     * @param Order $order
     * @return array
     */
    public function triggerOrderPaid(Order $order): array
    {
        $data = [
            'email' => $order->email,
            'order_id' => $order->id,
            'order_reference' => $order->order_reference_id,
            'order_total' => $order->amount,
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
        ];

        return $this->trigger(NewsletterCampaign::TRIGGER_ORDER_PAID, $data);
    }

    /**
     * Calculate cart total.
     *
     * @param Cart $cart
     * @return float
     */
    protected function calculateCartTotal(Cart $cart): float
    {
        return app(\Modules\Cart\Repositories\CartManager::class)->sum($cart->session_id);
    }

    /**
     * Get cart item count.
     *
     * @param Cart $cart
     * @return int
     */
    protected function getCartItemCount(Cart $cart): int
    {
        return Cart::where('session_id', $cart->session_id)
            ->where('order_completed', 0)
            ->count();
    }

    /**
     * Get cart items for email.
     *
     * @param Cart $cart
     * @return array
     */
    protected function getCartItems(Cart $cart): array
    {
        $items = Cart::where('session_id', $cart->session_id)
            ->where('order_completed', 0)
            ->get();

        return $items->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'qty' => $item->qty,
                'price' => $item->price,
                'image' => $item->item_image,
                'link' => $item->link,
            ];
        })->toArray();
    }
}
