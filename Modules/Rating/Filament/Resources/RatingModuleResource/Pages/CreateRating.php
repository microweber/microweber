<?php

namespace Modules\Rating\Filament\Resources\RatingModuleResource\Pages;

use Modules\Rating\Filament\Resources\RatingModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRating extends CreateRecord
{
    protected static string $resource = RatingModuleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
