<?php

namespace Modules\Tag\Filament\Resources\TagResource\Pages;

use Modules\Tag\Filament\Resources\TagResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
