<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource;
use MicroweberPackages\Modules\Newsletter\Filament\Components\SelectTemplate;

class ManageTemplates extends ManageRecords
{
    protected static string $resource = TemplatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
            Actions\Action::make('New design')
                ->modalHeading('Select a template')
                ->modalDescription('Choose a template to start with')
                ->slideOver()
                ->modalSubmitAction(false)
                ->form([
                    SelectTemplate::make('template')
                        ->label('Template')
                        ->required()
                        ->default('default'),
                ])
        ];
    }
}
