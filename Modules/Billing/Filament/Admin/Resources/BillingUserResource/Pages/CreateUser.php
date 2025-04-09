<?php

namespace Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = BillingUserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
