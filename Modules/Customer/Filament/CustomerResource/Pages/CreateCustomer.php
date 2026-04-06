<?php

namespace Modules\Customer\Filament\CustomerResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Customer\Filament\CustomerResource;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
