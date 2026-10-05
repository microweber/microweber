<?php

namespace Modules\Address\Filament\AddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Address\Filament\AddressResource;

class EditAddress extends EditRecord
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
