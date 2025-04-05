<?php

namespace Modules\Billing\Filament\Resources\BillingUserResource\Pages;

use Modules\Billing\Filament\Resources\BillingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = BillingUserResource::class;
}
