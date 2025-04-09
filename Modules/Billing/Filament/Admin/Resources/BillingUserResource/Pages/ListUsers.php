<?php

namespace Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource;

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
