<?php

namespace Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource;

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
