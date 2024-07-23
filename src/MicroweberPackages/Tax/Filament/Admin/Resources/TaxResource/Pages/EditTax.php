<?php

namespace MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource;

class EditTax extends EditRecord
{
    protected static string $resource = TaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
