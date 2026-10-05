<?php

namespace Modules\ContentField\Filament\ContentFieldResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\ContentField\Filament\ContentFieldResource;

class EditContentField extends EditRecord
{
    protected static string $resource = ContentFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
