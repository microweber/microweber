<?php

namespace Modules\Company\Filament\CompanyResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Company\Filament\CompanyResource;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
