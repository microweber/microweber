<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Modules\Newsletter\Jobs\ProcessTriggeredEmail;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Services\CampaignAutomationService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProcessAutomationQueueCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        NewsletterAutomationQueue::query()->delete();
        NewsletterSubscriber::query()->delete();
        NewsletterCampaign::where('campaign_type', NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED)->delete();
    }

    #[Test]
    public function it_processes_a_triggered_cart_abandoned_campaign_from_queue(): void
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'delay_minutes' => 0,
            'is_active' => true,
            'name' => 'Abandoned cart recovery',
        ]);

        $service = app(CampaignAutomationService::class);
        $queuedItems = $service->trigger(NewsletterCampaign::TRIGGER_CART_ABANDONED, [
            'email' => 'cart-user@example.com',
            'first_name' => 'Cart',
            'cart_total' => 149.99,
            'item_count' => 2,
        ]);

        $this->assertCount(1, $queuedItems);

        $queueItem = $queuedItems[0];
        $queueItem->update([
            'scheduled_at' => now()->subMinute(),
        ]);

        $result = Artisan::call('newsletter:process-automation-queue');

        $this->assertSame(0, $result);
        $this->assertDatabaseHas('newsletter_automation_queue', [
            'id' => $queueItem->id,
            'campaign_id' => $campaign->id,
            'status' => NewsletterAutomationQueue::STATUS_PENDING,
        ]);

        Queue::assertPushed(ProcessTriggeredEmail::class, function (ProcessTriggeredEmail $job) use ($queueItem): bool {
            return $job->queueItemId === $queueItem->id;
        });
    }

    #[Test]
    public function it_marks_queue_items_as_failed_when_the_triggered_campaign_is_inactive(): void
    {
        $campaign = NewsletterCampaign::factory()->create([
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_TRIGGERED,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'is_active' => false,
        ]);

        $queueItem = NewsletterAutomationQueue::factory()
            ->forCartAbandoned()
            ->scheduledForPast()
            ->create([
                'campaign_id' => $campaign->id,
                'email' => 'inactive-campaign@example.com',
                'status' => NewsletterAutomationQueue::STATUS_PENDING,
            ]);

        $result = Artisan::call('newsletter:process-automation-queue');

        $this->assertSame(0, $result);

        $queueItem->refresh();

        $this->assertSame(NewsletterAutomationQueue::STATUS_FAILED, $queueItem->status);
        $this->assertSame('Campaign is inactive', $queueItem->error_message);

        Queue::assertNothingPushed();
    }
}
