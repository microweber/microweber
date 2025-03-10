<?php

namespace Modules\Tag\Filament\Resources\TaggedResource\Pages;

use Modules\Tag\Filament\Resources\TaggedResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListTagged extends ListRecords
{
    protected static string $resource = TaggedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
