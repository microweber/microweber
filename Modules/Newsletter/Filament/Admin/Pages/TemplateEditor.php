<?php

namespace Modules\Newsletter\Filament\Admin\Pages;

use Filament\Pages\Page;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;

class TemplateEditor extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'newsletter/template-editor';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.template-editor';

    public function getLayout(): string
    {
        return static::$layout ?? 'filament-panels::components.layout.live-edit';
    }

    protected function getViewData(): array
    {
        return [

        ];
    }
}
