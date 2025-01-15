<?php

namespace Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages;

use Modules\MailTemplate\Filament\Resources\MailTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMailTemplate extends EditRecord
{
    protected static string $resource = MailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
