<?php

namespace Modules\Newsletter\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\CreateCampaign;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\EditCampaign;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CampaignResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('admin-newsletter');
        DB::table('newsletter_campaigns_clicked_link')->delete();
        DB::table('newsletter_campaigns_pixel')->delete();
        DB::table('newsletter_campaigns')->delete();
        DB::table('newsletter_subscribers_lists')->delete();
        DB::table('newsletter_subscribers')->delete();
        DB::table('newsletter_lists')->delete();
        DB::table('newsletter_sender_accounts')->delete();
    }

    protected function getResourceClass(): string
    {
        return CampaignResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ManageCampaigns::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $list = NewsletterList::factory()->create();
        $campaigns = NewsletterCampaign::factory()->count(3)->create([
            'list_id' => $list->id,
        ]);

        Livewire::test(ManageCampaigns::class)
            ->loadTable()
            ->assertCanSeeTableRecords($campaigns);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        $list = NewsletterList::factory()->create();
        NewsletterCampaign::factory()->count(15)->create([
            'list_id' => $list->id,
        ]);

        Livewire::test(ManageCampaigns::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'name' => 'Test Campaign Search',
            'list_id' => $list->id,
        ]);

        Livewire::test(ManageCampaigns::class)
            ->searchTable('Test Campaign')
            ->loadTable()
            ->assertCanSeeTableRecords([$campaign]);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(CreateCampaign::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_create_page_validates_required_fields(): void
    {
        Livewire::test(CreateCampaign::class)
            ->fillForm([
                'name' => '',
                'list_id' => '',
                'email_content_type' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name',
                'list_id',
                'email_content_type',
            ]);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $list = NewsletterList::factory()->create();
        $sender = NewsletterSenderAccount::factory()->create([
            'from_name' => 'Sender Name',
            'from_email' => 'sender@example.com',
            'reply_email' => 'reply@example.com',
        ]);
        $scheduledAt = now()->addDay()->startOfMinute();

        Livewire::test(CreateCampaign::class)
            ->fillForm([
                'name' => 'Test Campaign',
                'recipients_from' => 'specific_list',
                'list_id' => $list->id,
                'sender_account_id' => $sender->id,
                'from_name' => 'Campaign Sender',
                'from_email' => 'campaign@example.com',
                'reply_email' => 'campaign-reply@example.com',
                'scheduled_at' => $scheduledAt,
                'email_content_type' => 'design',
                'email_content_html' => '<p>Test content</p>',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('newsletter_campaigns', [
            'name' => 'Test Campaign',
            'list_id' => $list->id,
            'sender_account_id' => $sender->id,
            'recipients_from' => 'specific_list',
            'from_name' => 'Campaign Sender',
            'from_email' => 'campaign@example.com',
            'reply_email' => 'campaign-reply@example.com',
            'email_content_type' => 'design',
        ]);

        $campaign = NewsletterCampaign::query()->where('name', 'Test Campaign')->firstOrFail();
        $this->assertTrue($campaign->scheduled_at?->equalTo($scheduledAt));
    }

    #[Test]
    public function it_edit_page_pre_fills_form_data(): void
    {
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'name' => 'Edit Test Campaign',
            'list_id' => $list->id,
            'email_content_type' => 'html',
        ]);

        Livewire::test(EditCampaign::class, ['record' => $campaign->id])
            ->assertSuccessful()
            ->assertFormSet([
                'name' => 'Edit Test Campaign',
                'list_id' => $list->id,
            ]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'name' => 'Original Name',
            'list_id' => $list->id,
            'status' => NewsletterCampaign::STATUS_DRAFT,
        ]);

        Livewire::test(EditCampaign::class, ['record' => $campaign->id])
            ->fillForm([
                'name' => 'Updated Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('newsletter_campaigns', [
            'id' => $campaign->id,
            'name' => 'Updated Name',
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
        ]);

        Livewire::test(ManageCampaigns::class)
            ->callTableAction('delete', $campaign);

        $this->assertDatabaseMissing('newsletter_campaigns', [
            'id' => $campaign->id,
        ]);
    }

    #[Test]
    public function it_campaign_belongs_to_list(): void
    {
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
        ]);

        $this->assertInstanceOf(NewsletterList::class, $campaign->list);
        $this->assertEquals($list->id, $campaign->list->id);
    }

    #[Test]
    public function it_can_cancel_queued_campaign(): void
    {
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'status' => NewsletterCampaign::STATUS_QUEUED,
        ]);

        Livewire::test(ManageCampaigns::class)
            ->callTableAction('cancel', $campaign);

        $this->assertDatabaseHas('newsletter_campaigns', [
            'id' => $campaign->id,
            'status' => NewsletterCampaign::STATUS_CANCELED,
        ]);
    }

    #[Test]
    public function it_can_expand_opened_campaign_audience_without_per_row_email_lookups_breaking_results(): void
    {
        $list = NewsletterList::factory()->create();
        $sender = NewsletterSenderAccount::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'name' => 'Opened Base Campaign',
            'list_id' => $list->id,
            'sender_account_id' => $sender->id,
        ]);
        $subscriberA = NewsletterSubscriber::factory()->create(['email' => 'opened-a@example.com']);
        $subscriberB = NewsletterSubscriber::factory()->create(['email' => 'opened-b@example.com']);

        NewsletterCampaignPixel::factory()->create(['campaign_id' => $campaign->id, 'email' => $subscriberA->email]);
        NewsletterCampaignPixel::factory()->create(['campaign_id' => $campaign->id, 'email' => $subscriberB->email]);
        NewsletterCampaignPixel::factory()->create(['campaign_id' => $campaign->id, 'email' => $subscriberA->email]);

        Livewire::test(ManageCampaigns::class)
            ->callTableAction('expand-opened', $campaign);

        $expandedCampaign = NewsletterCampaign::query()->where('name', 'Opened Base Campaign - Opened')->firstOrFail();

        $this->assertSame('specific_list', $expandedCampaign->recipients_from);
        $this->assertSame('Hello, [[name]]! <br />How are you today?', $expandedCampaign->email_content_html);
        $this->assertDatabaseCount('newsletter_subscribers_lists', 2);
        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'list_id' => $expandedCampaign->list_id,
            'subscriber_id' => $subscriberA->id,
        ]);
        $this->assertDatabaseHas('newsletter_subscribers_lists', [
            'list_id' => $expandedCampaign->list_id,
            'subscriber_id' => $subscriberB->id,
        ]);
    }

    #[Test]
    public function it_export_action_exists(): void
    {
        Livewire::test(ManageCampaigns::class)
            ->assertTableActionExists('export');
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ManageCampaigns::class)
            ->assertTableColumnExists('name')
            ->assertTableColumnExists('list.name')
            ->assertTableColumnExists('status');
    }
}
