<?php

namespace Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource;

class ListProductVariantAttributes extends ListRecords
{
    protected static string $resource = ProductVariantAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
