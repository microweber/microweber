<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NewsletterSubscriberListTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_subscriber_list()
    {
        $data = [
            'subscriber_id' => 1,
            'list_id' => 1,
        ];

        $subscriberList = NewsletterSubscriberList::create($data);

        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'id' => $subscriberList->id,
            'subscriber_id' => 1,
            'list_id' => 1,
        ]);
    }

    #[Test]
    public function it_has_factory()
    {
        $subscriberList = NewsletterSubscriberList::factory()->create();
        $this->assertInstanceOf(NewsletterSubscriberList::class, $subscriberList);
        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'id' => $subscriberList->id,
        ]);
    }

    #[Test]
    public function it_has_campaigns_send_log_relationship()
    {
        $subscriberList = NewsletterSubscriberList::factory()->create();
        $sendLog = NewsletterCampaignsSendLog::factory()->create([
            'subscriber_id' => $subscriberList->subscriber_id,
        ]);

        $this->assertTrue($subscriberList->campaignsSendLog->contains($sendLog));
    }
}