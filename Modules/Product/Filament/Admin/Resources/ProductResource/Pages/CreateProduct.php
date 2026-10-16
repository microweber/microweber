<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Pages;

use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;
use Modules\Product\Filament\Admin\Resources\ProductResource;

class CreateProduct extends CreateContent
{
    protected static string $resource = ProductResource::class;

    // Map the "Downloadable product" toggle to the subtype column.
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['subtype'] = !empty($data['is_downloadable']) ? 'downloadable_product' : 'product';
        unset($data['is_downloadable']);

        return $data;
    }
}
