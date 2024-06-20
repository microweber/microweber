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

    protected static bool $shouldRegisterNavigation = false;

    protected function getViewData(): array
    {
        $emailTemplates = [];

        $templatesPath = modules_path() .'newsletter/src/resources/views/email-templates';
        $templates = glob($templatesPath . '/*.blade.php');

        foreach ($templates as $template) {
            $filename = basename($template, '.blade.php');
            $emailTemplates[] = [
                'name' => $filename,
                'filename' => $filename,
                'demoUrl'=>route('admin.newsletter.preview-email-template-iframe') . '?filename=' . $filename,
            ];
        }

        return [
            'emailTemplates' => $emailTemplates,
        ];
    }
}
