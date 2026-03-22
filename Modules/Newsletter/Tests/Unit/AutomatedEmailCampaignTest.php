<?php

namespace Modules\Newsletter\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Services\CampaignAutomationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AutomatedEmailCampaignTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_triggered_campaign_with_automation_fields()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'is_active' => true,
            'trigger_conditions' => ['cart_total' => ['operator' => 'greater_than', 'value' => 50]],
        ]);

        $this->assertDatabaseHas('newsletter_campaigns', [
            'id' => $campaign->id,
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'is_active' => true,
        ]);
    }

    #[Test]
    public function it_can_queue_triggered_email_for_cart_abandonment()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'test-' . uniqid() . '@example.com';
        $data = [
            'email' => $uniqueEmail,
            'first_name' => 'John',
            'cart_id' => 123,
            'cart_total' => 100,
            'item_count' => 2,
        ];

        $result = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, $data);

        $this->assertCount(1, $result);
        $this->assertDatabaseHas('newsletter_automation_queue', [
            'campaign_id' => $campaign->id,
            'email' => $uniqueEmail,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'status' => NewsletterAutomationQueue::STATUS_PENDING,
        ]);
    }

    #[Test]
    public function it_schedules_email_with_delay()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 120,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'test-delay-' . uniqid() . '@example.com';
        $data = [
            'email' => $uniqueEmail,
            'cart_total' => 100,
        ];

        $result = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, $data);

        $queueItem = $result[0];
        $this->assertTrue($queueItem->scheduled_at->isFuture());
        $this->assertTrue($queueItem->scheduled_at->diffInMinutes(now()) >= 119);
    }

    #[Test]
    public function it_does_not_trigger_inactive_campaigns()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'is_active' => false,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'test-inactive-' . uniqid() . '@example.com';
        $data = [
            'email' => $uniqueEmail,
            'cart_total' => 100,
        ];

        $result = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, $data);

        $this->assertEmpty($result);
    }

    #[Test]
    public function it_does_not_queue_duplicate_pending_emails()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'test-duplicate-' . uniqid() . '@example.com';
        $data = [
            'email' => $uniqueEmail,
            'cart_total' => 100,
        ];

        // First trigger
        $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, $data);

        // Second trigger should not create duplicate
        $result = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, $data);

        $this->assertEmpty($result);
        $this->assertEquals(1, NewsletterAutomationQueue::where('email', $uniqueEmail)->count());
    }

    #[Test]
    public function it_matches_trigger_conditions_correctly()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'is_active' => true,
            'trigger_conditions' => [
                ['field' => 'cart_total', 'operator' => 'greater_than', 'value' => 50],
            ],
        ]);

        $service = app(CampaignAutomationService::class);

        // Should match - cart total > 50
        $uniqueEmail1 = 'test-match-' . uniqid() . '@example.com';
        $result1 = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, [
            'email' => $uniqueEmail1,
            'cart_total' => 100,
        ]);
        $this->assertCount(1, $result1);

        // Should not match - cart total < 50
        $uniqueEmail2 = 'test-nomatch-' . uniqid() . '@example.com';
        $result2 = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, [
            'email' => $uniqueEmail2,
            'cart_total' => 25,
        ]);
        $this->assertEmpty($result2);
    }

    #[Test]
    public function it_can_cancel_pending_emails()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'test-cancel-' . uniqid() . '@example.com';
        $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, [
            'email' => $uniqueEmail,
            'cart_total' => 100,
        ]);

        $canceledCount = $service->cancelPendingEmails($uniqueEmail);

        $this->assertEquals(1, $canceledCount);
        $this->assertDatabaseHas('newsletter_automation_queue', [
            'email' => $uniqueEmail,
            'status' => NewsletterAutomationQueue::STATUS_CANCELED,
        ]);
    }

    #[Test]
    public function it_creates_or_reuses_subscriber_on_trigger()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'newuser-' . uniqid() . '@example.com';
        $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, [
            'email' => $uniqueEmail,
            'first_name' => 'New User',
            'cart_total' => 100,
        ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => $uniqueEmail,
            'name' => 'New User',
            'status' => 'subscribed',
        ]);
    }

    #[Test]
    public function it_triggers_multiple_campaigns_for_same_event()
    {
        $campaign1 = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 60,
            'is_active' => true,
        ]);

        $campaign2 = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 1440,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'test-multi-' . uniqid() . '@example.com';
        $result = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, [
            'email' => $uniqueEmail,
            'cart_total' => 100,
        ]);

        $this->assertCount(2, $result);
    }

    #[Test]
    public function it_returns_empty_array_when_no_email_provided()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $result = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, [
            'cart_total' => 100,
        ]);

        $this->assertEmpty($result);
    }

    #[Test]
    public function it_supports_order_placed_trigger()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_ORDER_PLACED,
            'delay_minutes' => 0,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'test-order-' . uniqid() . '@example.com';
        $result = $service->trigger(NewsletterCampaign::TRIGGER_ORDER_PLACED, [
            'email' => $uniqueEmail,
            'order_id' => 456,
            'order_total' => 150,
        ]);

        $this->assertCount(1, $result);
    }

    #[Test]
    public function it_supports_user_registered_trigger()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_USER_REGISTERED,
            'delay_minutes' => 0,
            'is_active' => true,
        ]);

        $service = app(CampaignAutomationService::class);
        $uniqueEmail = 'test-reg-' . uniqid() . '@example.com';
        $result = $service->trigger(NewsletterCampaign::TRIGGER_USER_REGISTERED, [
            'email' => $uniqueEmail,
            'first_name' => 'John',
        ]);

        $this->assertCount(1, $result);
    }

    #[Test]
    public function it_allows_manual_status_updates()
    {
        $queueItem = NewsletterAutomationQueue::factory()->create([
            'status' => NewsletterAutomationQueue::STATUS_PENDING,
        ]);

        $queueItem->markAsSent();

        $this->assertEquals(NewsletterAutomationQueue::STATUS_SENT, $queueItem->fresh()->status);
        $this->assertNotNull($queueItem->fresh()->sent_at);
    }

    #[Test]
    public function it_can_mark_as_failed()
    {
        $queueItem = NewsletterAutomationQueue::factory()->create([
            'status' => NewsletterAutomationQueue::STATUS_PENDING,
        ]);

        $queueItem->markAsFailed('Test error message');

        $this->assertEquals(NewsletterAutomationQueue::STATUS_FAILED, $queueItem->fresh()->status);
        $this->assertEquals('Test error message', $queueItem->fresh()->error_message);
    }

    #[Test]
    public function it_scopes_pending_items_correctly()
    {
        // Create unique emails for isolation
        $uniqueEmail1 = 'test-pending-' . uniqid() . '@example.com';
        $uniqueEmail2 = 'test-sent-' . uniqid() . '@example.com';
        $uniqueEmail3 = 'test-future-' . uniqid() . '@example.com';

        $pending = NewsletterAutomationQueue::factory()->create([
            'email' => $uniqueEmail1,
            'status' => NewsletterAutomationQueue::STATUS_PENDING,
            'scheduled_at' => now()->subHour(),
        ]);

        $sent = NewsletterAutomationQueue::factory()->create([
            'email' => $uniqueEmail2,
            'status' => NewsletterAutomationQueue::STATUS_SENT,
            'scheduled_at' => now()->subHour(),
        ]);

        $future = NewsletterAutomationQueue::factory()->create([
            'email' => $uniqueEmail3,
            'status' => NewsletterAutomationQueue::STATUS_PENDING,
            'scheduled_at' => now()->addHour(),
        ]);

        $readyToSend = NewsletterAutomationQueue::readyToSend()->get();

        $this->assertCount(1, $readyToSend);
        $this->assertEquals($pending->id, $readyToSend->first()->id);
    }

    #[Test]
    public function it_filters_by_event_type()
    {
        $uniqueEmail1 = 'test-abandoned-' . uniqid() . '@example.com';
        $uniqueEmail2 = 'test-order-' . uniqid() . '@example.com';

        $abandoned = NewsletterAutomationQueue::factory()->create([
            'email' => $uniqueEmail1,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
        ]);

        $orderPlaced = NewsletterAutomationQueue::factory()->create([
            'email' => $uniqueEmail2,
            'trigger_event' => NewsletterCampaign::TRIGGER_ORDER_PLACED,
        ]);

        $abandonedItems = NewsletterAutomationQueue::byEvent(NewsletterCampaign::TRIGGER_CART_ABANDONED)->get();

        $this->assertCount(1, $abandonedItems);
        $this->assertEquals($abandoned->id, $abandonedItems->first()->id);
    }
}
