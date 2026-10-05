<?php

namespace Modules\Address\Filament\AddressResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Address\Filament\AddressResource;

class CreateAddress extends CreateRecord
{
    protected static string $resource = AddressResource::class;
}
