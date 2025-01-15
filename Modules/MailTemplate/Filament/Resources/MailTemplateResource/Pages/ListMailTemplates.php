<?php

namespace Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages;

use Modules\MailTemplate\Filament\Resources\MailTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailTemplates extends ListRecords
{
    protected static string $resource = MailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
