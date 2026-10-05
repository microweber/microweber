<?php

namespace Modules\Country\Filament\CountryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Country\Filament\CountryResource;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;
}
