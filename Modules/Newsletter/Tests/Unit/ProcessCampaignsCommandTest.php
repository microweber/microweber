<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Mail;
use Modules\Newsletter\Console\Commands\ProcessCampaigns;
use Modules\Newsletter\Jobs\ProcessCampaignSubscriber;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Newsletter\Models\NewsletterTemplate;
use Tests\TestCase;

class ProcessCampaignsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        Mail::fake();
        config(['queue.default' => 'database']);
    }

    /** @test */
    public function it_marks_scheduled_campaign_as_pending_and_processes_it()
    {
        $sender = NewsletterSenderAccount::factory()->create();
        $template = NewsletterTemplate::factory()->create();
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'status' => NewsletterCampaign::STATUS_SCHEDULED,
            'scheduled_at' => now()->subMinute(),
            'scheduled_timezone' => config('app.timezone', 'UTC'),
            'list_id' => $list->id,
            'sender_account_id' => $sender->id,
            'email_content_type' => 'design',
            'email_template_id' => $template->id,
        ]);
        $subscriber = NewsletterSubscriber::factory()->create();
        $subscriberList = NewsletterSubscriberList::factory()->create([
            'list_id' => $list->id,
            'subscriber_id' => $subscriber->id,
        ]);

        Artisan::call('newsletter:process-campaigns');

        $campaign->refresh();

        // Run again to process the pending campaign
        Artisan::call('newsletter:process-campaigns');
        $campaign->refresh();
        $this->assertEquals(NewsletterCampaign::STATUS_PROCESSING, $campaign->status);

        Queue::assertPushed(ProcessCampaignSubscriber::class, function ($job) use ($subscriber, $campaign) {
            return $job->subscriberId === $subscriber->id && $job->campaignId === $campaign->id;
        });

        Mail::assertNothingSent();

        // Cleanup
        $subscriberList->delete();
        $subscriber->delete();
        $campaign->delete();
        $list->delete();
        $sender->delete();
        $template->delete();

        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_lists', ['id' => $list->id]);
        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
        $this->assertDatabaseMissing('newsletter_templates', ['id' => $template->id]);
        $this->assertDatabaseMissing('newsletter_subscribers_lists', ['id' => $subscriberList->id]);
    }

    /** @test */
    public function it_fails_if_sender_missing()
    {
        $list = NewsletterList::factory()->create();
        $template = NewsletterTemplate::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'status' => NewsletterCampaign::STATUS_PENDING,
            'list_id' => $list->id,
            'sender_account_id' => 9999,
            'email_content_type' => 'design',
            'email_template_id' => $template->id,
        ]);
        Artisan::call('newsletter:process-campaigns');
        $campaign->refresh();
        $this->assertEquals(NewsletterCampaign::STATUS_FAILED, $campaign->status);

        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        $list->delete();
        $template->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_lists', ['id' => $list->id]);
        $this->assertDatabaseMissing('newsletter_templates', ['id' => $template->id]);
    }

    /** @test */
    public function it_fails_if_template_missing()
    {
        $sender = NewsletterSenderAccount::factory()->create();
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'status' => NewsletterCampaign::STATUS_PENDING,
            'list_id' => $list->id,
            'sender_account_id' => $sender->id,
            'email_content_type' => 'design',
            'email_template_id' => 9999,
        ]);
        Artisan::call('newsletter:process-campaigns');
        $campaign->refresh();
        $this->assertEquals(NewsletterCampaign::STATUS_FAILED, $campaign->status);

        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        $list->delete();
        $sender->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_lists', ['id' => $list->id]);
        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
    }

    /** @test */
    public function it_fails_if_content_missing()
    {
        $sender = NewsletterSenderAccount::factory()->create();
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'status' => NewsletterCampaign::STATUS_PENDING,
            'list_id' => $list->id,
            'sender_account_id' => $sender->id,
            'email_content_type' => 'html',
            'email_content_html' => null,
        ]);
        Artisan::call('newsletter:process-campaigns');
        $campaign->refresh();
        $this->assertEquals(NewsletterCampaign::STATUS_FAILED, $campaign->status);

        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        $list->delete();
        $sender->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_lists', ['id' => $list->id]);
        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
    }

    /** @test */
    public function it_shows_error_if_queue_driver_not_database()
    {
        config(['queue.default' => 'sync']);
        $result = Artisan::call('newsletter:process-campaigns');
        $this->assertEquals(0, $result);
        Mail::assertNothingSent();
    }
}
