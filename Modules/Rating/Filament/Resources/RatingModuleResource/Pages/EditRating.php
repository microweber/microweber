<?php

namespace Modules\Rating\Filament\Resources\RatingModuleResource\Pages;

use Modules\Rating\Filament\Resources\RatingModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRating extends EditRecord
{
    protected static string $resource = RatingModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
