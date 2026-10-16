<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Pages;

use Modules\Product\Filament\Admin\Resources\ProductResource;

class EditProduct extends \Modules\Content\Filament\Admin\ContentResource\Pages\EditContent
{

    protected static string $resource = ProductResource::class;

    // Reflect the stored subtype into the "Downloadable product" toggle.
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['is_downloadable'] = ($data['subtype'] ?? 'product') === 'downloadable_product';

        return $data;
    }

    // Map the toggle back to the subtype column; 'is_downloadable' is not a
    // Product attribute, so it is removed before the record update.
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['subtype'] = !empty($data['is_downloadable']) ? 'downloadable_product' : 'product';
        unset($data['is_downloadable']);

        return $data;
    }
}
