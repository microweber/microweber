<?php

namespace Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource;

class EditProductVariantAttribute extends EditRecord
{
    protected static string $resource = ProductVariantAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
