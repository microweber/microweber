<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Pages\Page;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

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
