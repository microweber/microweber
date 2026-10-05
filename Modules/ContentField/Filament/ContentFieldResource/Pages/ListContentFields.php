<?php

namespace Modules\ContentField\Filament\ContentFieldResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\ContentField\Filament\ContentFieldResource;

class ListContentFields extends ListRecords
{
    protected static string $resource = ContentFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
