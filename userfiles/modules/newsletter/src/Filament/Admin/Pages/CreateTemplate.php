<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Pages\Page;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class CreateTemplate extends Page
{
    protected static ?string $slug = 'newsletter/create-template';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.create-template';

    protected function getViewData(): array
    {
        return [

        ];
    }
}
