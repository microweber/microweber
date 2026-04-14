<?php

namespace Modules\Newsletter\Tests\Filament;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use Modules\Newsletter\Jobs\ProcessCampaignSubscriber;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Tests\NewsletterTestCase;
use PHPUnit\Framework\Attributes\Test;

class NewsletterCampaignResourceTest extends NewsletterTestCase
{
    #[Test]
    public function it_can_list_newsletter_campaigns()
    {
        NewsletterCampaign::query()->delete();
        NewsletterList::query()->delete();

        $this->loginAsAdmin();
        $list = NewsletterList::factory()->create();
        $campaigns = NewsletterCampaign::factory()
            ->count(3)
            ->create(['list_id' => $list->id]);
        $list->save();
        foreach ($campaigns as $campaign) {
            $campaign->save();
        }

        $response = $this->get(CampaignResource::getUrl('index', [], false, 'admin-newsletter'));
        $response->assertSuccessful();
        $this->assertStringContainsString($campaigns[0]->name, $response->getContent());
        $this->assertStringContainsString($list->name, $response->getContent());
    }

    #[Test]
    public function it_can_render_create_page()
    {
        $this->loginAsAdmin();
        $this->get(CampaignResource::getUrl('create', [], false, 'admin-newsletter'))
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_create_newsletter_campaign()
    {
        $this->loginAsAdmin();
        $list = NewsletterList::factory()->create();
        $campaignData = NewsletterCampaign::factory()
            ->make(['list_id' => $list->id]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-newsletter'),
        );

        Livewire::test(CampaignResource\Pages\CreateCampaign::class)
            ->fillForm([
                'name' => $campaignData->name,
                'list_id' => $list->id,
                'email_content_html' => $campaignData->email_content_html,
                'email_content_type' => $campaignData->email_content_type,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('newsletter_campaigns', [
            'name' => $campaignData->name,
            'list_id' => $list->id,
        ]);
    }

    #[Test]
    public function it_can_render_edit_page()
    {
        $this->loginAsAdmin();
        $campaign = NewsletterCampaign::factory()->create();

        $response = $this->get(CampaignResource::getUrl('edit', [
            'record' => $campaign
        ], false, 'admin-newsletter'))->assertSuccessful();
        $this->assertStringContainsString($campaign->name, $response->getContent());
    }


    #[Test]
    public function it_validates_required_fields()
    {
        $this->loginAsAdmin();

        Filament::setCurrentPanel(
            Filament::getPanel('admin-newsletter'),
        );

        Livewire::test(CampaignResource\Pages\CreateCampaign::class)
            ->fillForm([
                'name' => '',
                'list_id' => null,
                'email_content_html' => '',
                'email_content_type' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'list_id' => 'required',
            ]);
    }

    #[Test]
    public function it_can_process_a_send_now_campaign_from_creation_to_send_log(): void
    {
        $this->loginAsAdmin();

        $list = NewsletterList::factory()->create([
            'name' => 'VIP Customers',
        ]);
        $template = NewsletterTemplate::factory()->create([
            'title' => 'Spring Launch Design',
            'text' => '<p>Hello {{ name }}, check out our spring launch.</p>',
        ]);
        $sender = NewsletterSenderAccount::factory()->create([
            'account_type' => 'unsupported-provider',
            'from_name' => 'Microweber News',
            'from_email' => 'news@example.com',
            'reply_email' => 'reply@example.com',
        ]);
        $subscriber = NewsletterSubscriber::factory()->create([
            'name' => 'Taylor Reader',
            'email' => 'taylor.reader@example.com',
        ]);
        NewsletterSubscriberList::factory()->create([
            'list_id' => $list->id,
            'subscriber_id' => $subscriber->id,
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-newsletter'),
        );

        Livewire::test(CampaignResource\Pages\CreateCampaign::class)
            ->fillForm([
                'name' => 'Spring VIP Launch',
                'list_id' => $list->id,
                'email_content_type' => 'design',
                'email_content_html' => '<p>Placeholder content</p>',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $campaign = NewsletterCampaign::query()
            ->latest('id')
            ->firstOrFail();

        $campaign->update([
            'recipients_from' => 'specific_list',
            'list_id' => $list->id,
            'sender_account_id' => $sender->id,
            'subject' => 'Spring launch for VIP customers',
            'email_content_type' => 'design',
            'email_template_id' => $template->id,
            'delivery_type' => NewsletterCampaign::DELIVERY_TYPE_SEND_NOW,
        ]);
        $campaign->refresh();

        $this->assertSame('specific_list', $campaign->recipients_from);
        $this->assertSame($list->id, $campaign->list_id);
        $this->assertSame($sender->id, $campaign->sender_account_id);
        $this->assertSame($template->id, $campaign->email_template_id);
        $this->assertSame(NewsletterCampaign::DELIVERY_TYPE_SEND_NOW, $campaign->delivery_type);

        $campaign->update([
            'status' => NewsletterCampaign::STATUS_PENDING,
        ]);

        config(['queue.default' => 'database']);
        Queue::fake();

        Artisan::call('newsletter:process-campaigns');

        $campaign->refresh();

        $this->assertSame(NewsletterCampaign::STATUS_PROCESSING, $campaign->status);

        Queue::assertPushed(ProcessCampaignSubscriber::class, function (ProcessCampaignSubscriber $job) use ($campaign, $subscriber): bool {
            return $job->campaignId === $campaign->id
                && $job->subscriberId === $subscriber->id;
        });

        (new ProcessCampaignSubscriber($subscriber->id, $campaign->id))->handle();

        $this->assertDatabaseHas('newsletter_campaigns_send_log', [
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
        ]);

        $sendLog = NewsletterCampaignsSendLog::query()
            ->where('campaign_id', $campaign->id)
            ->where('subscriber_id', $subscriber->id)
            ->first();

        $this->assertNotNull($sendLog);
        $this->assertSame($campaign->id, $sendLog->campaign_id);
        $this->assertSame($subscriber->id, $sendLog->subscriber_id);
    }
}
