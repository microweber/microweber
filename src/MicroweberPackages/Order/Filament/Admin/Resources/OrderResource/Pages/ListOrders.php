<?php

namespace MicroweberPackages\Order\Filament\Admin\Resources\OrderResource\Pages;

use MicroweberPackages\Order\Filament\Admin\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
