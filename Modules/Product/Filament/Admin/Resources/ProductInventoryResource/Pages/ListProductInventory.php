<?php

namespace Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Product\Filament\Admin\Resources\ProductInventoryResource;

class ListProductInventory extends ListRecords
{
    protected static string $resource = ProductInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
