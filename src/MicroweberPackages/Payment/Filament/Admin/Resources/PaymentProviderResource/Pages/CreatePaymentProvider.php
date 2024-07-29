<?php

namespace MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use MicroweberPackages\Payment\Filament\Admin\Resources\PaymentProviderResource;

class CreatePaymentProvider extends CreateRecord
{
    protected static string $resource = PaymentProviderResource::class;

    protected static ?string $title = 'Setup new Payment Provider';

    protected static bool $canCreateAnother = false;

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Setup')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

}
