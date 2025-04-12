<?php

namespace Modules\Newsletter\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsletterCampaignTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_campaign()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'subject' => 'Test Campaign',
            'status' => 'draft'
        ]);

        $this->assertDatabaseHas('newsletter_campaigns', [
            'id' => $campaign->id,
            'subject' => 'Test Campaign'
        ]);
    }

    #[Test]
    public function it_can_change_status()
    {
        $campaign = NewsletterCampaign::factory()->create(['status' => 'draft']);
        
        $campaign->update(['status' => 'sending']);
        
        $this->assertEquals('sending', $campaign->fresh()->status);
    }

    #[Test]
    public function it_returns_list_relationship_and_list_name()
    {
        $list = NewsletterList::factory()->create(['name' => 'VIP List']);
        $campaign = NewsletterCampaign::factory()->create(['list_id' => $list->id]);

        $this->assertInstanceOf(NewsletterList::class, $campaign->list);
        $this->assertEquals('VIP List', $campaign->listName());
    }

    #[Test]
    public function it_returns_sender_account_relationship()
    {
        $sender = NewsletterSenderAccount::factory()->create();
        $campaign = NewsletterCampaign::factory()->create(['sender_account_id' => $sender->id]);

        $this->assertInstanceOf(NewsletterSenderAccount::class, $campaign->senderAccount);
        $this->assertEquals($sender->id, $campaign->senderAccount->id);
    }

    #[Test]
    public function it_counts_subscribers_for_specific_list()
    {
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'recipients_from' => 'specific_list'
        ]);
        NewsletterSubscriberList::factory()->count(3)->create(['list_id' => $list->id]);

        $this->assertEquals(3, $campaign->countSubscribers());
    }

    #[Test]
    public function it_counts_all_subscribers_when_not_specific_list()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'recipients_from' => 'all'
        ]);
        NewsletterSubscriber::factory()->count(5)->create();

        $this->assertEquals(5, $campaign->countSubscribers());
    }

    #[Test]
    public function it_returns_get_subscribers_attribute()
    {
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create(['list_id' => $list->id]);
        NewsletterSubscriberList::factory()->count(2)->create(['list_id' => $list->id]);

        $this->assertEquals(2, $campaign->subscribers);
    }

    #[Test]
    public function it_returns_opened_and_clicked_attributes()
    {
        $campaign = NewsletterCampaign::factory()->create();
        NewsletterCampaignPixel::factory()->count(4)->create(['campaign_id' => $campaign->id]);
        NewsletterCampaignClickedLink::factory()->count(2)->create(['campaign_id' => $campaign->id]);

        $this->assertEquals(4, $campaign->opened);
        $this->assertEquals(2, $campaign->clicked);
    }

    #[Test]
    public function it_returns_default_status_if_not_set()
    {
        $campaign = NewsletterCampaign::factory()->make();
        unset($campaign->status);

        $this->assertEquals(NewsletterCampaign::STATUS_DRAFT, $campaign->status);
    }

    #[Test]
    public function it_can_mark_as_finished()
    {
        $campaign = NewsletterCampaign::factory()->create(['status' => 'processing', 'is_done' => 0]);
        NewsletterCampaign::markAsFinished($campaign->id);

        $campaign->refresh();
        $this->assertEquals(NewsletterCampaign::STATUS_FINISHED, $campaign->status);
        $this->assertEquals(1, $campaign->is_done);
    }
}