<?php

namespace MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\Tax\Filament\Admin\Resources\TaxResource;

class ListTaxes extends ListRecords
{
    protected static string $resource = TaxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
