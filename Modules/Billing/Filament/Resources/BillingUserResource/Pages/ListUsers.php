<?php

namespace Modules\Billing\Filament\Resources\BillingUserResource\Pages;

use Modules\Billing\Filament\Resources\BillingUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = BillingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
