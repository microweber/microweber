<?php

namespace Modules\Tag\Filament\Resources\TagGroupResource\Pages;

use Modules\Tag\Filament\Resources\TagGroupResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListTagGroups extends ListRecords
{
    protected static string $resource = TagGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
