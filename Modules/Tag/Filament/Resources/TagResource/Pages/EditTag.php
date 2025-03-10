<?php

namespace Modules\Tag\Filament\Resources\TagResource\Pages;

use Modules\Tag\Filament\Resources\TagResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditTag extends EditRecord
{
    protected static string $resource = TagResource::class;

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
