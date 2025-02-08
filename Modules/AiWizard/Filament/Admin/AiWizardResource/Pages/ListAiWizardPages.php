<?php

namespace Modules\AiWizard\Filament\Admin\AiWizardResource\Pages;

use Modules\AiWizard\Filament\Admin\AiWizardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAiWizardPages extends ListRecords
{
    protected static string $resource = AiWizardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Page with AI'),
        ];
    }
}
