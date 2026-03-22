<?php

namespace Modules\Newsletter\Listeners;

use Illuminate\Events\Dispatcher;
use Modules\Cart\Events\AddToCartEvent;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Services\AbandonedCartService;
use Modules\Newsletter\Services\CampaignAutomationService;
use Modules\Order\Events\OrderWasCreated;
use Modules\Order\Events\OrderWasPaid;

class NewsletterAutomationSubscriber
{
    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        // Listen for cart events
        $events->listen(
            AddToCartEvent::class,
            [self::class, 'handleAddToCart']
        );

        // Listen for order events
        $events->listen(
            OrderWasCreated::class,
            [self::class, 'handleOrderCreated']
        );

        $events->listen(
            OrderWasPaid::class,
            [self::class, 'handleOrderPaid']
        );
    }

    /**
     * Handle add to cart event.
     *
     * @param AddToCartEvent $event
     * @return void
     */
    public function handleAddToCart(AddToCartEvent $event): void
    {
        // Update cart activity timestamp
        // This helps with abandoned cart detection
        $cart = $event->cart;
        if ($cart) {
            $cart->touch();
        }
    }

    /**
     * Handle order created event.
     *
     * @param OrderWasCreated $event
     * @return void
     */
    public function handleOrderCreated(OrderWasCreated $event): void
    {
        $order = $event->order;
        if (!$order || !$order->email) {
            return;
        }

        // Mark cart as recovered and cancel pending abandoned cart emails
        $abandonedCartService = app(AbandonedCartService::class);
        $abandonedCartService->markCartAsRecovered($order->session_id);

        // Trigger order placed campaign
        $automationService = app(CampaignAutomationService::class);
        $automationService->triggerOrderPlaced($order);
    }

    /**
     * Handle order paid event.
     *
     * @param OrderWasPaid $event
     * @return void
     */
    public function handleOrderPaid(OrderWasPaid $event): void
    {
        $order = $event->order;
        if (!$order || !$order->email) {
            return;
        }

        // Trigger order paid campaign
        $automationService = app(CampaignAutomationService::class);
        $automationService->triggerOrderPaid($order);
    }
}
