<?php

namespace Modules\Tag\Filament\Resources\TaggedResource\Pages;

use Modules\Tag\Filament\Resources\TaggedResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditTagged extends EditRecord
{
    protected static string $resource = TaggedResource::class;

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
