<?php

namespace Modules\Rating\Filament\Resources\RatingModuleResource\Pages;

use Modules\Rating\Filament\Resources\RatingModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRatings extends ListRecords
{
    protected static string $resource = RatingModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
