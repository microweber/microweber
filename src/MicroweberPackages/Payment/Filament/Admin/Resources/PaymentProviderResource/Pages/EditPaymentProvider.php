<?php

namespace MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource;

class EditPaymentProvider extends EditRecord
{
    protected static string $resource = PaymentProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
