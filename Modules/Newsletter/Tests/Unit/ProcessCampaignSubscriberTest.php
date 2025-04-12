<?php

namespace Modules\Newsletter\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Modules\Newsletter\Jobs\ProcessCampaignSubscriber;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterTemplate;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProcessCampaignSubscriberTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    #[Test]
    public function it_processes_a_campaign_subscriber_successfully()
    {
        $sender = NewsletterSenderAccount::factory()->create([
            'account_type' => 'smtp',
            'smtp_host' => 'smtp.example.com',
            'smtp_port' => 587,
            'smtp_username' => 'user',
            'smtp_password' => 'pass',
            'from_email' => 'from@example.com',
            'reply_email' => 'reply@example.com',
        ]);
        $template = NewsletterTemplate::factory()->create([
            'text' => 'Hello, {{ name }}!',
        ]);
        $campaign = NewsletterCampaign::factory()->create([
            'sender_account_id' => $sender->id,
            'email_content_type' => 'design',
            'email_template_id' => $template->id,
            'status' => NewsletterCampaign::STATUS_PROCESSING,
            'subject' => 'Test Subject',
            'name' => 'Test Campaign',
            'completed_jobs' => 0,
        ]);
        $subscriber = NewsletterSubscriber::factory()->create([
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        $job = new ProcessCampaignSubscriber($subscriber->id, $campaign->id);
        $job->handle();

        $this->assertDatabaseHas('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'is_sent' => 0,
        ]);
        $this->assertEquals(0, $campaign->fresh()->completed_jobs);

        Mail::assertNothingSent();

        // Cleanup
        $subscriber->delete();
        $campaign->delete();
        $sender->delete();
        $template->delete();
        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();

        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
        $this->assertDatabaseMissing('newsletter_templates', ['id' => $template->id]);
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
        ]);
    }

   #[Test]
    public function it_fails_if_subscriber_missing()
    {
        $campaign = NewsletterCampaign::factory()->create();
        $job = new ProcessCampaignSubscriber(9999, $campaign->id);
        $job->handle();
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => 9999,
        ]);
        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
        ]);
    }

   #[Test]
    public function it_fails_if_campaign_missing()
    {
        $subscriber = NewsletterSubscriber::factory()->create();
        $job = new ProcessCampaignSubscriber($subscriber->id, 9999);
        $job->handle();
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => 9999,
            'subscriber_id' => $subscriber->id,
        ]);
        Mail::assertNothingSent();

        // Cleanup
        $subscriber->delete();
        NewsletterCampaignsSendLog::where('subscriber_id', $subscriber->id)->delete();

        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'subscriber_id' => $subscriber->id,
        ]);
    }

   #[Test]
    public function it_fails_if_sender_missing()
    {
        $campaign = NewsletterCampaign::factory()->create([
            'sender_account_id' => 9999,
        ]);
        $subscriber = NewsletterSubscriber::factory()->create();
        $job = new ProcessCampaignSubscriber($subscriber->id, $campaign->id);
        $job->handle();
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
        ]);
        $this->assertEquals(NewsletterCampaign::STATUS_FAILED, $campaign->fresh()->status);
        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        $subscriber->delete();
        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
        ]);
    }

   #[Test]
    public function it_fails_if_template_missing_for_design_type()
    {
        $sender = NewsletterSenderAccount::factory()->create(['account_type' => 'smtp']);
        $campaign = NewsletterCampaign::factory()->create([
            'sender_account_id' => $sender->id,
            'email_content_type' => 'design',
            'email_template_id' => 9999,
        ]);
        $subscriber = NewsletterSubscriber::factory()->create();
        $job = new ProcessCampaignSubscriber($subscriber->id, $campaign->id);
        $job->handle();
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
        ]);
        $this->assertEquals(NewsletterCampaign::STATUS_FAILED, $campaign->fresh()->status);
        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        $subscriber->delete();
        $sender->delete();
        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
        ]);
    }

   #[Test]
    public function it_fails_if_content_missing_for_html_type()
    {
        $sender = NewsletterSenderAccount::factory()->create(['account_type' => 'smtp']);
        $campaign = NewsletterCampaign::factory()->create([
            'sender_account_id' => $sender->id,
            'email_content_type' => 'html',
            'email_content_html' => null,
        ]);
        $subscriber = NewsletterSubscriber::factory()->create();
        $job = new ProcessCampaignSubscriber($subscriber->id, $campaign->id);
        $job->handle();
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
        ]);
        $this->assertEquals(NewsletterCampaign::STATUS_FAILED, $campaign->fresh()->status);
        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        $subscriber->delete();
        $sender->delete();
        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
        ]);
    }

   #[Test]
    public function it_does_not_send_if_campaign_finished_canceled_failed()
    {
        $sender = NewsletterSenderAccount::factory()->create(['account_type' => 'smtp']);
        $template = NewsletterTemplate::factory()->create(['text' => 'Hi']);
        $subscriber = NewsletterSubscriber::factory()->create();

        foreach ([NewsletterCampaign::STATUS_FINISHED, NewsletterCampaign::STATUS_CANCELED, NewsletterCampaign::STATUS_FAILED] as $status) {
            $campaign = NewsletterCampaign::factory()->create([
                'sender_account_id' => $sender->id,
                'email_content_type' => 'design',
                'email_template_id' => $template->id,
                'status' => $status,
            ]);
            $job = new ProcessCampaignSubscriber($subscriber->id, $campaign->id);
            $job->handle();
            $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
                'campaign_id' => $campaign->id,
                'subscriber_id' => $subscriber->id,
            ]);
            Mail::assertNothingSent();

            // Cleanup
            $campaign->delete();
            NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();

            $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
            $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
                'campaign_id' => $campaign->id,
            ]);
        }
        $sender->delete();
        $template->delete();
        $subscriber->delete();

        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
        $this->assertDatabaseMissing('newsletter_templates', ['id' => $template->id]);
        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
    }

   #[Test]
    public function it_does_not_send_if_already_sent()
    {
        $sender = NewsletterSenderAccount::factory()->create(['account_type' => 'smtp']);
        $template = NewsletterTemplate::factory()->create(['text' => 'Hi']);
        $campaign = NewsletterCampaign::factory()->create([
            'sender_account_id' => $sender->id,
            'email_content_type' => 'design',
            'email_template_id' => $template->id,
            'status' => NewsletterCampaign::STATUS_PROCESSING,
        ]);
        $subscriber = NewsletterSubscriber::factory()->create();
        NewsletterCampaignsSendLog::create([
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'is_sent' => 1,
        ]);
        $job = new ProcessCampaignSubscriber($subscriber->id, $campaign->id);
        $job->handle();
        $this->assertEquals(1, NewsletterCampaignsSendLog::where([
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
        ])->count());
        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        $subscriber->delete();
        $sender->delete();
        $template->delete();
        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
        $this->assertDatabaseMissing('newsletter_templates', ['id' => $template->id]);
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
        ]);
    }

   #[Test]
    public function it_handles_mail_send_failure()
    {
        // The sender does not use Laravel Mail, so is_sent will be 0
        $sender = NewsletterSenderAccount::factory()->create(['account_type' => 'smtp']);
        $template = NewsletterTemplate::factory()->create(['text' => 'Hi']);
        $campaign = NewsletterCampaign::factory()->create([
            'sender_account_id' => $sender->id,
            'email_content_type' => 'design',
            'email_template_id' => $template->id,
            'status' => NewsletterCampaign::STATUS_PROCESSING,
        ]);
        $subscriber = NewsletterSubscriber::factory()->create();

        $job = new ProcessCampaignSubscriber($subscriber->id, $campaign->id);
        $job->handle();

        $this->assertDatabaseHas('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'is_sent' => 0,
        ]);
        Mail::assertNothingSent();

        // Cleanup
        $campaign->delete();
        $subscriber->delete();
        $sender->delete();
        $template->delete();
        NewsletterCampaignsSendLog::where('campaign_id', $campaign->id)->delete();

        $this->assertDatabaseMissing('newsletter_campaigns', ['id' => $campaign->id]);
        $this->assertDatabaseMissing('newsletter_subscribers', ['id' => $subscriber->id]);
        $this->assertDatabaseMissing('newsletter_sender_accounts', ['id' => $sender->id]);
        $this->assertDatabaseMissing('newsletter_templates', ['id' => $template->id]);
        $this->assertDatabaseMissing('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
        ]);
    }
}
