<?php

namespace App\Filament\Admin\Resources\ShippingProviderResource\Pages;

use App\Filament\Admin\Resources\ShippingProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingProvider extends EditRecord
{
    protected static string $resource = ShippingProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
