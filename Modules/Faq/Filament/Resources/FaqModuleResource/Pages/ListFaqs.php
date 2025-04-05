<?php

namespace Modules\Faq\Filament\Resources\FaqModuleResource\Pages;

use Modules\Faq\Filament\Resources\FaqModuleResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
class ListFaqs extends ListRecords
{
    protected static string $resource = FaqModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
