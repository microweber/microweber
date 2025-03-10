<?php

namespace Modules\Tag\Filament\Resources\TagGroupResource\Pages;

use Modules\Tag\Filament\Resources\TagGroupResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditTagGroup extends EditRecord
{
    protected static string $resource = TagGroupResource::class;

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
