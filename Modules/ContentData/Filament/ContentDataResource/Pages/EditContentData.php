<?php

namespace Modules\ContentData\Filament\ContentDataResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\ContentData\Filament\ContentDataResource;

class EditContentData extends EditRecord
{
    protected static string $resource = ContentDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
