<?php

namespace Modules\Newsletter\Tests\Filament;

use Filament\Facades\Filament;
use Livewire\Livewire;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Tests\NewsletterTestCase;
use PHPUnit\Framework\Attributes\Test;

class NewsletterCampaignResourceTest extends NewsletterTestCase
{
    #[Test]
    public function it_can_list_newsletter_campaigns()
    {
        NewsletterCampaign::truncate();
        NewsletterList::truncate();

        $this->loginAsAdmin();
        $list = NewsletterList::factory()->create();
        $campaigns = NewsletterCampaign::factory()
            ->count(3)
            ->create(['list_id' => $list->id]);
        $list->save();
        foreach ($campaigns as $campaign) {
            $campaign->save();
        }

        $this->get(CampaignResource::getUrl('index', [], false, 'admin-newsletter'))
            ->assertSuccessful()
            ->assertSee($campaigns[0]->name)
            ->assertSee($list->name);
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

        $this->get(CampaignResource::getUrl('edit', [
            'record' => $campaign
        ], false, 'admin-newsletter'))->assertSuccessful()
            ->assertSee($campaign->name);
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
                'email_content_html' => 'required',
                'email_content_type' => 'required',
            ]);
    }
}
