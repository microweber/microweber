<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Pages;

use Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent;
use Modules\Product\Filament\Admin\Resources\ProductResource;

class CreateProduct extends CreateContent
{
    protected static string $resource = ProductResource::class;

    // Map the "Downloadable product" toggle to the subtype column, and the
    // per-product "Email download link on order" toggle to a content_data flag.
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $isDownloadable = !empty($data['is_downloadable']);
        $data['subtype'] = $isDownloadable ? 'downloadable_product' : 'product';

        $emailOnOrder = $isDownloadable && !empty($data['email_download_on_order']) ? '1' : '0';
        $data['content_data'] = array_merge($data['content_data'] ?? [], [
            'email_download_on_order' => $emailOnOrder,
        ]);

        unset($data['is_downloadable'], $data['email_download_on_order']);

        return $data;
    }
}
