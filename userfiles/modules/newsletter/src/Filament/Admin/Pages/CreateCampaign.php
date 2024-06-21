<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Pages\Page;
use MicroweberPackages\Modules\Newsletter\Filament\Components\SelectTemplate;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class CreateCampaign extends Page
{
    protected static ?string $slug = 'newsletter/create-campaign';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.create-campaign';

    protected static bool $shouldRegisterNavigation = false;

    public function form(Form $form): Form
    {
        return $form->schema([

            Wizard::make([
//                Wizard\Step::make('Template')
//                    ->schema([
//                        SelectTemplate::make('template'),
//                    ]),
                Wizard\Step::make('Campaign Details')
                    ->schema([
                        // ...
                    ]),
                Wizard\Step::make('Schedule')
                    ->schema([
                        // ...
                    ]),
            ])

        ]);
    }

}
