<?php

namespace Modules\Newsletter\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterSubscriber;

class NewsletterAutomationQueueFactory extends Factory
{
    protected $model = NewsletterAutomationQueue::class;

    public function definition()
    {
        return [
            'campaign_id' => NewsletterCampaign::factory(),
            'subscriber_id' => NewsletterSubscriber::factory(),
            'email' => $this->faker->safeEmail,
            'trigger_event' => 'cart_abandoned',
            'event_data' => ['cart_total' => $this->faker->randomFloat(2, 10, 500)],
            'scheduled_at' => now()->addMinutes(60),
            'sent_at' => null,
            'status' => NewsletterAutomationQueue::STATUS_PENDING,
            'error_message' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function sent()
    {
        return $this->state([
            'status' => NewsletterAutomationQueue::STATUS_SENT,
            'sent_at' => now(),
        ]);
    }

    public function failed()
    {
        return $this->state([
            'status' => NewsletterAutomationQueue::STATUS_FAILED,
            'error_message' => 'Test failure message',
        ]);
    }

    public function canceled()
    {
        return $this->state([
            'status' => NewsletterAutomationQueue::STATUS_CANCELED,
        ]);
    }

    public function forCartAbandoned()
    {
        return $this->state([
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
        ]);
    }

    public function forOrderPlaced()
    {
        return $this->state([
            'trigger_event' => NewsletterCampaign::TRIGGER_ORDER_PLACED,
        ]);
    }

    public function forUserRegistered()
    {
        return $this->state([
            'trigger_event' => NewsletterCampaign::TRIGGER_USER_REGISTERED,
        ]);
    }

    public function scheduledForPast()
    {
        return $this->state([
            'scheduled_at' => now()->subHour(),
        ]);
    }

    public function scheduledForFuture()
    {
        return $this->state([
            'scheduled_at' => now()->addHour(),
        ]);
    }
}
