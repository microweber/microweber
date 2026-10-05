<?php

namespace Modules\Company\Filament\CompanyResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Company\Filament\CompanyResource;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
}
