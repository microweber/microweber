<?php

namespace Modules\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Modules\Payment\Filament\Admin\Resources\PaymentProviderResource;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;

class ListPaymentProviders extends ListRecords
{
    protected static string $resource = PaymentProviderResource::class;

    protected function getHeaderActions(): array
    {
        $getAvailableToSetup = PaymentProviderResource::getAvailableToSetup();
        $paymentProviders = $getAvailableToSetup['paymentProviders'];
        $actions = [];
        if (!empty($paymentProviders)) {
            $actions [] = Actions\CreateAction::make()->label('Setup new Payment Provider');
        }
        $actions [] = Action::make('Payments')
            ->label('Payments')
            ->url(PaymentResource::getUrl('index'));

        return $actions;
    }
}
