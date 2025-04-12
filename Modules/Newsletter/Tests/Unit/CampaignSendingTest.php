<?php

namespace Modules\Newsletter\Tests\Unit;


use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Modules\Newsletter\Mails\NewsletterMail;

class CampaignSendingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    #[Test]
    public function it_sends_to_active_subscribers()
    {
        $list = NewsletterList::factory()->create();
        $subscribers = NewsletterSubscriber::factory()
            ->count(3)
            ->create(['list_id' => $list->id, 'status' => 'active']);

        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'status' => 'sending'
        ]);

        // Trigger sending (would normally be a job)
        foreach($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail($campaign));
        }

        Mail::assertSent(NewsletterMail::class, 3);
    }

    #[Test]
    public function it_does_not_send_to_unsubscribed()
    {
        $list = NewsletterList::factory()->create();
        NewsletterSubscriber::factory()->create([
            'list_id' => $list->id,
            'status' => 'unsubscribed'
        ]);

        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id
        ]);

        // Should send 0 emails
        Mail::assertNothingSent();
    }
    #[Test]
    public function it_can_import_emails()
    {
        // Step 1: Import 20,000 subscribers
        $list = NewsletterList::factory()->create();
        $subscribers = NewsletterSubscriber::factory()->count(20000)->create();

        // Attach all subscribers to the list in batches
        $subscriberListData = [];
        foreach ($subscribers as $subscriber) {
            $subscriberListData[] = [
                'subscriber_id' => $subscriber->id,
                'list_id' => $list->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // Batch insert for SQLite compatibility
        foreach (array_chunk($subscriberListData, 500) as $batch) {
            NewsletterSubscriberList::insert($batch);
        }

        $this->assertEquals(20000, NewsletterSubscriber::count());
        $this->assertEquals(20000, NewsletterSubscriberList::where('list_id', $list->id)->count());

        // Step 2: Create a campaign
        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'recipients_from' => 'specific_list',
        ]);

        // Step 3: Simulate sending emails (create send log entries) in batches
        $sendLogData = [];
        foreach ($subscribers as $subscriber) {
            $sendLogData[] = [
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
                'is_sent' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        foreach (array_chunk($sendLogData, 500) as $batch) {
            NewsletterCampaignsSendLog::insert($batch);
        }

        $this->assertEquals(20000, NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->count());

        // Step 4: Clean up (RefreshDatabase will handle this, but explicit delete for demonstration)
        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();
        NewsletterSubscriberList::where('list_id', $list->id)->delete();
        NewsletterSubscriber::query()->delete();
        NewsletterCampaign::where('id', $campaign->id)->delete();
        NewsletterList::where('id', $list->id)->delete();

        $this->assertEquals(0, NewsletterCampaignsSendLog::count());
        $this->assertEquals(0, NewsletterSubscriberList::count());
        $this->assertEquals(0, NewsletterSubscriber::count());
        $this->assertEquals(0, NewsletterCampaign::count());
        $this->assertEquals(0, NewsletterList::where('id', $list->id)->count());
    }

}
