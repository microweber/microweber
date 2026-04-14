<?php

namespace Modules\Newsletter\Tests\Filament;

use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Tests\NewsletterTestCase;
use PHPUnit\Framework\Attributes\Test;

class NewsletterBreadcrumbsTest extends NewsletterTestCase
{
    #[Test]
    public function newsletter_homepage_shows_dashboard_breadcrumb(): void
    {
        $this->loginAsAdmin();

        $this->get(route('filament.admin-newsletter.pages.homepage'))
            ->assertSuccessful()
            ->assertSee('Dashboard');
    }

    #[Test]
    public function create_campaign_page_shows_campaigns_breadcrumb(): void
    {
        $this->loginAsAdmin();

        $this->get(route('filament.admin-newsletter.pages.create-campaign'))
            ->assertSuccessful()
            ->assertSeeInOrder(['Campaigns', 'Create Campaign']);
    }

    #[Test]
    public function edit_campaign_page_shows_campaign_name_and_edit_breadcrumbs(): void
    {
        $this->loginAsAdmin();
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'name' => 'Spring Launch',
            'list_id' => $list->id,
        ]);

        $this->get(route('filament.admin-newsletter.pages.edit-campaign.{id}', $campaign->id))
            ->assertSuccessful()
            ->assertSeeInOrder(['Campaigns', 'Spring Launch', 'Edit']);
    }

    #[Test]
    public function template_editor_uses_campaign_context_when_campaign_query_is_present(): void
    {
        $this->loginAsAdmin();
        $list = NewsletterList::factory()->create();
        $campaign = NewsletterCampaign::factory()->create([
            'name' => 'Abandoned Cart Recovery',
            'list_id' => $list->id,
        ]);
        $template = NewsletterTemplate::query()->create([
            'title' => 'Recovery Design',
            'json' => '{}',
        ]);

        $this->get(route('filament.admin-newsletter.pages.template-editor') . '?id=' . $template->id . '&campaignId=' . $campaign->id)
            ->assertSuccessful()
            ->assertSeeInOrder([
                'data-testid="newsletter-template-editor-breadcrumbs"',
                'Campaigns',
                'Abandoned Cart Recovery',
                'Template Editor',
            ], false);
    }

    #[Test]
    public function template_editor_falls_back_to_design_breadcrumbs_without_campaign_context(): void
    {
        $this->loginAsAdmin();
        $template = NewsletterTemplate::query()->create([
            'title' => 'Monthly Newsletter',
            'json' => '{}',
        ]);

        $this->get(route('filament.admin-newsletter.pages.template-editor') . '?id=' . $template->id)
            ->assertSuccessful()
            ->assertSeeInOrder([
                'data-testid="newsletter-template-editor-breadcrumbs"',
                'Designs',
                'Monthly Newsletter',
                'Template Editor',
            ], false);
    }
}
