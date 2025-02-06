<?php

namespace Modules\Faq\Filament\Resources\FaqModuleResource\Pages;

use Modules\Faq\Filament\Resources\FaqModuleResource;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    protected static string $resource = FaqModuleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
