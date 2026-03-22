<?php

namespace Modules\Newsletter\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Cart\Models\Cart;
use Modules\Newsletter\Models\NewsletterCampaign;

class AbandonedCartService
{
    /**
     * Default delay before a cart is considered abandoned (in minutes).
     */
    protected int $abandonedDelayMinutes = 60;

    /**
     * Find abandoned carts that haven't been emailed yet.
     *
     * @param int|null $delayMinutes Override default delay
     * @return array Array of abandoned cart data with email and cart info
     */
    public function findAbandonedCarts(?int $delayMinutes = null): array
    {
        $delay = $delayMinutes ?? $this->abandonedDelayMinutes;
        $cutoffTime = Carbon::now()->subMinutes($delay);

        // Find carts that have been inactive for the delay period
        // These are carts where the last activity (updated_at) is older than the delay
        // and they haven't been converted to orders
        $abandonedCarts = Cart::where('order_completed', 0)
            ->whereNull('order_id')
            ->where('updated_at', '<', $cutoffTime)
            ->whereNotExists(function ($query) {
                $query->select('id')
                    ->from('newsletter_automation_queue')
                    ->whereColumn('newsletter_automation_queue.event_data->cart_id', 'cart.id')
                    ->where('trigger_event', NewsletterCampaign::TRIGGER_CART_ABANDONED)
                    ->whereIn('status', ['pending', 'sent']);
            })
            ->select('session_id', 'email', 'id', 'updated_at', 'created_at')
            ->groupBy('session_id')
            ->get();

        $results = [];
        foreach ($abandonedCarts as $cart) {
            // Skip if no email associated with this cart
            // Email might be stored in session or user profile
            $email = $this->getCartEmail($cart);
            if (!$email) {
                continue;
            }

            $cartData = $this->getCartData($cart->session_id);
            if (empty($cartData['items'])) {
                continue;
            }

            $results[] = [
                'cart' => $cart,
                'email' => $email,
                'session_id' => $cart->session_id,
                'items' => $cartData['items'],
                'total' => $cartData['total'],
                'item_count' => $cartData['item_count'],
                'last_activity' => $cart->updated_at,
            ];
        }

        return $results;
    }

    /**
     * Process abandoned carts and trigger emails.
     *
     * @param int|null $delayMinutes
     * @return array Results of processing
     */
    public function processAbandonedCarts(?int $delayMinutes = null): array
    {
        $abandonedCarts = $this->findAbandonedCarts($delayMinutes);
        $results = [
            'processed' => 0,
            'triggered' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        if (empty($abandonedCarts)) {
            return $results;
        }

        $automationService = app(CampaignAutomationService::class);

        foreach ($abandonedCarts as $abandonedCart) {
            try {
                $results['processed']++;

                $triggered = $automationService->triggerAbandonedCart(
                    $abandonedCart['email'],
                    $abandonedCart['cart'],
                    [
                        'cart_total' => $abandonedCart['total'],
                        'item_count' => $abandonedCart['item_count'],
                        'cart_items' => $abandonedCart['items'],
                        'last_activity' => $abandonedCart['last_activity']->toIso8601String(),
                    ]
                );

                if (!empty($triggered)) {
                    $results['triggered']++;
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'email' => $abandonedCart['email'],
                    'session_id' => $abandonedCart['session_id'],
                    'error' => $e->getMessage(),
                ];

                Log::error('AbandonedCartService: Failed to process abandoned cart', [
                    'email' => $abandonedCart['email'],
                    'session_id' => $abandonedCart['session_id'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $results;
    }

    /**
     * Get email associated with a cart.
     *
     * @param Cart $cart
     * @return string|null
     */
    protected function getCartEmail(Cart $cart): ?string
    {
        // If cart has email directly
        if (!empty($cart->email)) {
            return $cart->email;
        }

        // Try to get from session data
        $sessionData = session($cart->session_id);
        if (is_array($sessionData) && !empty($sessionData['email'])) {
            return $sessionData['email'];
        }

        // Try to get from user if logged in
        if (auth()->check()) {
            return auth()->user()->email;
        }

        // Try to get from checkout data stored in session
        $checkoutData = session('checkout_data');
        if (is_array($checkoutData) && !empty($checkoutData['email'])) {
            return $checkoutData['email'];
        }

        return null;
    }

    /**
     * Get cart data for a session.
     *
     * @param string $sessionId
     * @return array
     */
    protected function getCartData(string $sessionId): array
    {
        $items = Cart::where('session_id', $sessionId)
            ->where('order_completed', 0)
            ->whereNull('order_id')
            ->get();

        $total = 0;
        $formattedItems = [];

        foreach ($items as $item) {
            $itemTotal = ($item->price ?? 0) * ($item->qty ?? 1);
            $total += $itemTotal;

            $formattedItems[] = [
                'id' => $item->id,
                'title' => $item->title,
                'qty' => $item->qty,
                'price' => $item->price,
                'total' => $itemTotal,
                'image' => $item->item_image,
                'link' => $item->link,
                'description' => $item->description,
            ];
        }

        return [
            'items' => $formattedItems,
            'total' => $total,
            'item_count' => count($formattedItems),
        ];
    }

    /**
     * Mark a cart as recovered (when user completes purchase).
     *
     * @param string $sessionId
     * @return void
     */
    public function markCartAsRecovered(string $sessionId): void
    {
        // Cancel any pending abandoned cart emails for this session
        $automationService = app(CampaignAutomationService::class);

        // Get email from session
        $email = null;
        $sessionData = session($sessionId);
        if (is_array($sessionData) && !empty($sessionData['email'])) {
            $email = $sessionData['email'];
        }

        if ($email) {
            $automationService->cancelPendingEmails($email, NewsletterCampaign::TRIGGER_CART_ABANDONED);
        }

        Log::info('AbandonedCartService: Cart marked as recovered', [
            'session_id' => $sessionId,
            'email' => $email,
        ]);
    }

    /**
     * Set the delay before a cart is considered abandoned.
     *
     * @param int $minutes
     * @return self
     */
    public function setAbandonedDelay(int $minutes): self
    {
        $this->abandonedDelayMinutes = $minutes;
        return $this;
    }

    /**
     * Get abandoned cart statistics.
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $cutoffTime = Carbon::now()->subMinutes($this->abandonedDelayMinutes);

        $totalActiveCarts = Cart::where('order_completed', 0)
            ->whereNull('order_id')
            ->distinct('session_id')
            ->count('session_id');

        $abandonedCarts = Cart::where('order_completed', 0)
            ->whereNull('order_id')
            ->where('updated_at', '<', $cutoffTime)
            ->distinct('session_id')
            ->count('session_id');

        $pendingRecoveryEmails = \Modules\Newsletter\Models\NewsletterAutomationQueue::where('trigger_event', NewsletterCampaign::TRIGGER_CART_ABANDONED)
            ->where('status', 'pending')
            ->count();

        $sentRecoveryEmails = \Modules\Newsletter\Models\NewsletterAutomationQueue::where('trigger_event', NewsletterCampaign::TRIGGER_CART_ABANDONED)
            ->where('status', 'sent')
            ->count();

        return [
            'total_active_carts' => $totalActiveCarts,
            'abandoned_carts' => $abandonedCarts,
            'pending_recovery_emails' => $pendingRecoveryEmails,
            'sent_recovery_emails' => $sentRecoveryEmails,
        ];
    }
}
