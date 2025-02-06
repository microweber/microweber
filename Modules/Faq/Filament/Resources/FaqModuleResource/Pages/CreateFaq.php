<?php

namespace Modules\Faq\Filament\Resources\FaqModuleResource\Pages;

use Modules\Faq\Filament\Resources\FaqModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    protected static string $resource = FaqModuleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
