<?php

namespace Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages;

use Modules\MailTemplate\Filament\Resources\MailTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMailTemplate extends CreateRecord
{
    protected static string $resource = MailTemplateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
