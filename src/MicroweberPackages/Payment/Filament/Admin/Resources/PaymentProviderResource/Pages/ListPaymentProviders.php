<?php

namespace MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource;

class ListPaymentProviders extends ListRecords
{
    protected static string $resource = PaymentProviderResource::class;

    protected function getHeaderActions(): array
    {
        $getAvailableToSetup = PaymentProviderResource::getAvailableToSetup();
        $paymentProviders = $getAvailableToSetup['paymentProviders'];
        if (!empty($paymentProviders)) {
            return [
                Actions\CreateAction::make()->label('Setup new Payment Provider'),
            ];
        }


        return [];
    }
}
