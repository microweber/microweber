<?php

namespace Modules\ContentData\Filament\ContentDataResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\ContentData\Filament\ContentDataResource;

class ListContentData extends ListRecords
{
    protected static string $resource = ContentDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
