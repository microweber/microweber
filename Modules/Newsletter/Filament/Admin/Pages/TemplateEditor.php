<?php

namespace Modules\Newsletter\Filament\Admin\Pages;

use Filament\Pages\Page;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterTemplate;

class TemplateEditor extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'template-editor';

    protected string $view = 'microweber-module-newsletter::livewire.filament.admin.template-editor';

    public function getBreadcrumbs(): array
    {
        $newsletterRoot = route('filament.admin.pages.newsletter.homepage');

        if ($campaign = $this->getCampaign()) {
            return [
                $newsletterRoot => 'Newsletter',
                route('filament.admin-newsletter.resources.campaigns.index') => 'Campaigns',
                route('filament.admin-newsletter.pages.edit-campaign.{id}', $campaign->id) => $campaign->name,
                'Template Editor',
            ];
        }

        $breadcrumbs = [
            $newsletterRoot => 'Newsletter',
            route('filament.admin-newsletter.resources.templates.index') => 'Designs',
        ];

        if ($template = $this->getTemplate()) {
            $breadcrumbs[] = $template->title;
        }

        $breadcrumbs[] = 'Template Editor';

        return $breadcrumbs;
    }

    public function getLayout(): string
    {
        return static::$layout ?? 'filament-panels::components.layout.live-edit';
    }

    protected function getCampaign(): ?NewsletterCampaign
    {
        $campaignId = request()->integer('campaignId');

        if (! $campaignId) {
            return null;
        }

        return NewsletterCampaign::query()->find($campaignId);
    }

    protected function getTemplate(): ?NewsletterTemplate
    {
        $templateId = request()->integer('id');

        if (! $templateId) {
            return null;
        }

        return NewsletterTemplate::query()->find($templateId);
    }

    protected function getViewData(): array
    {
        return [

        ];
    }
}
