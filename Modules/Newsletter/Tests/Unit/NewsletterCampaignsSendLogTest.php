<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterSubscriber;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterCampaignsSendLogTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_campaigns_send_log()
    {
        $subscriber = NewsletterSubscriber::factory()->create();
        $data = [
            'campaign_id' => 1,
            'subscriber_id' => $subscriber->id,
            'is_sent' => 1,
        ];

        $log = NewsletterCampaignsSendLog::create($data);

        $this->assertDatabaseHas('newsletter_campaigns_send_log', [
            'id' => $log->id,
            'campaign_id' => 1,
            'subscriber_id' => $subscriber->id,
            'is_sent' => 1,
        ]);
    }

    #[Test]
    public function it_has_subscriber_relationship()
    {
        $subscriber = NewsletterSubscriber::factory()->create();
        $log = NewsletterCampaignsSendLog::factory()->create([
            'subscriber_id' => $subscriber->id,
        ]);

        $this->assertInstanceOf(NewsletterSubscriber::class, $log->subscriber);
        $this->assertEquals($subscriber->id, $log->subscriber->id);
    }

    #[Test]
    public function it_has_factory()
    {
        $log = NewsletterCampaignsSendLog::factory()->create();
        $this->assertInstanceOf(NewsletterCampaignsSendLog::class, $log);
        $this->assertDatabaseHas('newsletter_campaigns_send_log', [
            'id' => $log->id,
        ]);
    }
}