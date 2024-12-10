<?php

namespace Modules\Payment\Filament\Admin\Resources\PaymentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Payment\Filament\Admin\Resources\PaymentResource;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
