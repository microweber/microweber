<?php

namespace Modules\Newsletter\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\CreateCampaign;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\EditCampaign;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CampaignResourceTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
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

        Livewire::test(CreateCampaign::class)
            ->fillForm([
                'name' => 'Test Campaign',
                'list_id' => $list->id,
                'email_content_type' => 'design',
                'email_content_html' => '<p>Test content</p>',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('newsletter_campaigns', [
            'name' => 'Test Campaign',
            'list_id' => $list->id,
            'email_content_type' => 'design',
        ]);
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
            ->assertHasNoFormErrors()
            ->assertRedirect();

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
    public function it_can_filter_by_status(): void
    {
        $list = NewsletterList::factory()->create();
        $draftCampaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'status' => NewsletterCampaign::STATUS_DRAFT,
        ]);
        $finishedCampaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'status' => NewsletterCampaign::STATUS_FINISHED,
        ]);

        Livewire::test(ManageCampaigns::class)
            ->filterTable('status', NewsletterCampaign::STATUS_DRAFT)
            ->assertCanSeeTableRecords([$draftCampaign])
            ->assertCanNotSeeTableRecords([$finishedCampaign]);
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
    public function it_export_action_exists(): void
    {
        Livewire::test(ManageCampaigns::class)
            ->assertTableHeaderActionExists('export');
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
